<?php

namespace SCCSP\SendCloud\Connected\Shipping\Models;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Auth_Data {
	/**
	 * @var string
	 */
	private $code;

	/**
	 * @var string
	 */
	private $code_challenge;

	/**
	 * @param string $code
	 * @param string $code_challenge
	 */
	public function __construct( $code, $code_challenge ) {
		$this->code           = $code;
		$this->code_challenge = $code_challenge;
	}

	/**
	 * @return string
	 */
	public function get_code() {
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function get_code_challenge() {
		return $this->code_challenge;
	}

	/**
	 * @return array
	 */
	public function to_array() {
		return array(
			'code' => $this->code,
			'code_challenge' => $this->code_challenge,
		);
	}

	/**
	 * @param array $data
	 *
	 * @return self
	 */
	public static function from_array( array $data ) {
		return new self(
			array_key_exists( 'code', $data ) ? $data['code'] : '',
			array_key_exists( 'code_challenge', $data ) ? $data['code_challenge'] : ''
		);
	}
}
