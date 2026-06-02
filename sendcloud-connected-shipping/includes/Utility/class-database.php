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

        if ( !$is_multisite ) {
            $this->do_update();
            return;
        }

        $this->batch_process_multisite();
    }

    /**
     * Batch schema update for multistore setups
     *
     * @return void
     */
    private function batch_process_multisite()
    {
        $batch_size = 100;
        $offset = 0;

        do {
            $sites = get_sites( [
                'fields' => 'ids',
                'number' => $batch_size,
                'offset' => $offset,
            ] );

            foreach ( $sites as $site_id ) {

                switch_to_blog( $site_id );

                try {
                    $this->do_update();

                } catch (\Throwable $e) {
                    $message =  sprintf(
                        'Migration for store ID %d failed: %s',
                        $site_id,
                        $e->getMessage()
                    );
                    SCCSP_Logger::error( $message );
                } finally {
                    restore_current_blog();
                }
            }

            $offset += $batch_size;
        } while (!empty($sites));
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
