<?php

namespace SCCSP\SendCloud\Connected\Shipping\Services;

use Exception;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Http_Client;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Logger;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Shop_Helper;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Connect_Service {

	/**
	 * @var \SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service
	 */
	private $config_service;

	/**
	 * @var SCCSP_Http_Client
	 */
	private $http_client;

	public function __construct() {
		$this->config_service = new SCCSP_Config_Service();
		$this->http_client    = new SCCSP_Http_Client();
	}

	/**
	 * Get redirect url
	 *
	 * @return string
	 */
	public function get_redirect_url() {
		$permalinks_enabled = get_option( 'permalink_structure' );
		if ( ! $permalinks_enabled ) {
			return admin_url( 'admin.php?page=sendcloud-wc' );
		}

		SCCSP_Logger::info( 'Connecting to Sendcloud.' );

		$oauth_connect_url = SCCSP_Shop_Helper::get_controller_url( 'SCCSP_OAuth_Connect', 'init' );

		return sprintf( '%s/shops/woocommerce_v2/redirect/auth/connect?oauth_connect_url=%s', $this->config_service->get_panel_url(),
			urlencode( $oauth_connect_url ) );
	}

	/**
	 * Disconnect
	 *
	 * @return void
	 */
	public function disconnect() {
		$integration_id = $this->config_service->get_integration_id() ?? '';
		if ( ! $integration_id ) {
			return;
		}

		$deactivation_data = $this->config_service->get_deactivation_data();
		$callback_url = $deactivation_data->get_callback_url();

		// Add https:// prefix only if the URL doesn't already have it
		if (strpos($callback_url, 'http') !== 0) {
			$callback_url = 'https://' . $callback_url;
		}
		try {
			$this->http_client->delete(
				"{$callback_url}?integration_id={$integration_id}",
				array( 'Authorization' => $deactivation_data->get_token() )
			);
		} catch ( Exception $exception ) {
			SCCSP_Logger::error( 'Error while disconnecting. Message: ' . $exception->getMessage() );
		}
	}
}
