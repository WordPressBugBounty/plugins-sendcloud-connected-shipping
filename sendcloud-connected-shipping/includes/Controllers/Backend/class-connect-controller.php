<?php

namespace SCCSP\SendCloud\Connected\Shipping\Controllers\Backend;

use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service;
use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Connect_Service;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Response;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Connect_Controller {
	/**
	 * @var \SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Connect_Service
	 */
	private $connect_service;
	/**
	 * @var SCCSP_Config_Service
	 */
	private $config_service;

	/**
	 * Connect_Controller consrtuct
	 */
	public function __construct() {
		$this->connect_service = new SCCSP_Connect_Service();
		$this->config_service  = new SCCSP_Config_Service();
	}

	/**
	 * Enables WooCommerce API
	 */
	public function generate_redirect_url() {
		try {
			update_option( 'woocommerce_api_enabled', 'yes' );
			$redirect_url = $this->connect_service->get_redirect_url();
		} catch ( \Exception $exception ) {
			$redirect_url = null;
		}

		SCCSP_Response::json( array( 'redirect_url' => $redirect_url ) );
	}

	/**
	 * Check if integration is connected
	 */
	public function check_status() {
		$integration_id = $this->config_service->get_integration_id();
		if ( $integration_id ) {
			SCCSP_Response::json( array( 'is_connected' => true ) );

			return;
		}
		SCCSP_Response::json( array( 'is_connected' => false ) );
	}
}
