<?php

namespace SCCSP\SendCloud\Connected\Shipping\Controllers\Frontend;

use Exception;
use SCCSP\SendCloud\Connected\Shipping\Exceptions\SCCSP_Invalid_Payload_Exception;
use SCCSP\SendCloud\Connected\Shipping\Exceptions\SCCSP_Request_Missing_Parameters_Exception;
use SCCSP\SendCloud\Connected\Shipping\Models\SCCSP_Auth_Data;
use SCCSP\SendCloud\Connected\Shipping\Models\SCCSP_Deactivation_Data;
use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Auth_Service;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_OAuth_Connect_Controller extends SCCSP_Base_Controller {

	const RESPONSE_TYPE = 'code';

	const SCOPE = 'api';

	/**
	 * @var SCCSP_Auth_Service
	 */
	private $auth_service;

	/**
	 * SCCSP_OAuth_Connect_Controller constructor
	 */
	public function __construct() {
		$this->auth_service = new SCCSP_Auth_Service();
	}


	/**
	 * @return void
	 */
	public function init() {
		try {
			$this->validate_data();

			$code = $this->auth_service->generate_authorization_code();
			$this->auth_service->save_auth_data( new SCCSP_Auth_Data( $code, $this->get_param( 'code_challenge' ) ) );
			$this->auth_service->save_deactivation_data(
				new SCCSP_Deactivation_Data(
					$this->get_param( 'deactivation_token' ),
					$this->get_param( 'deactivation_callback_url' )
				)
			);

			$this->redirect( $this->auth_service->generate_redirect_url(
				$this->get_param( 'redirect_uri' ),
				array( 'code' => $code, 'state' => $this->get_param( 'state' ) )
			) );
		} catch ( Exception $exception ) {
			$this->return_json( array(
				'error' => $exception->getMessage()
			), 400 );
		}

	}

	/**
	 * @throws SCCSP_Invalid_Payload_Exception
	 * @throws SCCSP_Request_Missing_Parameters_Exception
	 */
	private function validate_data() {
		$is_valid =
			$this->get_param( 'redirect_uri' ) !== null &&
			$this->get_param( 'client_id' ) !== null &&
			$this->get_param( 'response_type' ) !== null &&
			$this->get_param( 'scope' ) !== null &&
			$this->get_param( 'state' ) !== null &&
			$this->get_param( 'code_challenge' ) !== null &&
			$this->get_param( 'code_challenge_method' ) !== null &&
			$this->get_param( 'deactivation_callback_url' ) !== null &&
			$this->get_param( 'deactivation_token' ) !== null;

		if ( ! $is_valid ) {
			throw new SCCSP_Request_Missing_Parameters_Exception( 'Missing parameters' );
		}

		if ( $this->get_param( 'response_type' ) !== static::RESPONSE_TYPE || $this->get_param( 'scope' ) !== static::SCOPE ) {
			throw new SCCSP_Invalid_Payload_Exception( 'Invalid payload.' );
		}
	}
}
