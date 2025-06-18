<?php

namespace SCCSP\SendCloud\Connected\Shipping\Models;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Deactivation_Data {
	/**
	 * @var string
	 */
	private $token;
	/**
	 * @var string
	 */
	private $callback_url;

	/**
	 * @param string $token
	 * @param string $callback_url
	 */
	public function __construct( $token, $callback_url ) {
		$this->token        = $token;
		$this->callback_url = $callback_url;
	}

	/**
	 * @return string
	 */
	public function get_token() {
		return $this->token;
	}

	/**
	 * @return string
	 */
	public function get_callback_url() {
		return $this->callback_url;
	}

	/**
	 * @return array
	 */
	public function to_array() {
		return array(
			'token' => $this->token,
			'callback_url' => $this->callback_url,
		);
	}

	/**
	 * @param array $data
	 *
	 * @return self
	 */
	public static function from_array( array $data ) {
		return new self(
			array_key_exists( 'token', $data ) ? $data['token'] : '',
			array_key_exists( 'callback_url', $data ) ? $data['callback_url'] : ''
		);
	}
}
