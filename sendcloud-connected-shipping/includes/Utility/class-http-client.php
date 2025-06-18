<?php

namespace SCCSP\SendCloud\Connected\Shipping\Utility;

use SCCSP\SendCloud\Connected\Shipping\Exceptions\SCCSP_Http_Unsuccessful_Response;
use WP_HTTP_Requests_Response;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Http_Client {
	/**
	 * @var \WP_Http|null
	 */
	private $wp_client;

	/**
	 * Http_Client construct.
	 */
	public function __construct() {
		$this->wp_client = _wp_http_get_object();
	}

	/**
	 * @throws SCCSP_Http_Unsuccessful_Response
	 */
	public function delete( $url, $headers = array() ) {
		$this->request( 'DELETE', $url, $headers );
	}

	/**
	 * @throws SCCSP_Http_Unsuccessful_Response
	 */
	private function request( $method, $url, $headers = array() ) {
		SCCSP_Logger::debug( "Sending http request: $method $url, headers: " . json_encode( $headers ) );
		$response = $this->wp_client->request( $url, array( 'method' => $method, 'headers' => $headers ) );
		if($response instanceof \WP_Error){
			SCCSP_Logger::error( "Unsuccessful response for request:  $method $url. Errors: " . json_encode( $response->get_error_messages() ) );
            throw new SCCSP_Http_Unsuccessful_Response(
                sprintf(
                    'Unsuccessful response for request: %s %s. Errors: %s',
                    esc_html( $method ),
                    esc_url_raw( $url ),
                    esc_html( wp_json_encode( $response->get_error_messages() ) )
                )
            );
        }

		/**
		 * @var WP_HTTP_Requests_Response $http_response
		 */
		$http_response = $response['http_response'];
		if ( $http_response->get_status() >= 300 ) {
            throw new SCCSP_Http_Unsuccessful_Response(
                sprintf(
                    'Unsuccessful response for request: %s %s. Response: %s',
                    esc_html( $method ),
                    esc_url_raw( $url ),
                    esc_html( wp_json_encode( $http_response->to_array() ) )
                )
            );
        }

		SCCSP_Logger::debug( "Response for request:  $method $url, headers: " . json_encode( $headers ) . ". Response: " . json_encode( $http_response->to_array() ) );

		return $http_response;
	}
}
