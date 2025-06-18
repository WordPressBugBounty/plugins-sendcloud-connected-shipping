<?php

namespace SCCSP\SendCloud\Connected\Shipping\HookHandlers;

use Exception;
use SCCSP\SendCloud\Connected\Shipping\SCCSP_Sendcloud;
use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service;
use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Connect_Service;
use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Webhook_Service;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Logger;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Shop_Helper;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Plugin_Disable_Handler {
	/**
	 * @var SCCSP_Connect_Service
	 */
	private $connect_service;

	/**
	 * @var SCCSP_Config_Service
	 */
	private $config_service;

    /**
     * @var SCCSP_Webhook_Service
     */
    private $webhook_service;

	/**
	 * Plugin_Disable_Handler construct.
	 */
	public function __construct() {
		$this->connect_service = new SCCSP_Connect_Service();
		$this->config_service  = new SCCSP_Config_Service();
        $this->webhook_service = new SCCSP_Webhook_Service();
    }

	/**
	 * @return void
	 */
	public function init() {
		register_deactivation_hook( SCCSP_Sendcloud::get_plugin_dir_path() . '/sendcloud-connected-shipping.php', array(
			$this,
			'deactivate'
		) );
	}

    /**
     * @return void
     */
	public function deactivate() {
		if ( SCCSP_Shop_Helper::is_woocommerce_active() ) {
			SCCSP_Logger::info( 'Deactivating Sendcloud' );
		}
		$this->connect_service->disconnect();
        $this->webhook_service->remove_woocommerce_webhooks();
        $this->config_service->set_integration_id( '' );
		$this->config_service->set_service_point_script( '' );
		$this->config_service->set_service_point_carriers( array() );
		$this->config_service->delete_auth_data();
		$this->config_service->delete_deactivation_data();
	}
}
