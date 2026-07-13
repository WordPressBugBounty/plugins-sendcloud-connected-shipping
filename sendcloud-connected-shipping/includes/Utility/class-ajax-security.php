<?php

namespace SCCSP\SendCloud\Connected\Shipping\Utility;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Centralised authorization guard for privileged AJAX endpoints.
 */
class SCCSP_Ajax_Security {

	/**
	 * Capability required to invoke Sendcloud admin AJAX actions.
	 */
	const CAPABILITY = 'manage_woocommerce';

	/**
	 * Nonce action shared by the admin AJAX endpoints.
	 */
	const NONCE_ACTION = 'sccsp_admin_ajax';

	/**
	 * Name of the nonce field/localized variable used by the admin scripts.
	 */
	const NONCE_FIELD = 'sccsp_nonce';

	/**
	 * Verifies that the current request is a legitimate, authorized admin AJAX request.
	 *
	 * @return void
	 */
	public static function verify() {
		if ( ! is_user_logged_in() || ! current_user_can( self::CAPABILITY ) ) {
			SCCSP_Response::json( array( 'error' => 'Insufficient permissions.' ), 403 );
		}

		if ( ! check_ajax_referer( self::NONCE_ACTION, self::NONCE_FIELD, false ) ) {
			SCCSP_Response::json( array( 'error' => 'Invalid or missing security token.' ), 403 );
		}
	}
}
