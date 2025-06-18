<?php

namespace SCCSP\SendCloud\Connected\Shipping\Utility;

use SCCSP\SendCloud\Connected\Shipping\SCCSP_Sendcloud;
use WC_Logger;
use WC_Logger_Interface;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Logger_Factory {
	/**
	 * Create WooCommerce logger instance based on the WooCommerce version.
	 *
	 * @return WC_Logger_Interface|null
	 */
	public static function create() {
		if ( ! SCCSP_Shop_Helper::is_woocommerce_active() ) {
			return null;
		}

		if ( SCCSP_Version_Utility::compare( '2.7', '>=' ) ) {
			return wc_get_logger();
		}

		return new WC_Logger();
	}

	/**
	 * Log message with the appropriate WooCommerce logger based on the version.
	 *
	 * @param WC_Logger_Interface $logger
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 */
	public static function log( WC_Logger_Interface $logger, $level, $message, $context = array() ) {
		if ( SCCSP_Version_Utility::compare( '2.7', '>=' ) ) {
			$context['source'] = SCCSP_Sendcloud::INTEGRATION_NAME;
			$logger->log( $level, $message, $context );

			return;
		}

		$message = strtoupper( $level ) . ' ' . $message;
		$logger->add( SCCSP_Sendcloud::INTEGRATION_NAME, $message );
	}
}
