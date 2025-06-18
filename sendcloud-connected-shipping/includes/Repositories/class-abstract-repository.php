<?php

namespace SCCSP\SendCloud\Connected\Shipping\Repositories;

use wpdb;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

abstract class SCCSP_Abstract_Repository
{
    /**
     * WordPress database
     *
     * @var wpdb
     */
    protected $db;

    /**
     * Abstract_Domain_Repository constructor.
     */
    public function __construct() {
	    global $wpdb;
        $this->db = $wpdb;
    }

    /**
     * Deletes all domain entities.
     */
    public function delete_all() {
        $query = 'TRUNCATE ' . $this->get_table_name();
        $this->db->query( $query );
    }

    /**
     * Provides table name.
     *
     * @return string
     */
    abstract protected function get_table_name();
}
