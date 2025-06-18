<?php

namespace SCCSP\SendCloud\Connected\Shipping\Database\Migrations;

use SCCSP\SendCloud\Connected\Shipping\Database\SCCSP_Abstract_Migration;

/**
 * Class Migration_1_0_11
 *
 * @package SCCSP\SendCloud\Connected\Shipping\Database\Migrations
 */
class Migration_1_0_11 extends SCCSP_Abstract_Migration {

    public function execute() {
        add_action( 'plugins_loaded', array( $this, 'update_sendcloud_user_name' ) );
    }

    /**
     * Update sendcloud user_login.
     */
    public function update_sendcloud_user_name() {
        $username = 'sendcloud_api';
        $user = get_user_by( 'login', $username );

        if ($user) {
            $username = $username . '_' . wp_generate_password( 10, false );
            $table_name = $this->db->prefix . 'sendcloud_configs';

            $query = "INSERT INTO ".$table_name." (`key`, `value`) VALUES ('%s', '%s')";
            $query = $this->db->prepare($query, array(
                "USER_NAME",
                $username,
            ));
            $this->db->query($query);

            $this->db->update(
                $this->db->prefix . 'users',
                ['user_login' => $username, 'user_nicename' => $username],
                ['ID' => $user->ID]
            );
        }
    }
}
