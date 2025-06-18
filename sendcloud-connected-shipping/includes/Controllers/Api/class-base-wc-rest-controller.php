<?php

namespace SCCSP\SendCloud\Connected\Shipping\Controllers\Api;

use SCCSP\SendCloud\Connected\Shipping\SCCSP_Sendcloud;
use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service;
use WC_REST_Controller;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

abstract class SCCSP_Base_WC_REST_Controller extends WC_REST_Controller {
	protected $namespace = 'wc-' . SCCSP_Sendcloud::BASE_API_URI;

	/**
	 * @var SCCSP_Config_Service
	 */
	protected $config_service;

	/**
	 * @var SCCSP_Authorization
	 */
	protected $authorization;

	/**
	 * Base_WC_REST_Controller construct
	 */
	public function __construct() {
		$this->config_service = new SCCSP_Config_Service();
		$this->authorization = new SCCSP_Authorization();
	}

	/**
	 * @return bool|int
	 */
	public function authenticate() {
		return $this->authorization->sccsp_authenticate(false);
	}
}
