<?php

namespace SCCSP\SendCloud\Connected\Shipping\Services;

use Exception;
use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_Logger;
use WC_Webhook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class Webhook Service.
 */
class SCCSP_Webhook_Service
{
    /**
     * Removes Sendcloud webhooks from the database.
     *
     * @return void
     */
    public function remove_woocommerce_webhooks(): void
    {
        $webhook_ids = $this->get_webhook_ids();

        if ( ! empty( $webhook_ids ) ) {
            foreach ( $webhook_ids as $webhook_id ) {
                try{
                    $webhook = new WC_Webhook( $webhook_id );
                    $webhook->delete( true ); // true = force delete
                } catch ( Exception $e ) {
                    SCCSP_Logger::info("Webhook with ID {$webhook_id} cannot be removed.");
                }
            }
        } else {
            SCCSP_Logger::info('No Sendcloud webhooks found.');
        }
    }

    /**
     * Retrieve webhook IDs from the database which name contains Sendcloud
     *
     * @return array
     */
    private function get_webhook_ids(): array
    {
        global $wpdb;
        return $wpdb->get_col(
            "SELECT webhook_id FROM {$wpdb->prefix}wc_webhooks WHERE name LIKE '%Sendcloud%'"
        );
    }
}
