<?php

namespace SCCSP\SendCloud\Connected\Shipping\ServicePoint\Checkout;

use SCCSP\SendCloud\Connected\Shipping\Repositories\SCCSP_Order_Repository;
use SCCSP\SendCloud\Connected\Shipping\Repositories\SCCSP_Shipping_Method_Options_Repository;
use SCCSP\SendCloud\Connected\Shipping\SCCSP_Sendcloud;
use SCCSP\SendCloud\Connected\Shipping\ServicePoint\Shipping\Service_Point_Free_Shipping_Method;
use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Logger;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_View;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class SCCSP_Checkout_Handler
{

    const CLASS_NAME = __CLASS__;

    const SERVICE_POINT_EXTRA_FIELD_NAME_V2 = 'sendcloudshipping_service_point_extra_v2';

    /**
     * @var SCCSP_Config_Service
     */
    private $config_service;

    /**
     * @var \SCCSP\SendCloud\Connected\Shipping\Repositories\SCCSP_Shipping_Method_Options_Repository
     */
    private $shipping_method_repository;

    /**
     * @var \SCCSP\SendCloud\Connected\Shipping\Repositories\SCCSP_Order_Repository
     */
    private $order_repository;

    /**
     * Checkout_Handler constructor
     */
    public function __construct()
    {
        $this->config_service = new SCCSP_Config_Service();
        $this->shipping_method_repository = new SCCSP_Shipping_Method_Options_Repository();
        $this->order_repository = new SCCSP_Order_Repository();
    }

    /**
     * @return void
     */
    public function init()
    {
        add_action('woocommerce_checkout_after_order_review', array($this, 'add_script_data'));
        add_action('wfacp_checkout_after_order_review', array($this, 'add_script_data'));
        add_action('woocommerce_after_shipping_rate', array($this, 'add_carriers'));
        add_action('woocommerce_checkout_process', array($this, 'add_notice_if_service_point_not_chosen'));
        add_action('woocommerce_checkout_update_order_meta', array($this, 'update_order_meta'));
        add_action('woocommerce_thankyou', array($this, 'add_service_point_info'), 11);
        add_action('woocommerce_view_order', array($this, 'add_service_point_info'), 11);
    }

    /**
     * Adds service point data necessary for script in the checkout
     */
    public function add_script_data()
    {
        $script = $this->config_service->get_service_point_script();

        if (empty($script)) {
            return;
        }

        wp_register_script('sendcloud-v2-service-point-js', $script, array(), SCCSP_Sendcloud::VERSION, true);

        $service_point_data_script = $this->sc_v2_generate_service_point_data_script();

        wp_add_inline_script('sendcloud-v2-service-point-js', $service_point_data_script, 'before');
        wp_enqueue_script('sendcloud-v2-service-point-js');

        wp_enqueue_script('sendcloud-v2-service-point-block',
            SCCSP_Sendcloud::get_plugin_url('resources/js/service-point-block.js'),
            array('jquery'),
            SCCSP_Sendcloud::VERSION,
            true
        );
    }

    /**
     * Generates service point data inline script
     *
     * @return string
     */
    public function sc_v2_generate_service_point_data_script()
    {
        $script_data = array(
            'language' => esc_js($this->getFormattedLocale()),
            'cart_dimensions' => esc_js(base64_encode(json_encode($this->cart_max_dimensions()))),
            'cart_dimensions_unit' => esc_js(json_encode(get_option('woocommerce_dimension_unit'))),
            'select_spp_label' => esc_js(__('Select Service Point', 'sendcloud-connected-shipping')),
        );

        return sprintf(
            "var SENDCLOUDSHIPPING_V2_LANGUAGE = '%s';\n" .
            "var SENDCLOUDSHIPPING_V2_SELECT_SPP_LABEL = '%s';\n" .
            "var SENDCLOUDSHIPPING_V2_DIMENSIONS = '%s';\n" .
            "var SENDCLOUDSHIPPING_V2_DIMENSIONS_UNIT = '%s';\n",
            $script_data['language'],
            $script_data['select_spp_label'],
            $script_data['cart_dimensions'],
            $script_data['cart_dimensions_unit']
        );
    }

    /**
     * Add carrier hidden inout fields
     *
     * @param $method
     *
     * @return void
     */
    public function add_carriers($method)
    {
        if (Service_Point_Free_Shipping_Method::ID === $method->method_id) {
            $instance_id = $method->instance_id;
            if (!$instance_id) {
                $id = explode(':', $method->id);
                $instance_id = !empty($id[1]) ? $id[1] : null;
            }

            $this->render_carriers($instance_id, $method->id);
        }
    }

    /**
     * Checks whether user chose service point before creating an order
     */
    public function add_notice_if_service_point_not_chosen()
    {
        SCCSP_Logger::info('Checkout_Handler::add_notice_if_service_point_not_chosen() invoked ');

        $service_point_key = $this->get_selected_shipping_method_id_key();
        $nonce = $this->get_nonce();

        $service_point_extra_field = isset($_POST[self::SERVICE_POINT_EXTRA_FIELD_NAME_V2])
            ? sanitize_text_field(wp_unslash($_POST[self::SERVICE_POINT_EXTRA_FIELD_NAME_V2]))
            : '';

        $service_point_selected = !empty($service_point_extra_field)
            && (($nonce && wp_verify_nonce(sanitize_text_field($nonce),
                        'woocommerce-process_checkout'))
                || WC()->session->get('reload_checkout', false));

        if (Service_Point_Free_Shipping_Method::ID === $service_point_key && !$service_point_selected) {
            wc_add_notice(
                esc_html__('Please choose a service point.', 'sendcloud-connected-shipping'),
                'error'
            );
        }
    }

    /**
     * Updates post meta field if service point is selected
     *
     * @param $order_id
     */
    public function update_order_meta($order_id)
    {
        SCCSP_Logger::info('Checkout_Handler::update_order_meta(): ' . 'order id: ' . $order_id);

        $nonce = $this->get_nonce();
        $service_point_selected = isset($_POST[self::SERVICE_POINT_EXTRA_FIELD_NAME_V2])
            && (($nonce && wp_verify_nonce(sanitize_text_field($nonce),
                        'woocommerce-process_checkout'))
                || WC()->session->get('reload_checkout', false));

        SCCSP_Logger::info('Checkout_Handler::update_order_meta(): ' . 'service point selected: ' . $service_point_selected);

        if ($service_point_selected) {
            $service_point_json = isset($_POST[self::SERVICE_POINT_EXTRA_FIELD_NAME_V2])
                ? sanitize_text_field(wp_unslash($_POST[self::SERVICE_POINT_EXTRA_FIELD_NAME_V2])) : '';
            $service_point_data = json_decode($service_point_json, true);
            if (isset($service_point_data['id'], $service_point_data['toPostalCode'], $service_point_data['name'],
                $service_point_data['street'], $service_point_data['city'], $service_point_data['postal_code'], $service_point_data['house_number'])
            ) {
                $this->order_repository->save_service_point_meta($order_id, $service_point_json);

                return;
            }
        }

        SCCSP_Logger::warning('Service point data not found.');
    }

    /**
     * Adds service point information in the order thank you page
     *
     * @param $order_id
     */
    public function add_service_point_info($order_id)
    {
        SCCSP_Logger::info('Checkout_Handler::add_service_point_info(): ' . 'order id: ' . $order_id);
        $service_point = $this->order_repository->get_service_point_meta($order_id);
        if ($service_point) {
            SCCSP_Logger::info('Checkout_Handler::add_service_point_info(): ' . 'service point: ' . json_encode($service_point->to_array()));
            echo wp_kses(SCCSP_View::file('/service-point/order-confirmation-page.php')->render(array(
                'address' => $service_point->get_address_formatted(),
                'post_number' => $service_point->get_to_post_number()
            )), SCCSP_View::get_allowed_tags());
        }
    }

    /**
     * @param $instance_id
     * @param $method
     *
     * @return void
     */
    protected function render_carriers($instance_id, $method)
    {
        $carriers = $this->shipping_method_repository->get_service_point_instance($instance_id)->get_carriers();

        echo wp_kses(SCCSP_View::file('/service-point/checkout/service-point-carriers.php')->render(array(
            'field_id' => $method . ':carrier_select',
            'carrier_select' => isset($carriers) ? implode(',', $carriers) : '',
        )), SCCSP_View::get_allowed_tags());
    }

    /**
     * Get selected shipping method id key
     *
     * @return mixed|string
     */
    private function get_selected_shipping_method_id_key()
    {
        $nonce = $this->get_nonce();

        if (isset($_POST['shipping_method'][0])
            && $nonce
            && wp_verify_nonce(sanitize_text_field($nonce), 'woocommerce-process_checkout')) {
            $parts = explode(':', sanitize_text_field(wp_unslash($_POST['shipping_method'][0])));

            return $parts[0];
        }

        /*
         * In case where the customer account is created on checkout, nonce value is already verified by the base
         * checkout action at the start, then the customer account is created, after that nonce value is no longer valid
         * for the newly started session.
         * The reload_checkout flag is set on session by the WooCommerce system so it is safe to use as an indicator
         * of that checkout case, and since the nonce was already verified, it is safe to read request parameters.
         */
        $is_checkout_reload = WC()->session->get('reload_checkout', false);
        if (isset($_POST['shipping_method'][0]) && $is_checkout_reload) {
            $parts = explode(':', sanitize_text_field(wp_unslash($_POST['shipping_method'][0])));

            return $parts[0];
        }

        return '';
    }

    /**
     * Fetches nonce from request. If nonce is not set in request, it will return null.
     *
     * @return string|null
     */
    protected function get_nonce()
    {
        if (isset($_REQUEST['woocommerce-process-checkout-nonce'])) {
            return wp_kses_post(wp_unslash($_REQUEST['woocommerce-process-checkout-nonce']));
        }

        if (isset($_REQUEST['_wpnonce'])) {
            return wp_kses_post(wp_unslash($_REQUEST['_wpnonce']));
        }

        return null;
    }

    /**
     * Gets product dimensions
     *
     * @return array
     */
    private function cart_max_dimensions()
    {
        $dimensions = array();
        $cart = WC()->cart;

        foreach ($cart->get_cart() as $values) {
            $product = $values['data'];
            if ($product->has_dimensions()) {
                $dimensions[] = array(
                    $product->get_length(),
                    $product->get_width(),
                    $product->get_height(),
                );
            }
        }

        return $dimensions;
    }

    /**
     * Returns formatted locale to be used in the JS script
     *
     * @return string
     */
    private function getFormattedLocale(): string
    {
        return strtolower(str_replace('_', '-', get_locale()));
    }
}
