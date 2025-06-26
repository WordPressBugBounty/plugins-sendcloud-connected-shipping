<?php

namespace SCCSP\SendCloud\Connected\Shipping\Repositories;

use SCCSP\SendCloud\Connected\Shipping\Models\SCCSP_Service_Point_Meta;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Order_Repository {
	const SERVICE_POINT_META_FIELD_NAME = 'sendcloudshipping_v2_service_point';

	/**
	 * Get service point based on order id
	 *
	 * @param $order_id
	 *
	 * @return SCCSP_Service_Point_Meta|null
	 */
	public function get_service_point_meta( $order_id ) {
		$order = wc_get_order( $order_id );

        if ( ! $order ) {
            return null;
        }

		$data  = $order->get_meta( self::SERVICE_POINT_META_FIELD_NAME );
		if ( ! $data ) {
			return null;
		}

		return SCCSP_Service_Point_Meta::from_array( json_decode( $data, true ) );
	}

	/**
	 * Update service point
	 *
	 * @param $order_id
	 * @param  string  $service_point
	 *
	 */
	public function save_service_point_meta( $order_id, $service_point ) {
		$order = wc_get_order( $order_id );

        if ( ! $order ) {
            return;
        }

		$order->update_meta_data( self::SERVICE_POINT_META_FIELD_NAME, $service_point );
		$order->save();
	}
}
