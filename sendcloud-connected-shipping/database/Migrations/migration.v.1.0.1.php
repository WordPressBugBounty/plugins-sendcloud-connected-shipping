<?php

namespace SCCSP\SendCloud\Connected\Shipping\Database\Migrations;

use SCCSP\SendCloud\Connected\Shipping\Database\SCCSP_Abstract_Migration;

/**
 * Class Migration_1_0_1
 *
 * @package SCCSP\SendCloud\Connected\Shipping\Database\Migrations
 */
class Migration_1_0_1 extends SCCSP_Abstract_Migration {

	public function execute() {
		add_action( 'plugins_loaded', array( $this, 'change_permission_for_sc_api_user' ) );
	}

	/**
	 * Creates delivery zone table.
	 */
	public function change_permission_for_sc_api_user() {
		$username = 'sendcloud_api';
		$user     = get_user_by( 'login', $username );
		if ( $user ) {
			$user->set_role( 'shop_manager' );
		}
	}
}
