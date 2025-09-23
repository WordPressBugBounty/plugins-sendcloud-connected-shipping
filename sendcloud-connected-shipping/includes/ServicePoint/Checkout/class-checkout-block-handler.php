<?php

namespace SCCSP\SendCloud\Connected\Shipping\ServicePoint\Checkout;

use SCCSP\SendCloud\Connected\Shipping\Repositories\SCCSP_Order_Repository;
use SCCSP\SendCloud\Connected\Shipping\ServicePoint\Shipping\Service_Point_Free_Shipping_Method;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Logger;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SCCSP_Checkout_Block_Handler extends SCCSP_Checkout_Handler {

	/**
	 * @return void
	 */
	public function init() {
		add_action(
			'woocommerce_blocks_enqueue_checkout_block_scripts_after',
			array( $this, 'add_script_data' ),
			111
		);
		add_action(
			'woocommerce_blocks_enqueue_checkout_block_scripts_after',
			array( $this, 'add_carriers_to_checkout_block' ),
			111
		);

		add_action( 'woocommerce_store_api_checkout_update_order_from_request',
			array( $this, 'validate_and_save' ),
            10,
            2
        );

        add_action(
            'woocommerce_blocks_loaded',
            function() {
                woocommerce_store_api_register_update_callback( [
                    'namespace' => 'sendcloud-connected-shipping-sp-block',
                    'callback'  => function ( $data ) {
                        if ( ! empty( $data['servicePoint'] ) ) {
                            WC()->session->set( 'sendcloud_service_point', $data['servicePoint'] );
                        } else {
                            WC()->session->__unset( 'sendcloud_service_point' );
                        }
                    },
                ]);
            }
        );
	}

	/**
	 * Add carriers to checkout block
	 *
	 * @return void
	 */
	public function add_carriers_to_checkout_block() {
		$shipping_methods = WC()->session->previous_shipping_methods;
		if ( empty( $shipping_methods ) ) {
			return;
		}
		foreach ( $shipping_methods[0] as $method ) {
			$id          = explode( ':', $method );
			$name        = ! empty( $id[0] ) ? $id[0] : null;
			$instance_id = ! empty( $id[1] ) ? $id[1] : null;
			if ( Service_Point_Free_Shipping_Method::ID === $name ) {
				$this->render_carriers( $instance_id, $method );
			}
		}
	}

	/**
	 * Process place order event when woocommerce blocks are used in checkout
	 *
	 * @param  \WC_Order  $order
     * @param \WP_REST_Request $request
	 *
	 * @return void
	 */
    public function validate_and_save(\WC_Order $order, \WP_REST_Request $request) {
        /**
         * This method will be adjusted after Sendcloud team finishes React.js script
         *
         * Perhaps we should use woocommerce_store_api_checkout_update_order_from_request action for saving data
         * and woocommerce_store_api_checkout_order_processed only for validating
         */
        $chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods', '' );
        if ( ! $chosen_shipping_methods ) {
            return;
        }
        $shipping_method_id = explode( ':', reset( $chosen_shipping_methods ) )[0];

        if ( Service_Point_Free_Shipping_Method::ID !== $shipping_method_id || empty( $order->get_items( 'shipping' ) ) ) {
            return;
        }

        $service_point_selected = $this->fetch_service_point_data();

        if ( ! $service_point_selected ) {
            wc_add_notice( esc_html__( 'Please choose a service point.', 'sendcloud-connected-shipping' ), 'error' );
        } else {
            $order_repository = new SCCSP_Order_Repository();

            $service_point_json = $this->fetch_service_point_data()
                ? sanitize_text_field( wp_unslash( $this->fetch_service_point_data() ) ) : '';
            $service_point      = json_decode( $service_point_json, true );
            if ( isset( $service_point['id'], $service_point['toPostalCode'], $service_point['name'],
                $service_point['street'], $service_point['city'], $service_point['postal_code'], $service_point['house_number'] )
            ) {
                $order_repository->save_service_point_meta( $order->get_id(), $service_point_json );

                wc_clear_notices();
                return;
            }

			SCCSP_Logger::warning( 'Service point data not found.' );
		}
	}

    /**
     * Fetch service point data from request
     *
     * @return mixed|null
     */
    public function fetch_service_point_data() {
        $service_point = WC()->session->get( 'sendcloud_service_point' );

        if ( ! empty( $service_point ) ) {
            return  $service_point;
        }

        return null;
    }
}
