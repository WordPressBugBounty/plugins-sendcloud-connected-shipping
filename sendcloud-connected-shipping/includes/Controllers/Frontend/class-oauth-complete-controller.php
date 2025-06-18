<?php

namespace SCCSP\SendCloud\Connected\Shipping\Controllers\Frontend;

use Exception;
use SCCSP\SendCloud\Connected\Shipping\Exceptions\SCCSP_Invalid_Payload_Exception;
use SCCSP\SendCloud\Connected\Shipping\Exceptions\SCCSP_Missing_Auth_Data_Exception;
use SCCSP\SendCloud\Connected\Shipping\Exceptions\SCCSP_Request_Missing_Parameters_Exception;
use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Auth_Service;
use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Base64_Url_Encoder;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Shop_Helper;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_OAuth_Complete_Controller extends SCCSP_Base_Controller {

	const GRANT_TYPE = 'authorization_code';

	/**
	 * @var SCCSP_Auth_Service
	 */
	private $auth_service;

	/**
	 * @var SCCSP_Config_Service
	 */
	private $config_service;

	/**
	 * SCCSP_OAuth_Complete_Controller constructor
	 */
	public function __construct() {
		$this->auth_service   = new SCCSP_Auth_Service();
		$this->config_service = new SCCSP_Config_Service();
	}

	/**
	 * @return void
	 */
	public function complete() {
		try {
			$this->verify_payload();
			$this->verify_auth_data();
			$api_key = $this->auth_service->get_api_key();
			$this->config_service->delete_auth_data();

			$this->return_json( array(
				'consumer_key'    => $api_key->get_consumer_key(),
				'consumer_secret' => $api_key->get_consumer_secret(),
				'url_webshop'     => urlencode( SCCSP_Shop_Helper::get_base_shop_url() )
			) );
		} catch ( Exception $exception ) {
			$this->return_json(
				array(
					'error' => $exception->getMessage()
				),
				400
			);
		}
	}


	/**
	 * Checks if request data is valid
	 *
	 * @return void
	 * @throws SCCSP_Request_Missing_Parameters_Exception
	 * @throws SCCSP_Invalid_Payload_Exception
	 */
	private function verify_payload() {
		$is_valid = $this->get_param( 'grant_type' ) !== null &&
		            $this->get_param( 'code' ) !== null &&
		            $this->get_param( 'client_id' ) !== null &&
		            $this->get_param( 'code_verifier' ) !== null;
		if ( ! $is_valid ) {
			throw new SCCSP_Request_Missing_Parameters_Exception( 'Missing parameters.' );
		}
		if ( $this->get_param( 'grant_type' ) !== static::GRANT_TYPE ) {
			throw new SCCSP_Invalid_Payload_Exception( ' Invalid payload.' );
		}
	}

	/**
	 * Validates if authorization data is the same as the one saved during connect request
	 *
	 * @return void
	 * @throws SCCSP_Missing_Auth_Data_Exception
	 * @throws SCCSP_Invalid_Payload_Exception
	 */
	private function verify_auth_data() {
		$auth_data = $this->config_service->get_auth_data();
		if ( ! $auth_data ) {
			throw new SCCSP_Missing_Auth_Data_Exception( 'Auth data not found in the database.' );
		}

		if ( $auth_data->get_code() !== $this->get_param( 'code' ) ) {
			throw new SCCSP_Invalid_Payload_Exception( 'Invalid authorization code.' );
		}

		$computed_code_challenge = SCCSP_Base64_Url_Encoder::encode( hash( 'sha256', $this->get_param( 'code_verifier' ), true ) );
		if ( $auth_data->get_code_challenge() !== $computed_code_challenge ) {
			throw new SCCSP_Invalid_Payload_Exception( 'Invalid code verifier .' );
		}
	}
}
