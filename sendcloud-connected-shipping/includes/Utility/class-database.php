<?php

namespace SCCSP\SendCloud\Connected\Shipping\Utility;

use SCCSP\SendCloud\Connected\Shipping\Database\SCCSP_Migrator;
use SCCSP\SendCloud\Connected\Shipping\SCCSP_Sendcloud;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Database
{
    private $db;

    /**
     * Database constructor.
     */
    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
    }

    /**
     * Updates the current site's schema when needed.
     *
     * Each site is upgraded when it loads the plugin. Traversing the network here
     * repeats every site's version check on every frontend, admin and cron request.
     *
     * @param bool $is_multisite Whether to retain multisite migration error logging.
     *
     */
    public function update( $is_multisite ) {

        if ( !$is_multisite ) {
            $this->do_update();
            return;
        }

        try {
            $this->do_update();
        } catch (\Throwable $e) {
            $message =  sprintf(
                'Migration for store ID %d failed: %s',
                get_current_blog_id(),
                $e->getMessage()
            );
            SCCSP_Logger::error( $message );
        }
    }

    /**
     * Updates schema for current site.
     *
     */
    private function do_update() {
        $current_schema_version = get_option( 'SCCSP_SENDCLOUD_SCHEMA_VERSION');
        $current_plugin_version = SCCSP_Sendcloud::VERSION;

        if ( $current_plugin_version === $current_schema_version ) {
            return;
        }

        $migrator = new SCCSP_Migrator( $this->db, $current_schema_version );
        $migrator->execute();
	    update_option( 'SCCSP_SENDCLOUD_SCHEMA_VERSION', $current_plugin_version );
    }
}
