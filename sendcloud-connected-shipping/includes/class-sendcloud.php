<?php

namespace SCCSP\SendCloud\Connected\Shipping;

use Automattic\WooCommerce\Utilities\FeaturesUtil;
use SCCSP\SendCloud\Connected\Shipping\Controllers\Backend\SCCSP_View_Controller;
use SCCSP\SendCloud\Connected\Shipping\Controllers\Frontend\SCCSP_Base_Controller;
use SCCSP\SendCloud\Connected\Shipping\HookHandlers\SCCSP_Api_Handler;
use SCCSP\SendCloud\Connected\Shipping\HookHandlers\SCCSP_Plugin_Disable_Handler;
use SCCSP\SendCloud\Connected\Shipping\HookHandlers\SCCSP_Product_Handler;
use SCCSP\SendCloud\Connected\Shipping\ServicePoint\Checkout\SCCSP_Checkout_Block_Handler;
use SCCSP\SendCloud\Connected\Shipping\ServicePoint\Checkout\SCCSP_Checkout_Handler;
use SCCSP\SendCloud\Connected\Shipping\ServicePoint\SCCSP_Email_Handler;
use SCCSP\SendCloud\Connected\Shipping\ServicePoint\SCCSP_Order_Admin_Handler;
use SCCSP\SendCloud\Connected\Shipping\ServicePoint\Shipping\Service_Point_Free_Shipping_Method;
use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Shop_Helper;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_View;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Database;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Logger;

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Sendcloud {
	const VERSION = '1.0.16';

	const INTEGRATION_NAME = 'sendcloudshipping';
	const BASE_API_URI = 'sendcloudshipping/v2';

	/**
	 * Instance of Sendcloud
	 *
	 * @var SCCSP_Sendcloud
	 */
	protected static $instance;

	/**
	 * Flag that signifies that the plugin is initialized.
	 *
	 * @var bool
	 */
	private $is_initialized = false;

	/**
	 * Path to Sendcloud plugin file
	 *
	 * @var string
	 */
	private $sendcloud_plugin_file;

	/**
	 * @var \SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Database
	 */
	private $database;

	/**
	 * @var \SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service
	 */
	private $config_service;

	/**
	 * @var \SCCSP\SendCloud\Connected\Shipping\ServicePoint\Checkout\SCCSP_Checkout_Handler
	 */
	private $checkout_handler;

	/**
	 * @var \SCCSP\SendCloud\Connected\Shipping\ServicePoint\Checkout\SCCSP_Checkout_Block_Handler
	 */
	private $checkout_block_handler;

	/**
	 * @var \SCCSP\SendCloud\Connected\Shipping\ServicePoint\SCCSP_Order_Admin_Handler
	 */
	private $order_admin_handler;

	/**
	 * @var \SCCSP\SendCloud\Connected\Shipping\ServicePoint\SCCSP_Email_Handler
	 */
	private $email_handler;

	/**
	 * @var SCCSP_Api_Handler
	 */
	private $api_handler;

	/**
	 * @var SCCSP_Product_Handler
	 */
	private $product_handler;

	/**
	 * @var SCCSP_Plugin_Disable_Handler
	 */
	private $plugin_disable_handler;

	/**
	 * Sendcloud constructor.
	 *
	 * @param $sendcloud_plugin_file
	 */
	private function __construct( $sendcloud_plugin_file ) {
		$this->sendcloud_plugin_file  = $sendcloud_plugin_file;
		$this->database               = new SCCSP_Database();
		$this->config_service         = new SCCSP_Config_Service();
		$this->checkout_handler       = new SCCSP_Checkout_Handler();
		$this->checkout_block_handler = new SCCSP_Checkout_Block_Handler();
		$this->order_admin_handler    = new SCCSP_Order_Admin_Handler();
		$this->email_handler          = new SCCSP_Email_Handler();
		$this->api_handler            = new SCCSP_Api_Handler();
		$this->product_handler        = new SCCSP_Product_Handler();
		$this->plugin_disable_handler = new SCCSP_Plugin_Disable_Handler();
	}

	/**
	 * Initialize the plugin and returns instance of the plugin
	 *
	 * @param $sendcloud_plugin_file
	 *
	 * @return SCCSP_Sendcloud
	 */
	public static function init( $sendcloud_plugin_file ) {
		if ( null === self::$instance ) {
			self::$instance = new self( $sendcloud_plugin_file );
		}

		self::$instance->initialize();

		return self::$instance;
	}

	/**
	 * Returns base directory path
	 *
	 * @return string
	 */
	public static function get_plugin_dir_path() {
		return rtrim( plugin_dir_path( __DIR__ ), '/' );
	}

	/**
	 * Returns url for the provided directory
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public static function get_plugin_url( $path ) {
		return rtrim( plugins_url( "/{$path}/", __DIR__ ), '/' );
	}

	/**
	 * Initialize
	 *
	 * @return void
	 * @throws \SCCSP\SendCloud\Connected\Shipping\Database\Exceptions\SCCSP_Migration_Exception
	 */
	private function initialize() {
		if ( $this->is_initialized ) {
			return;
		}
		$this->init_hooks();
		$this->database->update( is_multisite() );

		$this->is_initialized = true;
	}

	/**
	 * Init hooks
	 *
	 * @return void
	 */
	public function init_hooks() {
		if ( is_admin() && ! is_network_admin() ) {
			add_action( 'admin_menu', array( $this, 'create_admin_menu' ) );
		}
		add_action( 'init', array( $this, 'init_handler' ) );
		add_action( 'plugins_loaded', array( $this, 'init_hooks_on_plugins_loaded' ) );
		add_action( 'before_woocommerce_init', function () {
			if ( class_exists( FeaturesUtil::class ) ) {
				FeaturesUtil::declare_compatibility( 'custom_order_tables', $this->sendcloud_plugin_file, true );
                FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', $this->sendcloud_plugin_file, true );

            }
		} );
		$this->plugin_disable_handler->init();
	}

	/**
	 * Init hooks on plugin loaded event
	 *
	 * @return void
	 */
	public function init_hooks_on_plugins_loaded() {
		if ( ! SCCSP_Shop_Helper::is_woocommerce_active() ) {
			deactivate_plugins( plugin_basename( $this->sendcloud_plugin_file ) );
			add_action( 'admin_notices', array(
				$this,
				'render_deactivate_notice',
			) );

			return;
		}
		$this->api_handler->init();
		$this->product_handler->init();
		$this->init_service_point_handlers();
		register_shutdown_function( array( $this, 'log_errors' ) );
	}

	/**
	 * Renders message about WooCommerce being deactivated
	 */
	public function render_deactivate_notice() {
		echo wp_kses( SCCSP_View::file( '/plugin/deactivation-notice.php' )->render(),
            SCCSP_View::get_allowed_tags() );
	}

	/**
	 * Add translations and handle SC requests
	 *
	 * @return void
	 */
	public function init_handler() {
		if ( ! SCCSP_Shop_Helper::is_woocommerce_active() ) {
			return;
		}
		load_plugin_textdomain( 'sendcloud-connected-shipping',
			false,
			basename( dirname( $this->sendcloud_plugin_file ) ) . '/i18n/languages/' );
		$this->handle_sendcloud_request();
	}

	/**
	 * Adds Sendcloud item to backend administrator menu.
	 */
	public function create_admin_menu() {
		$controller      = new SCCSP_View_Controller();
		$sendcloud_label = esc_html__( 'Sendcloud Shipping', 'sendcloud-connected-shipping' );
		add_submenu_page(
			'woocommerce',
			$sendcloud_label,
			$sendcloud_label,
			'manage_woocommerce',
			'sendcloud-connected-shipping',
			array( $controller, 'render' )
		);
	}

	/**
	 * Register service point shipping method
	 *
	 * @param $methods
	 *
	 * @return mixed
	 */
	public function register_shipping_methods( $methods ) {
		if ( $this->config_service->get_service_point_script() ) {
			$methods[ Service_Point_Free_Shipping_Method::ID ] = Service_Point_Free_Shipping_Method::CLASS_NAME;
		}

		return $methods;
	}

	/**
	 * Log errors
	 *
	 * @return void
	 */
	public function log_errors() {
		$error = error_get_last();
		if ( $error && in_array( $error['type'], array(
				E_ERROR,
				E_PARSE,
				E_COMPILE_ERROR,
				E_USER_ERROR,
				E_RECOVERABLE_ERROR,
			), true ) ) {
			SCCSP_Logger::critical( sprintf( '%1$s in %2$s on line %3$s',
					$error['message'],
					$error['file'],
					$error['line'] ) .
			                  PHP_EOL );
		}
	}

	/**
	 * Init service point hooks and actions
	 *
	 * @return void
	 */
	private function init_service_point_handlers() {
		$this->checkout_handler->init();
		$this->checkout_block_handler->init();
		$this->order_admin_handler->init();
		$this->email_handler->init();
		add_filter( 'woocommerce_shipping_methods',
			array(
				$this,
				'register_shipping_methods',
			)
		);
	}

	/**
	 * Handle Sendcloud request
	 *
	 * @return void
	 */
	private function handle_sendcloud_request() {
		$controller_name = $this->get_param( 'sendcloud_v2_controller' );
		if ( ! empty( $controller_name ) ) {
			$controller = new SCCSP_Base_Controller();
			$controller->index();
		}
	}

	/**
	 * Gets request parameter if exists. Otherwise, returns null.
	 *
	 * @param string $key Request parameter key.
	 *
	 * @return mixed
	 */
	private function get_param( string $key ) {
		if ( isset( $_REQUEST[ $key ] ) ) {
			return sanitize_text_field( wp_unslash( $_REQUEST[ $key ] ) );
		}

		return null;
	}
}
