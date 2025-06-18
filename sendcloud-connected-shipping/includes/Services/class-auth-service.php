<?php

namespace SCCSP\SendCloud\Connected\Shipping\Services;

use SCCSP\SendCloud\Connected\Shipping\Exceptions\SCCSP_Missing_Consumer_Key_Exception;
use SCCSP\SendCloud\Connected\Shipping\Models\SCCSP_Auth_Data;
use SCCSP\SendCloud\Connected\Shipping\Models\SCCSP_Deactivation_Data;
use SCCSP\SendCloud\Connected\Shipping\Repositories\SCCSP_Api_Key_Repository;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Auth_Code_Generator;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Shop_Helper;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Auth_Service {

	const AUTH_CODE_LENGTH = 16;

	/**
	 * @var SCCSP_Config_Service
	 */
	private $config_service;

	/**
	 * @var SCCSP_Api_Key_Repository
	 */
	private $api_key_repository;

	/**
	 * Auth_Service
	 */
	public function __construct() {
		$this->api_key_repository = new SCCSP_Api_Key_Repository();
		$this->config_service     = new SCCSP_Config_Service();
	}

	/**
	 * Saves code and code challenge
	 *
	 * @param SCCSP_Auth_Data $auth_data
	 *
	 * @return void
	 */
	public function save_auth_data( SCCSP_Auth_Data $auth_data ) {
		$this->config_service->set_auth_data( $auth_data );
	}

	/**
	 * @param SCCSP_Deactivation_Data $data
	 *
	 * @return void
	 */
	public function save_deactivation_data( SCCSP_Deactivation_Data $data) {
		$this->config_service->save_deactivation_data( $data );
	}

	/**
	 * Get Apy Key
	 *
	 * @return \SCCSP\SendCloud\Connected\Shipping\Models\SCCSP_Api_Key
	 * @throws SCCSP_Missing_Consumer_Key_Exception
	 */
	public function get_api_key() {
		$api_key = $this->api_key_repository->get_fresh_credentials();

		if ( ! $api_key->get_consumer_key() ) {
			throw new SCCSP_Missing_Consumer_Key_Exception();
		}

		return $api_key;
	}

	/**
	 * Generates redirect authorization url
	 *
	 * @param string $redirect_url
	 * @param $params
	 *
	 * @return string
	 */
	public function generate_redirect_url( $redirect_url, $params ) {
		$params['oauth_complete_url'] = SCCSP_Shop_Helper::get_controller_url( 'SCCSP_OAuth_Complete', 'complete' );

		return rtrim( $redirect_url, '/' ) . '?' . http_build_query( $params );
	}

	/**
	 * Generate auth code
	 *
	 * @return string
	 */
	public function generate_authorization_code() {
		return SCCSP_Auth_Code_Generator::generate( self::AUTH_CODE_LENGTH );
	}
}
