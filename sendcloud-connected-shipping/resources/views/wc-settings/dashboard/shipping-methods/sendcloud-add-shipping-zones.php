<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Contains data regarding shipping configuration
 *
 * @var array $data
 */
?>

<ol class="sc-accordion-list">
    <li class="sc-accordion-list-step">
        <?php
        printf(
        /* translators: %1$s = HTML "WooCommerce > Settings >", %2$s = HTML link to Shipping, %3$s = bold "Add shipping zone" */
            esc_html__(
                'From the sidebar menu in your WooCommerce dashboard, go to %1$s %2$s and then click %3$s.',
                'sendcloud-connected-shipping'
            ),
            '<em>' . esc_html__('WooCommerce > Settings >', 'sendcloud-connected-shipping') . '</em>',
            '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=shipping' ) ) . '" target="_blank">'
            . esc_html__( 'Shipping', 'sendcloud-connected-shipping' )
            . '</a>',
            '<strong>' . esc_html__('Add zone', 'sendcloud-connected-shipping') . '</strong>'
        );
        ?>
        <div class="sc-step-image">
            <img class="sc-screenshot-thumb"
                 src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/WCSPEN1.png', 'sendcloud-connected-shipping' ); ?>"
                 alt="<?php esc_attr_e( 'Screenshot of WooCommerce Shipping Settings', 'sendcloud-connected-shipping' ); ?>">
        </div>
        <hr/>
    </li>

    <li class="sc-accordion-list-step">
        <?php
        /* translators: Instruction to give shipping zone a name, select regions, and add shipping methods */
        esc_html_e(
            'Give your shipping zone a name, select the regions you want to include, and add your shipping methods.',
            'sendcloud-connected-shipping'
        );
        ?>
        <hr/>
    </li>

    <li class="sc-accordion-list-step">
        <?php
        /* translators: Instruction on pricing methods */
        esc_html_e(
            "When you add a shipping method, you'll have the option to decide from the following pricing methods",
            'sendcloud-connected-shipping'
        );
        ?>:
        <ul class="sc-mt-16">
            <li>
                <strong><?php esc_html_e('Fixed rate', 'sendcloud-connected-shipping'); ?></strong>:
                <?php esc_html_e('Set a unique delivery fee', 'sendcloud-connected-shipping'); ?>
            </li>
            <li>
                <strong><?php esc_html_e('Free delivery', 'sendcloud-connected-shipping'); ?></strong>:
                <?php esc_html_e('Grant a free delivery to your customers', 'sendcloud-connected-shipping'); ?>
            </li>
            <li>
                <strong><?php esc_html_e('Local pickup', 'sendcloud-connected-shipping'); ?></strong>:
                <?php esc_html_e('Customers will pick up the order themselves', 'sendcloud-connected-shipping'); ?>
            </li>
        </ul>
        <hr/>
    </li>
    <li class="sc-accordion-list-step">
        <?php
        /* translators: Instruction after choosing a pricing method; %s is "set up" in bold */
        printf(
            esc_html__(
                'Once the shipping method is added, click on %s to change its name and add rates:',
                'sendcloud-connected-shipping'
            ),
            '<strong>' . esc_html__('Edit', 'sendcloud-connected-shipping') . '</strong>'
        );
        ?>
        <div class="sc-step-image">
            <img class="sc-screenshot-thumb"
                 src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/setup_shippingmethod.png', 'sendcloud-connected-shipping' ); ?>"
                 alt="<?php esc_attr_e( 'Screenshot of WooCommerce Shipping Settings', 'sendcloud-connected-shipping' ); ?>">
        </div>
        <hr/>
    </li>
    <li class="sc-accordion-list-step">
        <?php
        printf(
        /* translators: Instruction linking to WooCommerce documentation about shipping classes */
            esc_html__('For more information about adding rates and shipping classes in WooCommerce, see the following article: %s', 'sendcloud-connected-shipping'),
            '<a href="https://woocommerce.com/document/product-shipping-classes/" target="_blank">'
            . esc_html__('Product Shipping Classes - WooCommerce.', 'sendcloud-connected-shipping')
            . '</a>'
        );
        ?>
    </li>
</ol>