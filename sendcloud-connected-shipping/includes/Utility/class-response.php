<?php

namespace SCCSP\SendCloud\Connected\Shipping\Utility;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Response {

    /**
     * Provides json response.
     *
     * @param array $data
     * @param int $status
     *
     * @return false|string
     */
    public static function json( array $data, $status = 200) {
        echo wp_json_encode($data);
        header('Content-Type: application/json');
        status_header($status);

        exit;
    }
}
