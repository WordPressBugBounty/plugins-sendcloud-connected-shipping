<?php

namespace SCCSP\SendCloud\Connected\Shipping\Controllers\Backend;

use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Response;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Support_Controller {

	/**
	 * @var SCCSP_Config_Service
	 */
	private $config_service;

	public function __construct() {
		$this->config_service = new SCCSP_Config_Service();
	}

	public function get() {
		SCCSP_Response::json( array(
			'INTEGRATION_ID' => $this->config_service->get_min_log_level(),
			'SERVICE_POINT'  => $this->config_service->get_service_point_script(),
			'CARRIERS'       => $this->config_service->get_service_point_carriers(),
			'MIN_LOG_LEVEL'  => $this->config_service->get_min_log_level(),
		) );
	}

    public function save() {
        if ( isset( $_POST['MIN_LOG_LEVEL'] ) ) {
            $min_log_level = absint( $_POST['MIN_LOG_LEVEL'] );
            $this->config_service->set_min_log_level( $min_log_level );
        }

        SCCSP_Response::json( array() );
    }
}
