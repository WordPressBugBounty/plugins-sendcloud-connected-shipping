<?php

namespace SCCSP\SendCloud\Connected\Shipping\Database;

use SCCSP\SendCloud\Connected\Shipping\Database\Exceptions\SCCSP_Migration_Exception;
use wpdb;

abstract class SCCSP_Abstract_Migration {
	/**
	 * WordPress database
	 *
	 * @var wpdb
	 */
	protected $db;

	/**
	 * Abstract_Migration constructor.
	 *
	 * @param wpdb $db
	 */
	public function __construct( $db ) {
		$this->db = $db;
	}

	/**
	 * Executes migration.
	 *
	 * @throws SCCSP_Migration_Exception
	 */
	abstract public function execute();
}
