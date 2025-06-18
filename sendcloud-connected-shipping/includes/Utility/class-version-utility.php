<?php

namespace SCCSP\SendCloud\Connected\Shipping\Utility;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Version_Utility {

	/**
	 * Compare WooCommerce version.
	 *
	 * @param string $version
	 * @param string $operator
	 *
	 * @return bool
	 */
	public static function compare( $version, $operator = '>=' ) {
		return version_compare( WC()->version, $version, $operator );
	}

	/**
	 * Get order id
	 *
	 * @param $order
	 *
	 * @return mixed
	 */
	public static function get_order_id( $order ) {
		if ( self::compare( '3.0' ) ) {
			return $order->get_id();
		}

		return $order->id;
	}
}
