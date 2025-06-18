<?php

namespace SCCSP\SendCloud\Connected\Shipping\Controllers\Api;

use SCCSP\SendCloud\Connected\Shipping\Models\SCCSP_Api_Key;
use SCCSP\SendCloud\Connected\Shipping\Repositories\SCCSP_Api_Key_Repository;
use WP_Error;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Authorization extends \WC_REST_Authentication {

	/**
	 * @var SCCSP_Api_Key_Repository
	 */
	private $api_key_repository;

	public function __construct() {
        parent::__construct();
		$this->api_key_repository = new SCCSP_Api_Key_Repository();
	}

	/**
	 * Authenticate user.
	 *
	 * @param int|false $user_id User ID if one has been determined, false otherwise.
	 * @return int|false
	 */
	public function sccsp_authenticate( $user_id ) {
		// Do not authenticate twice and check if is a request to our endpoint in the WP REST API.
		if ( ! empty( $user_id ) || ! $this->is_request_to_rest_api() ) {
			return $user_id;
		}

		if ( is_ssl() ) {
			$user_id = $this->perform_basic_authentication();
		}

		if ( $user_id ) {
			return $user_id;
		}

		return $this->perform_oauth_authentication();
	}

	/**
	 * Basic Authentication.
	 *
	 * SSL-encrypted requests are not subject to sniffing or man-in-the-middle
	 * attacks, so the request can be authenticated by simply looking up the user
	 * associated with the given consumer key and confirming the consumer secret
	 * provided is valid.
	 *
	 * @return int|bool
	 */
    private function perform_basic_authentication() {
        $this->auth_method = 'basic_auth';
        $consumer_key      = '';
        $consumer_secret   = '';

        // If the $_GET parameters are present, sanitize and use them first.
        if ( ! empty( $_GET['consumer_key'] ) && ! empty( $_GET['consumer_secret'] ) ) {
            $consumer_key    = sanitize_text_field( wp_unslash( $_GET['consumer_key'] ) );
            $consumer_secret = sanitize_text_field( wp_unslash( $_GET['consumer_secret'] ) );
        }

        // If the above is not present, sanitize and use basic auth headers.
        if ( empty( $consumer_key ) && ! empty( $_SERVER['PHP_AUTH_USER'] ) && ! empty( $_SERVER['PHP_AUTH_PW'] ) ) {
            $consumer_key    = sanitize_text_field( wp_unslash( $_SERVER['PHP_AUTH_USER'] ) );
            $consumer_secret = sanitize_text_field( wp_unslash( $_SERVER['PHP_AUTH_PW'] ) );
        }

        // Stop if we don't have any key.
        if ( empty( $consumer_key ) || empty( $consumer_secret ) ) {
            return false;
        }

        // Get user data using the sanitized consumer key.
        $this->user = $this->api_key_repository->get_user_data_by_consumer_key( $consumer_key );
        if ( empty( $this->user ) ) {
            return false;
        }

        // Validate user secret using a timing-safe comparison.
        if ( ! hash_equals( $this->user->get_consumer_secret(), $consumer_secret ) ) {
            $this->set_error( new WP_Error(
                'woocommerce_rest_authentication_error',
                esc_html__( 'Consumer secret is invalid.', 'sendcloud-connected-shipping' ),
                array( 'status' => 401 )
            ) );

            return false;
        }

        return $this->user->get_user_id();
    }

	private function perform_oauth_authentication() {
		$this->auth_method = 'oauth1';

		$params = $this->get_oauth_parameters();
		if ( empty( $params ) ) {
			return false;
		}

		// Fetch WP user by consumer key.
		$this->user = $this->api_key_repository->get_user_data_by_consumer_key( $params['oauth_consumer_key'] );

		if ( empty( $this->user ) ) {
			$this->set_error( new WP_Error( 'woocommerce_rest_authentication_error', esc_html__( 'Consumer key is invalid.', 'sendcloud-connected-shipping' ), array( 'status' => 401 ) ) );

			return false;
		}

		// Perform OAuth validation.
		$signature = $this->check_oauth_signature( $this->user, $params );
		if ( is_wp_error( $signature ) ) {
			$this->set_error( $signature );
			return false;
		}

		$timestamp = $this->check_oauth_timestamp( $params['oauth_timestamp'] );
		if ( is_wp_error( $timestamp ) ) {
			$this->set_error( $timestamp );
			return false;
		}

		return $this->user->get_user_id();
	}

	/**
	 * Verify that the consumer-provided request signature matches our generated signature,
	 * this ensures the consumer has a valid key/secret.
	 *
	 * @param SCCSP_Api_Key $user   User data.
	 * @param array    $params The request parameters.
	 *
	 * @return WP_Error|bool
	 */
	private function check_oauth_signature( $user, $params ) {
        $http_method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : '';
        $request_path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';

        $wp_base = get_home_url( null, '/', 'relative' );
        if ( substr( $request_path, 0, strlen( $wp_base ) ) === $wp_base ) {
            $request_path = substr( $request_path, strlen( $wp_base ) );
        }
        $base_request_uri = rawurlencode( get_home_url( null, $request_path, is_ssl() ? 'https' : 'http' ) );

        if ( ! isset( $params['oauth_signature'] ) ) {
            return new WP_Error(
                'woocommerce_rest_authentication_error',
                esc_html__( 'Invalid signature - oauth_signature is missing.', 'sendcloud-connected-shipping' ),
                array( 'status' => 401 )
            );
        }
        $consumer_signature = rawurldecode( str_replace( ' ', '+', sanitize_text_field( wp_unslash( $params['oauth_signature'] ) ) ) );
        unset( $params['oauth_signature'] );

        if ( ! uksort( $params, 'strcmp' ) ) {
            return new WP_Error(
                'woocommerce_rest_authentication_error',
                esc_html__( 'Invalid signature - failed to sort parameters.', 'sendcloud-connected-shipping' ),
                array( 'status' => 401 )
            );
        }

        // Normalize and sanitize parameter keys/values.
        $params = $this->normalize_parameters( array_map( 'sanitize_text_field', $params ) );

        // Generate the query string and the string to sign.
        $query_string   = implode( '%26', $this->join_with_equals_sign( $params ) );
        $string_to_sign = $http_method . '&' . $base_request_uri . '&' . $query_string;

        // Validate the OAuth signature method.
        if ( 'HMAC-SHA1' !== $params['oauth_signature_method'] && 'HMAC-SHA256' !== $params['oauth_signature_method'] ) {
            return new WP_Error(
                'woocommerce_rest_authentication_error',
                esc_html__( 'Invalid signature - signature method is invalid.', 'sendcloud-connected-shipping' ),
                array( 'status' => 401 )
            );
        }

        // Determine the hashing algorithm.
        $hash_algorithm = strtolower( sanitize_text_field( str_replace( 'HMAC-', '', $params['oauth_signature_method'] ) ) );
        $secret         = sanitize_text_field( $user->get_consumer_secret() ) . '&';
        $signature      = base64_encode( hash_hmac( $hash_algorithm, $string_to_sign, $secret, true ) );

        // Compare the computed signature with the provided consumer signature.
        if ( ! hash_equals( $signature, $consumer_signature ) ) {
            return new WP_Error(
                'woocommerce_rest_authentication_error',
                esc_html__( 'Invalid signature - provided signature does not match.', 'sendcloud-connected-shipping' ),
                array( 'status' => 401 )
            );
        }

        return true;
    }

	/**
	 * Verify that the timestamp and nonce provided with the request are valid. This prevents replay attacks where
	 * an attacker could attempt to re-send an intercepted request at a later time.
	 *
	 * - A timestamp is valid if it is within 15 minutes of now.
	 * - A nonce is valid if it has not been used within the last 15 minutes.
	 *
	 * @param int      $timestamp The unix timestamp for when the request was made.
	 *
	 * @return WP_Error|bool
	 */
	private function check_oauth_timestamp( $timestamp ) {
		$valid_window = 15 * 60; // 15 minute window.

		if ( ( $timestamp < time() - $valid_window ) || ( $timestamp > time() + $valid_window ) ) {
			return new WP_Error( 'woocommerce_rest_authentication_error', esc_html__( 'Invalid timestamp.', 'sendcloud-connected-shipping' ), array( 'status' => 401 ) );
		}

		return true;
	}

	/**
	 * Normalize each parameter by assuming each parameter may have already been
	 * encoded, so attempt to decode, and then re-encode according to RFC 3986.
	 *
	 * Note both the key and value is normalized so a filter param like:
	 *
	 * 'filter[period]' => 'week'
	 *
	 * is encoded to:
	 *
	 * 'filter%255Bperiod%255D' => 'week'
	 *
	 * This conforms to the OAuth 1.0a spec which indicates the entire query string
	 * should be URL encoded.
	 *
	 * @see rawurlencode()
	 * @param array $parameters Un-normalized parameters.
	 * @return array Normalized parameters.
	 */
	private function normalize_parameters( $parameters ) {
		$keys       = wc_rest_urlencode_rfc3986( array_keys( $parameters ) );
		$values     = wc_rest_urlencode_rfc3986( array_values( $parameters ) );
		$parameters = array_combine( $keys, $values );

		return $parameters;
	}

	/**
	 * Creates an array of urlencoded strings out of each array key/value pairs.
	 *
	 * @param  array  $params       Array of parameters to convert.
	 * @param  array  $query_params Array to extend.
	 * @param  string $key          Optional Array key to append.
	 * @return string               Array of urlencoded strings.
	 */
	private function join_with_equals_sign( $params, $query_params = array(), $key = '' ) {
		foreach ( $params as $param_key => $param_value ) {
			if ( $key ) {
				$param_key = $key . '%5B' . $param_key . '%5D'; // Handle multi-dimensional array.
			}

			if ( is_array( $param_value ) ) {
				$query_params = $this->join_with_equals_sign( $param_value, $query_params, $param_key );
			} else {
				$string         = $param_key . '=' . $param_value; // Join with equals sign.
				$query_params[] = wc_rest_urlencode_rfc3986( $string );
			}
		}

		return $query_params;
	}
}
