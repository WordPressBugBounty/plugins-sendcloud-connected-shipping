<?php

namespace SCCSP\SendCloud\Connected\Shipping\Controllers\Frontend;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Base_Controller {

	/**
	 * @return void
	 */
	public function index() {
		$controller_name = $this->get_param( 'sendcloud_v2_controller' );
		$class_name      = "\SCCSP\SendCloud\Connected\Shipping\Controllers\Frontend\\" . $controller_name . '_Controller';

		if ( ! $this->validate_controller_name( $controller_name ) || ! class_exists( $class_name ) ) {
			status_header( 404 );
			nocache_headers();

			require get_404_template();

			exit();
		}

		/** @var SCCSP_Base_Controller $controller */
		$controller = new $class_name();
		$controller->process();
	}

	/**
	 * @param $action
	 *
	 * @return void
	 */
	public function process( $action = '' ) {
		if ( empty( $action ) ) {
			$action = $this->get_param( 'action' );
		}

		if ( $action ) {
			if ( method_exists( $this, $action ) ) {
				$this->$action();
			} else {
				$this->return_json( array( 'error' => "Method $action does not exist!" ), 404 );
			}
		}
	}

	/**
	 * Gets request parameter if exists. Otherwise, returns null.
	 *
	 * @param string $key Request parameter key.
	 *
	 * @return mixed
	 */
	protected function get_param( string $key ) {
		if ( isset( $_REQUEST[ $key ] ) ) {
			return sanitize_text_field( wp_unslash( $_REQUEST[ $key ] ) );
		}

		return null;
	}

	/**
	 * Redirect to the specified URL
	 *
	 * @param $url
	 *
	 * @return void
	 */
	protected function redirect( $url ) {
		wp_redirect( $url );
		exit;
	}

	/**
	 * Sets response header content type to json, echos supplied $data as a json string and terminates request.
	 *
	 * @param array $data Array to be returned as a json response.
	 * @param int $status_code Response status code.
	 */
	protected function return_json( array $data, $status_code = 200 ) {
		wp_send_json( $data, $status_code );
	}

	/**
	 * Gets raw request.
	 *
	 * @return string
	 */
	protected function get_raw_input() {
		return file_get_contents( 'php://input' );
	}

	/**
	 * Gets request method.
	 *
	 * @return string
	 */
    protected function get_method() {
        // Ensure the HTTP method is sanitized and in uppercase.
        return isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( strtoupper( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : '';
    }

	/**
	 * Validates controller name by checking whether it exists in the list of known controller names.
	 *
	 * @param string $controller_name Controller name from request input.
	 *
	 * @return bool
	 */
	private function validate_controller_name( $controller_name ) {
		$allowed_controllers = [ 'SCCSP_OAuth_Connect', 'SCCSP_OAuth_Complete' ];

		return in_array( $controller_name, $allowed_controllers, true );
	}
}
