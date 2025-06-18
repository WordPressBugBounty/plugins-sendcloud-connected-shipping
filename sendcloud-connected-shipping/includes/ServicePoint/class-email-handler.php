<?php

namespace SCCSP\SendCloud\Connected\Shipping\ServicePoint;

use SCCSP\SendCloud\Connected\Shipping\Repositories\SCCSP_Order_Repository;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Logger;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Version_Utility;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_View;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SCCSP_Email_Handler {
	const CLASS_NAME = __CLASS__;

	/**
	 * Order Repository
	 *
	 * @var \SCCSP\SendCloud\Connected\Shipping\Repositories\SCCSP_Order_Repository
	 */
	private $order_repository;

	/**
	 * Email_Handler constructor.
	 */
	public function __construct() {
		$this->order_repository = new SCCSP_Order_Repository();
	}

	/**
	 * Hooks email functions
	 */
	public function init() {
		add_action( 'woocommerce_email_after_order_table', array(
			$this,
			'add_service_point_data_in_email',
		), 15, 2 );
	}

	/**
	 * Adds service point information in email
	 *
	 * @param $order
	 * @param $sent_to_admin
	 */
	public function add_service_point_data_in_email( $order, $sent_to_admin ) {
		$order_id = SCCSP_Version_Utility::get_order_id( $order );
		SCCSP_Logger::info( 'Email_Handler::add_service_point_data_in_email(): ' . 'order id: ' . $order_id );
		$service_point = $this->order_repository->get_service_point_meta( $order_id );
		if ( $service_point ) {
			SCCSP_Logger::info( 'Email_Handler::add_service_point_data_in_email(): ' . 'service point: ' . json_encode( $service_point->to_array() ) );
			echo wp_kses( SCCSP_View::file( '/service-point/email-template.php' )->render(
				array(
					'address'     => $service_point->get_address_formatted(),
					'post_number' => $service_point->get_to_post_number()
				) ), SCCSP_View::get_allowed_tags() );
		}
	}
}
