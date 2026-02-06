<?php

namespace SCCSP\SendCloud\Connected\Shipping\Controllers\Backend;

use SCCSP\SendCloud\Connected\Shipping\ServicePoint\Shipping\Service_Point_Free_Shipping_Method;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Response;
use WC_Shipping_Zone;
use WC_Shipping_Zones;
use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Shipping_Zone;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class SCCSP_Migration_Controller
 *
 * Handles service point migration-related operations.
 *
 * @package Sendcloud\Shipping\Controllers
 */
class SCCSP_Migration_Controller
{
    /**
     * @var SCCSP_Config_Service
     */
    private $config_service;

    /**
     * @var SCCSP_Shipping_Zone
     */
    private $shipping_zone_utility;

    /**
     * Constructor to initialize the Migration_Controller.
     */
    public function __construct()
    {
        $this->config_service = new SCCSP_Config_Service();
        $this->shipping_zone_utility = new SCCSP_Shipping_Zone();
    }

    public function migrate_service_points()
    {
        try {
            $zones = $this->shipping_zone_utility->get_shipping_zones();

            foreach ($zones as $zone) {
                $this->process_zone_for_migration($zone);
            }

            $rest_of_the_world_zone = WC_Shipping_Zones::get_zone_by('zone_id', 0);
            if ($rest_of_the_world_zone) {
                $this->process_zone_for_migration([
                    'zone_id' => 0,
                    'zone_obj' => $rest_of_the_world_zone,
                ]);
            }

            $this->config_service->set_migration_required(false);
            $this->config_service->set_migration_completed();

            SCCSP_Response::json(['success' => true, 'message' => 'Migration completed successfully!']);
        } catch (\Exception $e) {
            SCCSP_Response::json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Creates a new V2 service point shipping method based on the V1 method settings.
     *
     * @param WC_Shipping_Zone $zone_obj
     * @param object $v1_method
     * @return void
     */
    private function create_v2_shipping_method(WC_Shipping_Zone $zone_obj, $v1_method): void
    {
        $new_method_id = 'service_point_v2_shipping_method';

        $new_method = new Service_Point_Free_Shipping_Method();
        $new_method->instance_settings = [
            'enabled' => 'yes',
            'title' => $v1_method->title . 'V2',
            'tax_status' => $v1_method->tax_status,
            'cost' => $v1_method->cost,
            'free_shipping_enabled' => $v1_method->get_option('free_shipping_enabled'),
            'free_shipping_min_amount' => $v1_method->get_option('free_shipping_min_amount'),
            'free_shipping_requires' => $v1_method->get_option('free_shipping_requires'),
            'carrier_select' => $v1_method->get_option('carrier_select') ? explode(',', $v1_method->get_option('carrier_select')) : []
        ];

        $new_method->instance_id = $zone_obj->add_shipping_method($new_method_id);

        update_option($new_method->get_instance_option_key(), $new_method->instance_settings);

        $this->disable_shipping_methods([$new_method->instance_id]);
    }

    /**
     * Disables specified shipping methods in the WooCommerce database.
     *
     * @param array $instance_ids
     * @return void
     */
    private function disable_shipping_methods(array $instance_ids): void
    {
        global $wpdb;

        foreach ($instance_ids as $instance_id) {
            $wpdb->update(
                "{$wpdb->prefix}woocommerce_shipping_zone_methods",
                ['is_enabled' => 0],
                ['instance_id' => $instance_id]
            );
        }
    }

    /**
     * Deletes a shipping method and its settings from the database.
     *
     * @param int $instance_id The instance ID of the shipping method to delete.
     */

    private function delete_shipping_method(int $instance_id): void
    {
        global $wpdb;

        $wpdb->delete(
            "{$wpdb->prefix}woocommerce_shipping_zone_methods",
            ['instance_id' => $instance_id],
            ['%d']
        );

        delete_option("woocommerce_{$instance_id}_settings");
    }

    /**
     * Processes a single shipping zone for migration.
     *
     * @param array $zone
     * @return void
     */
    private function process_zone_for_migration(array $zone): void
    {
        $zone_id = $zone['zone_id'];
        $zone_obj = new WC_Shipping_Zone($zone_id);
        $shipping_methods = $zone_obj->get_shipping_methods();

        foreach ($shipping_methods as $method) {
            if ($method->id === 'service_point_shipping_method') {
                $this->create_v2_shipping_method($zone_obj, $method);
            }
        }
    }

    public function check_migration_status()
    {
        $migration_required = $this->config_service->is_migration_required();
        $integration_id = $this->config_service->get_integration_id();
        $migration_completed = $this->config_service->is_migration_completed();

        SCCSP_Response::json([
            'show_migration_button' => $migration_required && $integration_id && !$migration_completed
        ]);
    }
}
