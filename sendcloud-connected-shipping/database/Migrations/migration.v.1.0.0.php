<?php

namespace SCCSP\SendCloud\Connected\Shipping\Database\Migrations;

use SCCSP\SendCloud\Connected\Shipping\Database\SCCSP_Abstract_Migration;

/**
 * Class Migration_1_0_0
 *
 * @package SCCSP\SendCloud\Connected\Shipping\Database\Migrations
 */
class Migration_1_0_0 extends SCCSP_Abstract_Migration {

	public function execute() {
		$this->create_configs_table();
	}

	/**
	 * Creates delivery zone table.
	 */
	private function create_configs_table() {
		$table_name = $this->db->prefix . 'sendcloud_configs';
		$query      = 'CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
  				       `id` INT NOT NULL AUTO_INCREMENT,
  				       `context` VARCHAR(64) NOT NULL,
  				       `key`VARCHAR(64) NOT NULL,
  			           `value` LONGTEXT NULL,
  	                   PRIMARY KEY (`id`))';

		$this->db->query( $query );
	}
}
