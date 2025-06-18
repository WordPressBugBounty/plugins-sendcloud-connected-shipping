<?php

namespace SCCSP\SendCloud\Connected\Shipping\Database;

use SCCSP\SendCloud\Connected\Shipping\Database\Utility\SCCSP_Migration_Reader;
use wpdb;

/**
 * Class Migrator
 *
 * @package SCCSP\SendCloud\Connected\Shipping\Database
 */
class SCCSP_Migrator {
	/**
	 * WordPress database
	 *
	 * @var wpdb
	 */
	private $db;
	/**
	 * Version
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Migrator constructor.
	 *
	 * @param wpdb $db
	 * @param string $version
	 */
	public function __construct( $db, $version ) {
		$this->db      = $db;
		$this->version = $version;
	}

	/**
	 * Executes migrations that have higher version then the provided version.
	 *
     */
	public function execute() {
		$migration_reader = new SCCSP_Migration_Reader( $this->get_migration_directory(), $this->version, $this->db );
		while ( $migration_reader->has_next() ) {
			$migration = $migration_reader->read_next();
			if ( $migration ) {
				$migration->execute();
			}
		}
	}

	/**
	 * Provides migration directory.
	 *
	 * @return string
	 */
	protected function get_migration_directory() {
		return realpath( __DIR__ ) . '/Migrations/';
	}
}
