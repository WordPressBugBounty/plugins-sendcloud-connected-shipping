<?php

namespace SCCSP\SendCloud\Connected\Shipping\Repositories;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Config_Repository extends SCCSP_Abstract_Repository
{
    public function get($key)
    {
        $result = $this->get_records($key);

        if ($result) {
            return $result[0]['value'];
        }

        return null;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function save($key, $value)
    {
        $data = $this->get_records($key);
        if ($data) {
            $query = 'UPDATE '.$this->get_table_name().' SET `value`=%s WHERE (`key`=%s)';
            $query = $this->db->prepare($query, array(
                $value,
                $key,
            ));
        } else {
            $query = "INSERT INTO ".$this->get_table_name()." (`key`, `value`) VALUES ('%s', '%s')";
            $query = $this->db->prepare($query, array(
                $key,
                $value,
            ));
        }

        $this->db->query($query);
    }

	/**
	 * @param $key
	 *
	 * @return void
	 */
	public function delete( $key ) {
		$query = 'DELETE FROM ' . $this->get_table_name() . ' WHERE `key`=%s';
		$query = $this->db->prepare( $query, array(
			$key,
		) );
		$this->db->query( $query );
	}

    /**
     * @return string
     */
    protected function get_table_name()
    {
        return $this->db->prefix.'sendcloud_configs';
    }

	private function get_records($key) {
		$query = "SELECT *
				   FROM ".$this->get_table_name()." 
				   WHERE `key`='{$key}'";

		return $this->db->get_results($query, ARRAY_A);
	}
}
