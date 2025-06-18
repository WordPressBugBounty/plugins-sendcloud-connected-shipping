<?php

namespace SCCSP\SendCloud\Connected\Shipping\ServicePoint\Shipping;

use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Service_Point_Free_Shipping_Method extends SCCSP_Abstract_Flat_Rate_Free_Shipping_Method {
	const CLASS_NAME = __CLASS__;

	const ID = 'service_point_v2_shipping_method';

	/**
	 * Configuration Service
	 *
	 * @var SCCSP_Config_Service
	 */
	private $config_service;

    /**
     * @var string[]
     */
    private $carrier_select;

    /**
	 * Init user set variables.
	 */
	public function init() {
		$this->id                 = self::ID;
		$this->method_title       = esc_html__( 'Service Point Delivery', 'sendcloud-connected-shipping' );
		$this->method_description = wp_kses( esc_html__( 'Deliver to a service point in the customer’s area. [Sendcloud]',
			'sendcloud-connected-shipping' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) );

		parent::init();

        $this->carrier_select = explode(',', $this->get_option( 'carrier_select') );
	}

	/**
	 * Checks if this method is enabled or not
	 *
	 * @return bool
	 */
	public function is_enabled() {
		$script = $this->get_config_service()->get_service_point_script();
		if ( empty( $script ) ) {
			return false;
		}

		return parent::is_enabled();
	}

	/**
	 * Add extra fields
	 *
	 * @param $form_fields
	 */
	protected function add_extra_fields( &$form_fields ) {
		parent::add_extra_fields( $form_fields );
        $settings = get_option($this->get_instance_option_key());
        $carrier_select = isset($settings['carrier_select']) && is_array($settings['carrier_select'])
            ? array_map('trim', $settings['carrier_select'])
            : [];
		$form_fields['carrier_select_v2'] = array(
            'id'          => 'sendcloud_shipping',
            'option_key'  => 'sendcloud_shipping',
			'title'       => esc_html__( 'Carrier Selection', 'sendcloud-connected-shipping' ),
			'type'        => 'multiselect',
			'default'     => $carrier_select,
			'desc_tip'    => true,
			'description' => esc_html__( "Select one or more carriers from your Sendcloud enabled list (e.g. UPS, DPD, DHL). An empty selection will display all your Sendcloud enabled carriers.",
				'sendcloud-connected-shipping' ),
			'options'     => $this->get_config_service()->get_service_point_carriers(),
		);
	}

	/**
	 * Returns configuration service
	 *
	 * @return SCCSP_Config_Service
	 */
	private function get_config_service() {
		if ( ! $this->config_service ) {
			$this->config_service = new SCCSP_Config_Service();
		}

		return $this->config_service;
	}
}
