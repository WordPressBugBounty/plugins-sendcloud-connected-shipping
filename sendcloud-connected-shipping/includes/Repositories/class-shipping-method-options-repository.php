<?php

namespace SCCSP\SendCloud\Connected\Shipping\Repositories;

use SCCSP\SendCloud\Connected\Shipping\Models\SCCSP_Service_Point_Instance;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Shipping_Method_Options_Repository {

    const SENDCLOUD_SHIPPING_SETTINGS = 'sendcloudshipping_v2_%settings';

	/**
	 * Get all Sendcloud shipping method configurations
	 *
	 * @return array
	 */
    public function get_all_methods_configurations() {
        global $wpdb;

        $configurations = array();
        $result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT option_name, option_value
                  FROM {$wpdb->prefix}options
                  WHERE option_name LIKE %s",
                stripslashes(self::SENDCLOUD_SHIPPING_SETTINGS)
            )
        );
        foreach ( $result as $settings ) {
            $configurations[ $settings['option_name'] ] = unserialize( $settings['option_value'] );
        }

        return $configurations;
    }

	/**
	 * @param $instance_id
	 *
	 * @return SCCSP_Service_Point_Instance
	 */
	public function get_service_point_instance( $instance_id ) {
		$service_point_data = get_option( 'sendcloudshipping_v2_service_point_v2_shipping_method_' . $instance_id . '_settings' );
		if ( $service_point_data ) {
			return SCCSP_Service_Point_Instance::from_array( $service_point_data );
		}

		return SCCSP_Service_Point_Instance::from_array( array() );
	}
}
