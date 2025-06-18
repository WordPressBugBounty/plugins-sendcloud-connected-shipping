<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

require_once __DIR__ . '/vendor/autoload.php';

// ***********************************************************************************
// STEP 1. ***************************************************************************
// Drop configuration.                                                               *
// ***********************************************************************************
function sccsp_load_woocommerce() {
    if ( ! empty( $GLOBALS['woocommerce'] ) ) {
        return;
    }

    $standard_paths = array(
        WP_PLUGIN_DIR . '/woocommerce/woocommerce.php',
        WPMU_PLUGIN_DIR . '/woocommerce/woocommerce.php',
        ABSPATH . WP_PLUGIN_DIR . '/woocommerce/woocommerce.php',
        ABSPATH . WPMU_PLUGIN_DIR . '/woocommerce/woocommerce.php',
    );

    foreach ( $standard_paths as $standard_path ) {
        if ( file_exists( $standard_path ) ) {
            require_once $standard_path;

            break;
        }
    }
}

// ***********************************************************************************
// STEP 3. ***************************************************************************
// Delete user and key.                                                                    *
// ***********************************************************************************
function sccsp_drop_user_and_api_keys( wpdb $wpdb ) {
    $query = "SELECT * FROM {$wpdb->prefix}sendcloud_configs WHERE `key`='USER_NAME'";
    $result = $wpdb->get_results( $query, ARRAY_A );

    if ( $result ) {
        $user_name = $result[0]['value'];
        $user = get_user_by( 'login', $user_name );
        if ($user) {
            wp_delete_user($user->ID);
        }
    }
}

// ***********************************************************************************
// STEP 3. ***************************************************************************
// Drop database.                                                                    *
// ***********************************************************************************

function sccsp_drop_database( wpdb $wpdb ) {
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}sendcloud_configs" );

    delete_option( 'SCCSP_SENDCLOUD_SCHEMA_VERSION' );
}

// ***********************************************************************************
// STEP 4. ***************************************************************************
// Execute.                                                                          *
// ***********************************************************************************

global $wpdb;
if ( is_multisite() ) {
    $sites = get_sites();
    foreach ( $sites as $site ) {
        switch_to_blog( $site->blog_id );
        sccsp_drop_user_and_api_keys( $wpdb );
        sccsp_drop_database( $wpdb );
        restore_current_blog();
    }
} else {
    sccsp_drop_user_and_api_keys( $wpdb );
    sccsp_drop_database( $wpdb );
}
