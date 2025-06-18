<?php

namespace SCCSP\SendCloud\Connected\Shipping\Controllers\Api;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Status_Controller extends SCCSP_Base_WC_REST_Controller {
	const CLASS_NAME = __CLASS__;
	protected $rest_base = '/statuses';

	/**
	 * @return void
	 */
	public function register_routes() {
		register_rest_route( $this->namespace, $this->rest_base, array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_statuses' ),
				'permission_callback' => array( $this, 'authenticate' ),
			)
		) );
	}

	/**
	 * @return array
	 */
	public function get_statuses() {
		return array( 'statuses' => wc_get_order_statuses() );
	}
}
