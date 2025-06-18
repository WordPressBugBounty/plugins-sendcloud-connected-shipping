<?php

namespace SCCSP\SendCloud\Connected\Shipping\Repositories;

use RuntimeException;
use SCCSP\SendCloud\Connected\Shipping\Models\SCCSP_Api_Key;
use WP_User;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Api_Key_Repository {
	const API_DESCRIPTION = 'SendCloud API';

	const WC_API_KEYS_TABLE_NAME = 'woocommerce_api_keys';

    /**
     * @var SCCSP_Config_Repository
     */
    private $config_repository;

    /**
     * Config_Service constructor.
     */
    public function __construct() {
        $this->config_repository = new SCCSP_Config_Repository();
    }

    /**
     * Gets fresh api_key
     *
     * @return SCCSP_Api_Key
     */
    public function get_fresh_credentials() {
        $api_key = $this->get_api_key();

		if ( is_null( $api_key ) ) {
			$api_key = $this->create_api_key();
		} else {
			$api_key = $this->update_api_key( $api_key );
		}

		return $api_key;
	}

	/**
	 * Retrieves api key from woocommerce_api_keys table
	 *
	 * @return SCCSP_Api_Key|null
	 * @throws \Exception
	 */
	public function get_api_key() {
		global $wpdb;

		$result = $wpdb->get_row( $wpdb->prepare( '
            SELECT key_id, user_id, consumer_secret
            FROM %1s
            WHERE user_id = %d AND description = %s
        ', $wpdb->prefix . self::WC_API_KEYS_TABLE_NAME, $this->get_user_id(), self::API_DESCRIPTION ), ARRAY_A );

		return $result ? SCCSP_Api_Key::from_array( $result ) : null;
	}

	/**
	 * Provides API key by consumer secret.
	 *
	 * @return SCCSP_Api_Key|null
	 */
	public function get_api_key_by_consumer_secret( $consumer_key ) {
		global $wpdb;

		$result = $wpdb->get_row( $wpdb->prepare( '
            SELECT key_id, user_id, description, permissions, consumer_key, consumer_secret, truncated_key, last_access
            FROM %1s
            WHERE consumer_secret = %d AND description = %s
        ', $wpdb->prefix . self::WC_API_KEYS_TABLE_NAME, $consumer_key, self::API_DESCRIPTION ), ARRAY_A );

		return $result ? SCCSP_Api_Key::from_array( $result ) : null;
	}

	/**
	 * Get API key by consumer key
	 *
	 * @param $consumer_key
	 *
	 * @return SCCSP_Api_Key|null
	 */
	public function get_user_data_by_consumer_key($consumer_key ) {
		global $wpdb;

		$consumer_key = wc_api_hash( sanitize_text_field( $consumer_key ) );
		$result         = $wpdb->get_row(
			$wpdb->prepare(
				"
			SELECT key_id, user_id, permissions, consumer_key, consumer_secret, nonces
			FROM {$wpdb->prefix}woocommerce_api_keys
			WHERE consumer_key = %s
		",
				$consumer_key
			), ARRAY_A
		);

		return $result ? SCCSP_Api_Key::from_array( $result ) : null;
	}

	/**
	 * Creates new api key
	 *
	 * @return SCCSP_Api_Key
	 * @throws \Exception
	 */
	private function create_api_key() {
		global $wpdb;

		list( $consumer_key, $consumer_key_hash ) = $this->generate_consumer_key();
		$consumer_secret = 'cs_' . wc_rand_hash();

		$data = array(
			'user_id'         => $this->get_user_id(),
			'description'     => self::API_DESCRIPTION,
			'permissions'     => 'read_write',
			'consumer_key'    => $consumer_key_hash,
			'consumer_secret' => $consumer_secret,
			'truncated_key'   => substr( $consumer_key, - 7 )
		);

		$wpdb->insert(
			$wpdb->prefix . self::WC_API_KEYS_TABLE_NAME,
			$data,
			array( '%d', '%s', '%s', '%s', '%s', '%s' )
		);

		$api_key = $this->get_api_key();
		if ( is_null( $api_key ) ) {
			throw new RuntimeException( 'Creating api key failed. ' );
		}

		$api_key->set_consumer_key( $consumer_key );

		return $api_key;
	}

	/**
	 * Updates existing api key
	 *
	 * @param SCCSP_Api_Key $api_key
	 *
	 * @return SCCSP_Api_Key
	 */
	private function update_api_key( $api_key ) {
		global $wpdb;

		list( $consumer_key, $consumer_key_hash ) = $this->generate_consumer_key();
		$api_key->set_consumer_key( $consumer_key );

		$wpdb->update(
			$wpdb->prefix . self::WC_API_KEYS_TABLE_NAME,
			array( 'consumer_key' => $consumer_key_hash ),
			array( 'key_id' => $api_key->get_key_id() ),
			array( '%s' ),
			array( '%d' )
		);

		return $api_key;
	}

	/**
	 * Generates consumer key
	 *
	 * @return array
	 */
	private function generate_consumer_key() {
		$consumer_key      = 'ck_' . wc_rand_hash();
		$consumer_key_hash = wc_api_hash( $consumer_key );

		return array( $consumer_key, $consumer_key_hash );
	}

	/**
	 * @throws \Exception
	 */
	private function get_user_id() {
        $username = $this->get_user_name();

        // Check if user exists
		$user = get_user_by('login', $username);

		if (!$user) {
            $password = wp_generate_password();
			// User does not exist, create new admin user
			$user_id = wp_create_user($username, $password);

			if (is_wp_error($user_id)) {
                throw new \Exception( 'Error creating user: ' . esc_html( $user_id->get_error_message() ) );
			}

			// Set the user role to administrator
			$user = new WP_User($user_id);
			$user->set_role('shop_manager');
		} else {
			$user_id = $user->ID;
		}

        return $user_id;
    }

    /**
     * Generates user name
     *
     * @return string
     */
    private function get_user_name()
    {
        $user_name = $this->config_repository->get('USER_NAME');

        if (!$user_name) {
            $user_name = 'sendcloud_api_' . wp_generate_password( 10, false );
            $this->config_repository->save( 'USER_NAME', $user_name);
        }

        return $user_name;
    }
}
