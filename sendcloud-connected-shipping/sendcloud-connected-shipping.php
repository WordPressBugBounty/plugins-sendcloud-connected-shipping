<?php
/**
 * Plugin Name: Sendcloud Shipping
 * Plugin URI: https://wordpress.org/plugins/sendcloud-connected-shipping/
 * Description: Sendcloud plugin.
 * Version: 1.0.14
 * Woo:
 * Author: Sendcloud B.V.
 * Author URI: https://www.sendcloud.com
 * License: GPLv2
 * Requires at least: 4.9
 * Tested up to: 6.8
 *
 * Text Domain: sendcloud-connected-shipping
 * Domain Path: /i18n/languages/
 * WC requires at least: 3.5.0
 * WC tested up to: 9.9.5
 *
 * @package sendcloud-connected-shipping
 */

use SCCSP\SendCloud\Connected\Shipping\SCCSP_Sendcloud;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';

if ( file_exists( __DIR__ . '/dev_env.php' ) ) {
	require_once __DIR__ . '/dev_env.php';
}

SCCSP_Sendcloud::init( __FILE__ );
