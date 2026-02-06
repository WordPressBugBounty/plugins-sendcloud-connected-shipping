<?php

namespace SCCSP\SendCloud\Connected\Shipping\Repositories;

use Automattic\WooCommerce\Internal\DataStores\Orders\OrdersTableDataStore;
use Automattic\WooCommerce\Enums\OrderStatus;
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

    /**
     * Remove service point
     *
     * @param int $order_id
     */
    public function delete_service_point_meta( $order_id ) {
        $order = wc_get_order( $order_id );

        if ( ! $order ) {
            return;
        }

        $order->delete_meta_data( self::SERVICE_POINT_META_FIELD_NAME );
        $order->save();
    }

    /**
     * @param int $product_id
     *
     * @return array|null
     * @throws \Exception
     */
    public function get_orders_by_product_id( $product_id ) {
        global $wpdb;
        $product_table = $wpdb->prefix . 'wc_order_product_lookup';
        $order_table = $wpdb->prefix . 'wc_orders';

        $sync_statuses = [
            "wc-" . OrderStatus::COMPLETED,
            "wc-" . OrderStatus::CANCELLED,
            "wc-" . OrderStatus::REFUNDED,
            "wc-" . OrderStatus::FAILED,
            "wc-" . OrderStatus::TRASH
        ];

        $sql = "
            SELECT p.order_id
                FROM %1s p
                LEFT JOIN %1s o ON p.order_id = o.id
                WHERE p.product_id = %d 
                  AND o.status NOT IN(".implode(', ', array_fill(0, count($sync_statuses), '%s')).")
                  AND o.date_created_gmt > NOW() - INTERVAL 30 DAY
        ";

        $query = call_user_func_array(
            array($wpdb, 'prepare'),
            array_merge(array($sql, $product_table, $order_table, $product_id), $sync_statuses)
        );
        $result = $wpdb->get_results($query, ARRAY_A);

        return $result ? array_column($result, 'order_id') : array();
    }

    /**
     * @param $order_ids
     * @return void
     */
    public function set_orders_updated_by_id( $order_ids )
    {
        global $wpdb;

        foreach ($order_ids as $order_id) {
            $wpdb->update(
                $wpdb->prefix . 'wc_orders',
                array(
                    'date_updated_gmt' => current_time( 'mysql', true ),
                ),
                array(
                    'id' => $order_id,
                ),
                array(
                    '%s',
                ),
                array(
                    '%d',
                )
            );
        }

    }
}
