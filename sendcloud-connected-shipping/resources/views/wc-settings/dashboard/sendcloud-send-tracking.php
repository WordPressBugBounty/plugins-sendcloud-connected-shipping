<?php

use SCCSP\SendCloud\Connected\Shipping\Utility\SCCSP_View;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Contains data regarding shipping configuration
 *
 * @var array $data
 */
?>

<div class="sc-accordion-item">
    <span class="tag tag-optional"><?php _e('Optional', 'sendcloud-connected-shipping'); ?></span>

<!--            Send tracking notifications from Sendcloud header-->
    <div class="sc-accordion-header">
        <h2>
            <?php
            /* translators: Section heading for sending tracking notifications from Sendcloud */
            esc_html_e('Send tracking notifications from Sendcloud', 'sendcloud-connected-shipping');
            ?>
        </h2>
        <span class="sc-nav-arrow"></span>
        <p>
            <?php
            /* translators: Short description explaining that this provides customers with real-time tracking updates and shipment details */
            esc_html_e('To provide your customers with real-time tracking updates and detailed shipment information.', 'sendcloud-connected-shipping');
            ?>
        </p>
    </div>

<!--            Send tracking notifications from Sendcloud content-->
    <div class="sc-accordion-content">
        <ol class="sc-accordion-list">
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators: %1$s and %2$s wrap 'Sendcloud Tracking' as a clickable link; the text explains that this article helps set up tracking updates */
                    esc_html__('Set up your tracking updates by following this article: %1$sSendcloud Tracking%2$s', 'sendcloud-connected-shipping'),
                    '<a href="' .
                    __('https://support.sendcloud.com/hc/en-us/articles/360024840812-Sendcloud-Tracking', 'sendcloud-connected-shipping')
                    . '" target="_blank">', '</a>'
                );
                ?>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators:
                   %1$s and %2$s wrap 'Settings' in italics;
                   %3$s and %4$s wrap 'Integrations' as a clickable link;
                   %5$s and %6$s wrap 'Configure.' in bold */
                    esc_html__(
                        'In your Sendcloud account, go to %1$sSettings%2$s > %3$sIntegrations%4$s, find your WooCommerce integration and click %5$sConfigure.%6$s',
                        'sendcloud-connected-shipping'
                    ),
                    '<i>', '</i>',
                    '<a href="' . esc_url(
                        sprintf(
                            'https://app.sendcloud.com/v2/settings/integrations/woocommerce_v2/%d',
                            $data['integration_id']
                        )
                    ) . '" target="_blank">', '</a>',
                    '<strong>', '</strong>'
                );
                ?>
                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/confi.png', 'sendcloud-connected-shipping' ); ?>"
                         alt="<?php esc_attr_e( 'Screenshot of Wordpress Integration', 'sendcloud-connected-shipping' ); ?>">
                </div>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators: %1$s and %2$s wrap 'Allow Sendcloud to send tracking updates to customers.' in bold */
                    esc_html__(
                        'Tick the checkbox that says: %1$sAllow Sendcloud to send tracking updates to customers.%2$s',
                        'sendcloud-connected-shipping'
                    ),
                    '<strong>', '</strong>'
                );
                ?>
                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/sendtrackingupdates.png', 'sendcloud-connected-shipping' ); ?>"
                         alt="<?php esc_attr_e( 'Screenshot of Update Tracking', 'sendcloud-connected-shipping' ); ?>">
                </div>
            </li>
        </ol>
    </div>
</div>