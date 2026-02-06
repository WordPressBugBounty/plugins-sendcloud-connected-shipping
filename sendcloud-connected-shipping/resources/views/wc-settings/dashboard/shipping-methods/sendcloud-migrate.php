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

<div class="sc-note-highlighted">
    <?php
    printf(
    /* translators:
     * %1$s and %2$s = <strong>Note:</strong>
     * %3$s = <i>shipping zones</i>
     * %4$s = <i>methods</i>
     * %5$s = <i>rates</i>
     * %6$s = <i>DHL Home delivery</i>
     */
            esc_html__(
                    '%1$sNote:%2$s If you’ve already added %3$s, %4$s, and %5$s in WooCommerce, these remained intact after migrating. However, you might need to set up some or all of your shipping rules again so your WooCommerce shipping methods (the options customers select at checkout) are linked to the real carrier services (for example, %6$s).',
                    'sendcloud-connected-shipping'
            ),
            '<strong>', '</strong>',
            '<i>' . esc_html__( 'shipping zones', 'sendcloud-connected-shipping' ) . '</i>',
            '<i>' . esc_html__( 'methods', 'sendcloud-connected-shipping' ) . '</i>',
            '<i>' . esc_html__( 'rates', 'sendcloud-connected-shipping' ) . '</i>',
            '<i>' . esc_html__( 'DHL Home delivery', 'sendcloud-connected-shipping' ) . '</i>'
    );
    ?>
</div>

<div class="sc-sub-accordion sc-mb-32">
    <div class="sc-accordion-item">
        <div class="sc-sub-accordion-header">
            <h2>
                <?php
                /* translators: Add shipping zones */
                esc_html_e(
                    'Add shipping zones, methods and rates',
                    'sendcloud-connected-shipping'
                );
                ?>
            </h2>
            <span class="sc-nav-arrow"></span>
            <p>
                <?php
                /* translators: Instruction explaining that enabling the feature will send real-time updates from Sendcloud to WooCommerce */
                esc_html_e(
                    'In case you haven’t done so yet or want to add more or change the ones you have.',
                    'sendcloud-connected-shipping'
                );
                ?>
            </p>
        </div>
        <div class="sc-sub-accordion-content">
            <?php echo SCCSP_View::file( '/wc-settings/dashboard/shipping-methods/sendcloud-add-shipping-zones.php' )->render($data); ?>
        </div>
    </div>
</div>