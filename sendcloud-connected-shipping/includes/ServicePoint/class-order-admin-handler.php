<?php

namespace SCCSP\SendCloud\Connected\Shipping\ServicePoint;

use SCCSP\SendCloud\Connected\Shipping\Repositories\SCCSP_Order_Repository;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Logger;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Version_Utility;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_View;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SCCSP_Order_Admin_Handler {

	/**
	 * @var \SCCSP\SendCloud\Connected\Shipping\Repositories\SCCSP_Order_Repository
	 */
	private $order_repository;

	public function __construct() {
		$this->order_repository = new SCCSP_Order_Repository();
	}

	public function init() {
		add_action( 'woocommerce_admin_order_data_after_shipping_address',
			array( $this, 'add_service_point_data_in_admin_order' ),
			11 );
	}

	/**
	 * Adds service point information in the order details page
	 *
	 * @param $order
	 */
	public function add_service_point_data_in_admin_order( $order ) {
		$order_id = SCCSP_Version_Utility::get_order_id( $order );
        SCCSP_Logger::info( 'Checkout_Handler::add_service_point_data_in_admin_order(): ' . 'order id: ' . $order_id );

		$service_point = $this->order_repository->get_service_point_meta( $order_id );
		if ( $service_point ) {
			echo wp_kses( SCCSP_View::file( '/service-point/order-admin.php' )->render( array(
				'address'     => $service_point->get_address_formatted(),
				'post_number' => $service_point->get_to_post_number(),
			) ), SCCSP_View::get_allowed_tags() );
		}
	}
}
