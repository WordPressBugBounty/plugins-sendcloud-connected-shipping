<?php

namespace SCCSP\SendCloud\Connected\Shipping\Controllers\Backend;

use SCCSP\SendCloud\Connected\Shipping\SCCSP_Sendcloud;
use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_View;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_View_Controller {

    /**
     * @var SCCSP_Config_Service
     */
    private $config_service;

    /**
     * View_Controller constructor.
     */
    public function __construct() {
        $this->config_service = new SCCSP_Config_Service();
        wp_enqueue_style( 'sendcloud-v2-css',
            SCCSP_Sendcloud::get_plugin_url( 'resources/css/sendcloud-connection-page.css' ),
            array(),
            SCCSP_Sendcloud::VERSION );
    }

    /**
     * Renders appropriate view
     */
    public function render() {
        wp_enqueue_script( 'sendcloud-v2-js-page',
            SCCSP_Sendcloud::get_plugin_url( 'resources/js/sendcloud.page.js' ),
            array( 'jquery' ),
            SCCSP_Sendcloud::VERSION,
            true );

        wp_enqueue_style(
            'sendcloud-v2-google-fonts',
            'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;700&display=swap',
            [],
            null
        );

        echo wp_kses( SCCSP_View::file( '/wc-settings/sendcloud-page.php' )->render( array(
            'panel_url'          => $this->config_service->get_panel_url(),
            'permalinks_enabled' => get_option( 'permalink_structure' ),
            'weight_unit'        => get_option( 'woocommerce_weight_unit' ),
            'integration_id'     => $this->config_service->get_integration_id(),
            'currency'           => get_woocommerce_currency_symbol( get_option( 'woocommerce_currency' ) ),
            'types'              => array(),
            'migration_required' => $this->config_service->is_migration_required(),
            'is_migration_completed' => $this->config_service->is_migration_completed(),
        ) ), SCCSP_View::get_allowed_tags() );
    }
}