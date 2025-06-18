<?php

namespace SCCSP\SendCloud\Connected\Shipping\HookHandlers;

use SCCSP\SendCloud\Connected\Shipping\Controllers\Api\SCCSP_Integration_Controller;
use SCCSP\SendCloud\Connected\Shipping\Controllers\Api\SCCSP_Service_Point_Controller;
use SCCSP\SendCloud\Connected\Shipping\Controllers\Api\SCCSP_Status_Controller;
use SCCSP\SendCloud\Connected\Shipping\Controllers\Backend\SCCSP_Connect_Controller;
use SCCSP\SendCloud\Connected\Shipping\Controllers\Backend\SCCSP_Support_Controller;
use SCCSP\SendCloud\Connected\Shipping\Controllers\Backend\SCCSP_Migration_Controller;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Api_Handler {

	/**
	 * Init ajax actions and API endpoints
	 *
	 * @return void
	 */
	public function init() {
		$this->init_ajax_actions();
		add_action( 'rest_api_init',
			array(
				$this,
				'init_api_endpoints',
			)
		);
	}

	/**
	 * Init API endpoints
	 *
	 * @return void
	 */
	public function init_api_endpoints() {
		$integration_controller = new SCCSP_Integration_Controller();
		$integration_controller->register_routes();

		$status_controller = new SCCSP_Status_Controller();
		$status_controller->register_routes();

		$service_point_controller = new SCCSP_Service_Point_Controller();
		$service_point_controller->register_routes();
	}

	/**
	 * Init AJAX actions
	 *
	 * @return void
	 */
	private function init_ajax_actions() {
		add_action( 'wp_ajax_get_redirect_sc_v2_url', array(
			new SCCSP_Connect_Controller(),
			'generate_redirect_url',
		) );
		add_action( 'wp_ajax_sc_check_status', array(
			new SCCSP_Connect_Controller(),
			'check_status',
		) );
		add_action( 'wp_ajax_sc_support_get', array(
			new SCCSP_Support_Controller(),
			'get',
		) );
		add_action( 'wp_ajax_sc_support_save', array(
			new SCCSP_Support_Controller(),
			'save',
		) );
        add_action( 'wp_ajax_migrate_service_points', array(
            new SCCSP_Migration_Controller(),
            'migrate_service_points'
        ));
        add_action('wp_ajax_sc_check_migration', array(
            new SCCSP_Migration_Controller(),
            'check_migration_status',
        ));
	}
}
