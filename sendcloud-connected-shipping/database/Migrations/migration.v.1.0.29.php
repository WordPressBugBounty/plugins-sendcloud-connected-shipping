<?php

namespace SCCSP\SendCloud\Connected\Shipping\Database\Migrations;

use SCCSP\SendCloud\Connected\Shipping\Database\SCCSP_Abstract_Migration;

/**
 * Class Migration_1_0_29
 *
 * Backfills the correct email address on the SendCloud API user
 * for installations where the user was created before the email
 * was set explicitly.
 *
 * @package SCCSP\SendCloud\Connected\Shipping\Database\Migrations
 */
class Migration_1_0_29 extends SCCSP_Abstract_Migration {

    const WP_USER_EMAIL = 'noreply-plugin@sendcloud.com';

    public function execute() {
        $this->fix_sendcloud_user_email();
    }

    /**
     * Finds the SendCloud API WordPress user (by the username stored in
     * sendcloud_configs) and ensures their email is set to the canonical
     * plugin address.
     */
    private function fix_sendcloud_user_email() {
        global $wpdb;

        $user_name = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT `value`
             FROM {$wpdb->prefix}sendcloud_configs
             WHERE `key` = %s
             LIMIT 1",
                'USER_NAME'
            )
        );

        if ( empty( $user_name ) ) {
            return;
        }

        $user = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT ID, user_email
             FROM {$wpdb->users}
             WHERE user_login = %s
             LIMIT 1",
                $user_name
            )
        );

        if ( ! $user ) {
            return;
        }

        if ( $user->user_email === self::WP_USER_EMAIL ) {
            return;
        }

        $updated = $wpdb->update(
            $wpdb->users,
            array( 'user_email' => self::WP_USER_EMAIL ),
            array( 'ID'         => $user->ID ),
            array( '%s' ),
            array( '%d' )
        );

        if ( false === $updated ) {
            error_log(
                '[SendCloud] Migration_1_0_29: could not update email for user '
                . esc_html( $user_name ) . ' — '
                . $wpdb->last_error
            );
        }
    }
}