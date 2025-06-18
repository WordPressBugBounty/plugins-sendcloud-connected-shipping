<?php

namespace SCCSP\SendCloud\Connected\Shipping\ServicePoint\Shipping;

use WC_Shipping_Flat_Rate;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class SCCSP_Abstract_Flat_Rate_Free_Shipping_Method extends WC_Shipping_Flat_Rate {

	const CLASS_NAME = __CLASS__;

	public $plugin_id = 'sendcloudshipping_v2_';

	const SC_MIN_ORDER_AMOUNT = 'min_order_amount';
	const SC_MIN_AMOUNT_OR_COUPON = 'min_amount_or_coupon';
	const SC_MIN_AMOUNT_AND_COUPON = 'min_amount_and_coupon';

	/**
	 * Free shipping enabled
	 *
	 * @var string
	 */
	protected $free_shipping_enabled;
	/**
	 * Free shipping min amount
	 *
	 * @var float
	 */
	protected $free_shipping_min_amount;
	/**
	 * Free shipping requires
	 *
	 * @var string
	 */
	protected $free_shipping_requires;
	/**
	 * Ignore discounts
	 *
	 * @var string
	 */
	protected $ignore_discounts;

	/**
	 * Init user set variables.
	 */
	public function init() {
		add_filter( "woocommerce_shipping_instance_form_fields_{$this->id}",
			array( $this, 'override_form_fields_config' ) );

		parent::init();

		$this->free_shipping_enabled    = $this->get_option( 'free_shipping_enabled' );
		$this->free_shipping_min_amount = $this->get_option( 'free_shipping_min_amount' );
		$this->free_shipping_requires   = $this->get_option( 'free_shipping_requires' );
		$this->ignore_discounts         = $this->get_option( 'ignore_discounts' );
	}

	/**
	 * Overrides title default value and extends form fields
	 *
	 * @param $form_fields
	 *
	 * @return mixed
	 */
	public function override_form_fields_config( $form_fields ) {
		$form_fields['title']['default'] = $this->method_title;
		$this->add_extra_fields( $form_fields );

		return $form_fields;
	}

	/**
	 * Initialize form fields
	 */
	public function init_form_fields() {
		parent::init_form_fields();
		$this->add_extra_fields( $this->form_fields );
	}

	/**
	 * Calculates shipping costs
	 *
	 * @param array $package
	 */
	public function calculate_shipping( $package = array() ) {
		if ( $this->check_free_shipping() ) {
			$this->add_rate( array(
				'id'      => $this->get_rate_id(),
				'label'   => $this->title,
				'cost'    => 0,
				'taxes'   => false,
				'package' => $package,
			) );
		} else {
			parent::calculate_shipping( $package );
		}
	}

	/**
	 * Checks whether or not shipping is free
	 *
	 * @return bool
	 */
	protected function check_free_shipping() {
		if ( 'yes' !== $this->free_shipping_enabled || ! isset( WC()->cart->cart_contents_total ) ) {
			return false;
		}

		$has_coupon = $this->has_coupon();

		$total = WC()->cart->get_displayed_subtotal();
		if ( 'incl' === WC()->cart->get_tax_price_display_mode() ) {
			$total -= WC()->cart->get_cart_discount_tax_total();
		}

		if ( 'no' === $this->ignore_discounts ) {
			$total -= WC()->cart->get_discount_total();
		}

		$min_amount_condition = $total >= $this->free_shipping_min_amount;;

		if ( static::SC_MIN_AMOUNT_OR_COUPON === $this->free_shipping_requires ) {
			return $has_coupon || $min_amount_condition;
		}

		if ( static::SC_MIN_AMOUNT_AND_COUPON === $this->free_shipping_requires ) {
			return $has_coupon && $min_amount_condition;
		}

		return $min_amount_condition;
	}

	/**
	 * Check if there is a coupon in the cart
	 *
	 * @return bool
	 */
	protected function has_coupon() {
		if ( ! in_array( $this->free_shipping_requires, array(
			static::SC_MIN_AMOUNT_AND_COUPON,
			static::SC_MIN_AMOUNT_OR_COUPON,
		), true ) ) {
			return false;
		}
		$coupons = WC()->cart->get_coupons();
		if ( empty( $coupons ) ) {
			return false;
		}

		foreach ( $coupons as $coupon ) {
			if ( $coupon->is_valid() && $coupon->get_free_shipping() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Add extra fields
	 *
	 * @param $form_fields
	 */
	protected function add_extra_fields( &$form_fields ) {
		$form_fields['free_shipping_enabled'] = array(
			'title'   => esc_html__( 'Enable Free Shipping', 'sendcloud-connected-shipping' ),
			'type'    => 'select',
			'class'   => 'wc-enhanced-select',
			'default' => '',
			'options' => array(
				'no'  => esc_html__( 'No', 'sendcloud-connected-shipping' ),
				'yes' => esc_html__( 'Yes', 'sendcloud-connected-shipping' ),
			),
		);

		$form_fields['free_shipping_requires'] = array(
			'title'   => esc_html__( 'Free shipping requires...', 'sendcloud-connected-shipping' ),
			'type'    => 'select',
			'class'   => 'sc-free-shipping-requires',
			'default' => 'min_order_amount',
			'options' => array(
				static::SC_MIN_ORDER_AMOUNT      => esc_html__( 'A minimum order amount', 'sendcloud-connected-shipping' ),
				static::SC_MIN_AMOUNT_OR_COUPON  => esc_html__( 'A minimum order amount OR a coupon', 'sendcloud-connected-shipping' ),
				static::SC_MIN_AMOUNT_AND_COUPON => esc_html__( 'A minimum order amount AND a coupon', 'sendcloud-connected-shipping' ),
			),
		);

		$form_fields['free_shipping_min_amount'] = array(
			'title'       => esc_html__( 'Minimum Order Amount for Free Shipping', 'sendcloud-connected-shipping' ),
			'type'        => 'price',
			'placeholder' => wc_format_localized_price( 0 ),
			'description' => esc_html__( 'If enabled, users will need to spend this amount to get free shipping.', 'sendcloud-connected-shipping' ),
			'default'     => '0',
			'desc_tip'    => true,
			'class'       => 'sc-free-shipping-min-amount'
		);

		$form_fields['ignore_discounts'] = array(
			'title'       => esc_html__( 'Coupons discounts', 'sendcloud-connected-shipping' ),
			'label'       => esc_html__( 'Apply minimum order rule before coupon discount', 'sendcloud-connected-shipping' ),
			'type'        => 'checkbox',
			'description' => esc_html__( 'If checked, free shipping would be available based on pre-discount order amount.', 'sendcloud-connected-shipping' ),
			'default'     => 'no',
			'desc_tip'    => true,
			'class'       => 'sc-ignore-discounts'
		);
	}
}
