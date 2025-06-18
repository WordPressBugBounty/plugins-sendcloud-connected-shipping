<?php

namespace SCCSP\SendCloud\Connected\Shipping\Controllers\Api;

use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Webhook_Service;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Logger;
use WP_Error;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Integration_Controller extends SCCSP_Base_WC_REST_Controller {
	const CLASS_NAME = __CLASS__;
    const SENDCLOUD_V1_CARRIERS = 'sendcloudshipping_service_point_carriers';

    protected $rest_base = '/integration';

    /**
     * @var SCCSP_Webhook_Service
     */
    private $webhook_service;

    public function __construct()
    {
        parent::__construct();
        $this->webhook_service = new SCCSP_Webhook_Service();
    }

    /**
	 * @return void
	 */
	public function register_routes() {
		register_rest_route( $this->namespace ,  $this->rest_base, array(
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'integration_connected' ),
				'permission_callback' => array( $this, 'authenticate' ),
			),
			array(
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'integration_disconnected' ),
				'permission_callback' => array( $this, 'authenticate' ),
			),
		) );
	}

    /**
	 * @param $data
	 *
	 * @return array|\WP_Error
	 */
	public function integration_connected( $data ) {
		SCCSP_Logger::info( 'POST sendcloudshipping/v2/integration API endpoint invoked. Data: ' . json_encode( $data ) );
		if ( isset( $data['integration_id'] ) ) {
			$this->config_service->set_integration_id( $data['integration_id'] );
            $carriers_option = get_option(self::SENDCLOUD_V1_CARRIERS);

            if ($carriers_option && !$this->config_service->is_migration_completed()) {
                $this->config_service->set_migration_required(true);
            }
        } else {
			SCCSP_Logger::error( 'Integration ID missing in the payload. Data: ' . json_encode( $data ) );

			return new WP_Error(
				'sc-invalid-payload',
                esc_html__( 'Invalid payload.', 'sendcloud-connected-shipping' ),
				array( 'status' => 400 )
			);
		}

		return array( 'message' => esc_html__( 'Integration connected', 'sendcloud-connected-shipping' ) );
	}

	/**
	 * @param $data
	 *
	 * @return array|\WP_Error
	 */
	public function integration_disconnected( $data ) {
		SCCSP_Logger::info( 'DELETE sendcloudshipping/v2/integration API endpoint invoked. Data: ' . json_encode( $data ) );
		if ( isset( $data['integration_id'] ) ) {
			$this->config_service->set_integration_id( null );
            $this->config_service->delete_migration_required();
            $this->config_service->delete_migration_completed();

            $this->webhook_service->remove_woocommerce_webhooks();
        } else {
			SCCSP_Logger::error( 'Integration ID missing in the payload. Data: ' . json_encode( $data ) );

			return new WP_Error(
				'sc-invalid-payload',
                esc_html__( 'Invalid payload.', 'sendcloud-connected-shipping' ),
				array( 'status' => 400 )
			);
		}

		return array( 'message' => esc_html__( 'Integration disconnected', 'sendcloud-connected-shipping' ) );
	}
}
