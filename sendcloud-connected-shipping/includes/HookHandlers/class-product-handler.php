<?php

namespace SCCSP\SendCloud\Connected\Shipping\HookHandlers;

use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Version_Utility;
use WC_Product;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Product_Handler {
	static $hs_code_key = 'sc_hs_code';
	static $country_of_origin_key = 'sc_country_of_origin';
	static $ean_code_key = 'sc_ean_code';


	/**
	 * Init product hook handlers
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'woocommerce_process_product_meta', array( $this, 'set_product_meta_data' ) );
		if ( SCCSP_Version_Utility::compare( '9.2', '<' ) ) {
			add_action( 'woocommerce_product_options_inventory_product_data', array( $this, 'add_ean_code_field' ) );
		}
		add_action( 'woocommerce_product_options_shipping_product_data', array(
			$this,
			'add_international_shipping_fields'
		) );
	}

	/**
	 * Adds EAN code field on product details page
	 *
	 * @return void
	 */
	public function add_ean_code_field() {
		/**
		 * @global WC_Product $product_object
		 */
		global $product_object;
		woocommerce_wp_text_input(
			array(
				'id'          => self::$ean_code_key,
				'label'       => esc_html__( 'EAN by Sendcloud', 'sendcloud-connected-shipping' ),
				'placeholder' => 'Enter the EAN code',
				'desc_tip'    => 'true',
				'description' => esc_html__( 'Enter the EAN (European Article Number) for this product if you want to use scanning functionality provided by Pack&Go. EAN is a unique identifier used internationally to distinguish products.',
					'sendcloud-connected-shipping' ),
				'value'       => $product_object->get_meta( self::$ean_code_key )
			)
		);
	}

	/**
	 * Adds HS code and country of origin fields on product details page
	 *
	 * @return void
	 */
	public function add_international_shipping_fields() {
		/**
		 * @global WC_Product $product_object
		 */
		global $product_object;

		woocommerce_wp_text_input(
			array(
				'id'          => self::$hs_code_key,
				'label'       => esc_html__( 'HS Code by Sendcloud', 'sendcloud-connected-shipping' ),
				'placeholder' => 'Enter the HS Code',
				'desc_tip'    => 'true',
				'description' => esc_html__( 'The HS code is the harmonized system code. If you want to ship your products internationally, you would need to enter these codes. These codes provide customs with information so that correct tariffs can be applied to the order.',
					'sendcloud-connected-shipping' ),
				'value'       => $product_object->get_meta( self::$hs_code_key )

			)
		);
		woocommerce_wp_select(
			array(
				'id'          => self::$country_of_origin_key,
				'label'       => esc_html__( 'Country of origin by Sendcloud', 'sendcloud-connected-shipping' ),
				'placeholder' => '(None)',
				'desc_tip'    => 'true',
				'description' => esc_html__( 'In most cases, the county where the product is manufactured.',
					'sendcloud-connected-shipping' ),
				'options'     => array_merge( array( "" => esc_html__( 'None', 'sendcloud-connected-shipping' ) ),
					WC()->countries->get_countries() ),
				'value'       => $product_object->get_meta( self::$country_of_origin_key )
			)
		);
	}

	/**
	 * Saves product meta on product update event
	 *
	 * @param $post_id
	 *
	 * @return void
	 */
	public function set_product_meta_data( $post_id ) {
		$product = wc_get_product( $post_id );
		$this->update_product_meta_data( $product );
		foreach ( $product->get_children() as $variation_id ) {
			$this->update_product_meta_data( wc_get_product( $variation_id ) );
		}
	}

	/**
	 * @param  WC_Product|null  $product
	 *
	 * @return void
	 */
	private function update_product_meta_data( $product ) {
		$fields = array( self::$hs_code_key, self::$country_of_origin_key, self::$ean_code_key );
		foreach ( $fields as $field ) {
            $sanitized_value = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '' ;
			$product->update_meta_data( $field, $sanitized_value );
		}
		$product->save_meta_data();
	}
}
