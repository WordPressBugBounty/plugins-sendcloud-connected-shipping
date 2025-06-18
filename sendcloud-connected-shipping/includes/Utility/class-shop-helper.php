<?php

namespace SCCSP\SendCloud\Connected\Shipping\Utility;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Shop_Helper {
	/**
	 * Gets URL for CleverReach controller.
	 *
	 * @param string $name Name of the controller without "CleverReach" and "Controller".
	 * @param string $action Name of the action.
	 * @param array $params Associative array of parameters.
	 *
	 * @return string
	 */
	public static function get_controller_url( $name, $action = '', array $params = array() ) {
		$query = array( 'sendcloud_v2_controller' => $name );
		if ( ! empty( $action ) ) {
			$query['action'] = $action;
		}

		$query = array_merge( $query, $params );

		return self::get_base_shop_url() . '/?' . http_build_query( $query );
	}

	/**
	 * Get base shop URL
	 *
	 * @return string
	 */
	public static function get_base_shop_url() {
		$site_url = get_option( 'home' );

		if ( defined( 'SC_V2_NGROK_URL' ) ) {
			return SC_V2_NGROK_URL;
		}

		return $site_url;
	}

	/**
	 * @return bool
	 */
	public static function is_woocommerce_active() {
		if ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return true;
		}

		return false;
	}
}
