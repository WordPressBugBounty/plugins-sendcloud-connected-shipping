<?php

namespace SCCSP\SendCloud\Connected\Shipping\Database\Migrations;

use SCCSP\SendCloud\Connected\Shipping\Database\SCCSP_Abstract_Migration;

/**
 * Class Migration_1_0_0
 *
 * @package SCCSP\SendCloud\Connected\Shipping\Database\Migrations
 */
class Migration_1_0_28 extends SCCSP_Abstract_Migration {

	public function execute() {
		$this->create_configs_table();
	}

	/**
	 * Creates delivery zone table.
	 */
    private function create_configs_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'sendcloud_configs';
        $charset_collate = $wpdb->get_charset_collate();

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        context VARCHAR(64) NOT NULL,
        `key` VARCHAR(64) NOT NULL,
        `value` LONGTEXT NULL,
        PRIMARY KEY (id),
        KEY context (context),
        KEY `key` (`key`)
    ) $charset_collate;";

        dbDelta($sql);
    }
}
