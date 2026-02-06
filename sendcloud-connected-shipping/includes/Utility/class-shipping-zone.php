<?php

namespace SCCSP\SendCloud\Connected\Shipping\Utility;

use WC_Shipping_Zones;
use WC_Shipping_Zone;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Shipping_Zone {

    /**
     * Retrieves all WooCommerce shipping zones.
     *
     * @return array
     */
    public function get_shipping_zones(): array {
        return WC_Shipping_Zones::get_zones();
    }
}