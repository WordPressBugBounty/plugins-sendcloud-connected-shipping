<?php

namespace SCCSP\SendCloud\Connected\Shipping\Models;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Service_Point_Instance {

	/**
	 * Carriers
	 *
	 * @var array
	 */
	private $carriers;

	/**
	 * @param array $carriers
	 */
	public function __construct( array $carriers ) {
		$this->carriers = $carriers;
	}

	/**
	 * @return array
	 */
	public function get_carriers() {
		return $this->carriers;
	}

	/**
	 * @param $data
	 *
	 * @return self
	 */
	public static function from_array( $data ) {
		return new self(
			array_key_exists( 'carrier_select_v2', $data ) && !empty($data['carrier_select_v2']) ? $data['carrier_select_v2'] : array()
		);
	}
}
