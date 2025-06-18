<?php

namespace SCCSP\SendCloud\Connected\Shipping\Controllers\Api;

use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Logger;
use WP_Error;
use WP_REST_Server;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Service_Point_Controller extends SCCSP_Base_WC_REST_Controller {
	const CLASS_NAME = __CLASS__;

	protected $rest_base = '/service_point';

	/**
	 * @return void
	 */
	public function register_routes() {
		register_rest_route( $this->namespace ,  $this->rest_base, array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'enable_service_points' ),
				'permission_callback' => array( $this, 'authenticate' ),
			),
			array(
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'disable_service_points' ),
				'permission_callback' => array( $this, 'authenticate' ),
			),
            array(
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => array( $this, 'update_service_points' ),
                'permission_callback' => array( $this, 'authenticate' ),
            ),
		) );
	}

	/**
	 * @param $data
	 *
	 * @return array|WP_Error
	 */
	public function enable_service_points( $data ) {
		SCCSP_Logger::info( 'POST sendcloudshipping/v2/service_point API endpoint invoked. Data: ' . json_encode( $data ) );
		if ( isset( $data['script_url'] ) ) {
			$this->config_service->set_service_point_script( $data['script_url'] );
			$this->config_service->set_service_point_carriers( $data['carriers'] );
		} else {
			SCCSP_Logger::error( 'Integration ID missing in the payload. Data: ' . json_encode( $data ) );

            return new WP_Error(
                'sc-invalid-payload',
                esc_html__( 'Invalid payload.', 'sendcloud-connected-shipping' ),
                array( 'status' => 400 )
            );
		}

		return array( 'message' => esc_html__( 'Service points enabled successfully.', 'sendcloud-connected-shipping' ) );
	}

	/**
	 * @return array
	 */
	public function disable_service_points() {
		SCCSP_Logger::info( 'DELETE sendcloudshipping/v2/service_point API endpoint invoked.' );

		$this->config_service->set_service_point_script( null );
		$this->config_service->set_service_point_carriers( array() );

		return array( 'message' => esc_html__( 'Service points disabled successfully.', 'sendcloud-connected-shipping' ) );
	}

    /**
     * @param $data
     *
     * @return array|WP_Error
     */
    public function update_service_points( $data ) {
        SCCSP_Logger::info( 'PATCH sendcloudshipping/v2/service_point API endpoint invoked. Data: ' . json_encode( $data ) );
        if ( isset( $data['carriers'] ) ) {
            $this->config_service->set_service_point_carriers( $data['carriers'] );
        } else {
            SCCSP_Logger::error( 'Carrier list is missing in the payload. Data: ' . json_encode( $data ) );

            return new WP_Error(
                'sc-invalid-payload',
                esc_html__( 'Invalid payload.', 'sendcloud-connected-shipping' ),
                array( 'status' => 400 )
            );
        }

        return array( 'message' => esc_html__( 'Service points updated successfully.', 'sendcloud-connected-shipping' ) );
    }
}
