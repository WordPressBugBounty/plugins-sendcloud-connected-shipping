<?php

namespace SCCSP\SendCloud\Connected\Shipping\Utility;

use SCCSP\SendCloud\Connected\Shipping\Database\SCCSP_Migrator;
use SCCSP\SendCloud\Connected\Shipping\SCCSP_Sendcloud;
use WP_Site;

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
     * Performs database update.
     *
     * @param $is_multisite
     *
     */
    public function update( $is_multisite ) {
        if ( $is_multisite ) {
            $sites = get_sites();
            /**
             * WP site
             *
             * @var WP_Site $site
             */
            foreach ( $sites as $site ) {
                switch_to_blog( $site->blog_id );
                $this->do_update();
                restore_current_blog();
            }
        } else {
            $this->do_update();
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
