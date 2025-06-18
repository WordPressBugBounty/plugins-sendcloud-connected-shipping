<?php

namespace SCCSP\SendCloud\Connected\Shipping\Models;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Service_Point_Meta {

	/**
	 * ID
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Post number
	 *
	 * @var string
	 */
	private $to_post_number;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $street;

	/**
	 * @var string
	 */
	private $city;

	/**
	 * @var string
	 */
	private $postal_code;

	/**
	 * @var string
	 */
	private $house_number;

	/**
	 * @param  string  $id
	 * @param  string  $to_post_number
	 * @param  string  $name
	 * @param  string  $street
	 * @param  string  $city
	 * @param  string  $postal_code
	 * @param  string  $house_number
	 */
	public function __construct(
		string $id,
		string $to_post_number,
		string $name,
		string $street,
		string $city,
		string $postal_code,
		string $house_number
	) {
		$this->id             = $id;
		$this->to_post_number = $to_post_number;
		$this->name           = $name;
		$this->street         = $street;
		$this->city           = $city;
		$this->postal_code    = $postal_code;
		$this->house_number   = $house_number;
	}

	public function get_id(): string {
		return $this->id;
	}

	public function get_to_post_number(): string {
		return $this->to_post_number;
	}

	public function get_name(): string {
		return $this->name;
	}

	public function get_street(): string {
		return $this->street;
	}

	public function get_city(): string {
		return $this->city;
	}

	public function get_postal_code(): string {
		return $this->postal_code;
	}

	public function get_house_number(): string {
		return $this->house_number;
	}

	/**
	 * Return object as array
	 *
	 * @return array
	 */
	public function to_array() {
		return array(
			'id'           => $this->id,
			'toPostalCode' => $this->to_post_number,
			'name'         => $this->name,
			'street'       => $this->street,
			'city'         => $this->city,
			'postal_code'  => $this->postal_code,
			'house_number' => $this->house_number
		);
	}

	/**
	 * Creates object from array
	 *
	 * @param $data
	 *
	 * @return SCCSP_Service_Point_Meta
	 */
	public static function from_array( $data ) {
		return new self(
			array_key_exists( 'id', $data ) ? $data['id'] : '',
			array_key_exists( 'toPostalCode', $data ) ? $data['toPostalCode'] : '',
			array_key_exists( 'name', $data ) ? $data['name'] : '',
			array_key_exists( 'street', $data ) ? $data['street'] : '',
			array_key_exists( 'city', $data ) ? $data['city'] : '',
			array_key_exists( 'postal_code', $data ) ? $data['postal_code'] : '',
			array_key_exists( 'house_number', $data ) ? $data['house_number'] : ''
		);
	}

	/**
	 * @return string
	 */
	public function get_address_formatted() {
		return $this->name . ' | ' . $this->street . ' ' . $this->house_number . ' | ' . $this->postal_code . ' ' . $this->city;
	}
}
