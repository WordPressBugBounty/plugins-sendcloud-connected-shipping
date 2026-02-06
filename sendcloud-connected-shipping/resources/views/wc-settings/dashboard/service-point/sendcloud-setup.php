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
<div class="sc-accordion-item">
    <span class="tag tag-optional"><?php _e('Optional', 'sendcloud-connected-shipping'); ?></span>

<!--    Set up Service Point delivery header-->
    <div class="sc-accordion-header">
        <h2>
            <span class="sc-nav-arrow"></span>
            <?php
            /* translators: Heading for the setup step where a merchant configures service point delivery */
            esc_html_e( 'Set up Service Point delivery', 'sendcloud-connected-shipping' );
            ?>
        </h2>
        <p>
            <?php
            /* translators: Description text explaining why to configure service point delivery */
            esc_html_e(
                'To offer your customers the option to pick up their parcels from a local service point.',
                'sendcloud-connected-shipping'
            );
            ?>
        </p>
    </div>

<!--    Set up Service Point delivery content-->
    <div class="sc-accordion-content">
        <div class="sc-content-title">
            <?php
            /* translators: Step A: Activating Service Point  */
            esc_html_e(
                    'Step A: Activating Service Point delivery in Sendcloud',
                    'sendcloud-connected-shipping'
            );
            ?>
        </div>

        <ol class="sc-accordion-list">
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators:
                   %1$s and %2$s wrap 'Settings' in italics;
                   %3$s and %4$s wrap 'Integrations' as a clickable link;
                   %5$s and %6$s wrap 'Configure' in bold */
                        esc_html__(
                                'In your Sendcloud account, go to %1$sSettings%2$s > %3$sIntegrations%4$s, find your WooCommerce integration and click %5$sConfigure%6$s.',
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
                /* translators:
                   %1$s and %2$s wrap 'Service Point delivery options' in bold;
                   %3$s and %4$s wrap 'carriers' in bold;
                   the rest explains enabling the switch and selecting carriers for this delivery type */
                        esc_html__(
                                'Activate the switch to provide %1$sService Point delivery options%2$s and tick the %3$scarriers%4$s you want to use for this type of delivery.',
                                'sendcloud-connected-shipping'
                        ),
                        '<strong>', '</strong>',
                        '<strong>', '</strong>'
                );
                ?>
                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/EN%20service%20points%20WC%20in%20panel.png', 'sendcloud-connected-shipping' ); ?>"
                         alt="<?php esc_attr_e( 'Screenshot of Service Points', 'sendcloud-connected-shipping' ); ?>">
                </div>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators: %1$s and %2$s wrap 'Save' button text in bold */
                        esc_html__(
                                'Click %1$sSave.%2$s',
                                'sendcloud-connected-shipping'
                        ),
                        '<strong>', '</strong>'
                );
                ?>
            </li>
        </ol>

        <div class="sc-sub-accordion sc-mb-32">
            <div class="sc-accordion-item">
                <div class="sc-sub-accordion-header">
                    <h2>
                        <?php
                        /* translators: Instruction or label to enable checking dimensions */
                        esc_html_e(
                                'Enable checking dimensions',
                                'sendcloud-connected-shipping'
                        );
                        ?>
                    </h2>
                    <span class="sc-nav-arrow"></span>
                    <p>
                        <?php
                        /* translators: Explains that enabling the feature helps prevent parcel rejection due to size restrictions. Applies only to PostNL and DHL. */
                        esc_html_e(
                                'To prevent parcel rejection due to size. Only for PostNL and DHL.',
                                'sendcloud-connected-shipping'
                        );
                        ?>
                    </p>
                </div>
                <div class="sc-sub-accordion-content">
                    <ol class="sc-accordion-list">
                        <li class="sc-accordion-list-step">
                            <?php
                            printf(
                            /* translators: %1$s = italic "Settings", %2$s = link "Integrations", %3$s = bold "WooCommerce", %4$s = bold "Service point check dimensions", %5$s = bold "only", %6$s = bold "DHL", %7$s = bold "PostNL" */
                                    esc_html__(
                                            'Also in %1$s > %2$s > %3$s, tick the box as shown below: %4$s. We recommend %5$s selecting this box if you are shipping with %6$s and/or %7$s.',
                                            'sendcloud-connected-shipping'
                                    ),
                                    '<i>' . esc_html__( 'Settings', 'sendcloud-connected-shipping' ) . '</i>',
                                    '<a href="' . esc_url( sprintf(
                                            'https://app.sendcloud.com/v2/settings/integrations/woocommerce_v2/%d',
                                            $data['integration_id']
                                    ) ) . '" target="_blank">' . esc_html__( 'Integrations', 'sendcloud-connected-shipping' ) . '</a>',
                                    '<strong>' . esc_html__( 'WooCommerce', 'sendcloud-connected-shipping' ) . '</strong>',
                                    '<strong>"' . esc_html__( 'Service point check dimensions', 'sendcloud-connected-shipping' ) . '"</strong>',
                                    '<strong>' . esc_html__( 'only', 'sendcloud-connected-shipping' ) . '</strong>',
                                    '<strong>' . esc_html__( 'DHL', 'sendcloud-connected-shipping' ) . '</strong>',
                                    '<strong>' . esc_html__( 'PostNL', 'sendcloud-connected-shipping' ) . '</strong>'
                            );
                            ?>
                            <div class="sc-note-highlighted">
                                <?php  _e(
                                        '<strong>Note:</strong> If multiple carriers are activated while the dimension checker is enabled, you’ll see this error: <i>"One of the items in your order is too large to be shipped to a service point. Search is limited to your selected country."</i> This is why we recommend using dimension checking only for PostNL and DHL service points.'
                                        , 'sendcloud-connected-shipping' );
                                ?>
                            </div>
                            <div class="sc-step-image">
                                <img class="sc-screenshot-thumb"
                                     src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/EN%20parcel%20dimensions%20check.png', 'sendcloud-connected-shipping' ); ?>"
                                     alt="<?php esc_attr_e( 'Screenshot of Service Point Dimensions', 'sendcloud-connected-shipping' ); ?>">
                            </div>
                            <hr/>
                        </li>
                        <li class="sc-accordion-list-step">
                            <?php _e('This feature is dependent on you having entered the correct dimensions for all of your products in your WooCommerce admin environment. The example below shows how to set up maximum dimensions in your WooCommerce product settings:',
                                    'sendcloud-connected-shipping' ); ?>
                            <div class="sc-step-image">
                                <img class="sc-screenshot-thumb"
                                     src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/WCSPEN5.png', 'sendcloud-connected-shipping' ); ?>"
                                     alt="<?php esc_attr_e( 'Screenshot of Product Dimensions', 'sendcloud-connected-shipping' ); ?>">
                            </div>
                            <?php _e('The service point picker will filter out carriers that cannot handle products of that given size.',
                                    'sendcloud-connected-shipping' ); ?>
                        </li>
                    </ol>
                    <hr/>
                    <?php _e('Please bear in mind that there is no advance packing logic behind this — we simply check if all the given products in an order are within the maximum dimensions of the carrier.',
                            'sendcloud-connected-shipping' ); ?>
                </div>
            </div>
        </div>

        <div class="sc-content-title">
            <?php
            /* translators: Step B: Configuring Service Point in WooCommerce  */
            esc_html_e(
                    'Step B: Configuring Service Point delivery in WooCommerce',
                    'sendcloud-connected-shipping'
            );
            ?>
        </div>

        <ol class="sc-accordion-list">
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators: %1$s = WooCommerce > Settings > (emphasized), %2$s = Shipping link, %3$s = Add zone in bold */
                    esc_html__('In your WooCommerce admin panel, navigate to %1$s %2$s and %3$s.', 'sendcloud-connected-shipping'),
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
                printf(
                /* translators: %s is the "Add shipping method" text in bold */
                    esc_html__('Click %s.', 'sendcloud-connected-shipping'),
                    '<strong>' . esc_html__('Add shipping method', 'sendcloud-connected-shipping') . '</strong>'
                );
                ?>

                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/WCSPEN2.png', 'sendcloud-connected-shipping' ); ?>"
                         alt="<?php esc_attr_e( 'Screenshot of WooCommerce Shipping Zone', 'sendcloud-connected-shipping' ); ?>">
                </div>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators: %1$s = "Service Point Delivery" in bold, %2$s = "[Sendcloud]" in bold, %3$s = "Continue" in bold */
                    esc_html__(
                        'Select the %1$s method that explicitly includes %2$s in its description. Then, click %3$s.',
                        'sendcloud-connected-shipping'
                    ),
                    '<strong>' . esc_html__('"Service Point Delivery"', 'sendcloud-connected-shipping') . '</strong>',
                    '<strong>' . esc_html__('"[Sendcloud]"', 'sendcloud-connected-shipping') . '</strong>',
                    '<strong>' . esc_html__('Continue', 'sendcloud-connected-shipping') . '</strong>'
                );
                ?>
                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/servicepoint_delivery[Sendcloud].png', 'sendcloud-connected-shipping' ); ?>"
                         alt="<?php esc_attr_e( 'Screenshot of Service Point Delivery Method', 'sendcloud-connected-shipping' ); ?>">                </div>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators: %1$s = "method name" in bold, %2$s = example method in italic */
                    esc_html__(
                        'Enter a %1$s for your shipping method to be displayed on your checkout page (such as %2$s) and add a shipping cost.',
                        'sendcloud-connected-shipping'
                    ),
                    '<strong>' . esc_html__('method name', 'sendcloud-connected-shipping') . '</strong>',
                    '<i>' . esc_html__('"Delivery to service point"', 'sendcloud-connected-shipping') . '</i>'
                );
                ?>

                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators: Shown in the settings description for the Carrier Selection field */
                    esc_html__(
                        'In the %1$s field, you’ll see the carriers you activated in Sendcloud for this type of
                         delivery. When you’re done, click %2$s.',
                        'sendcloud-connected-shipping'
                    ),
            '<strong>' . esc_html__('Carrier Selection', 'sendcloud-connected-shipping') . '</strong>',
                    '<strong>' . esc_html__('Create and save', 'sendcloud-connected-shipping') . '</strong>'
                );
                ?>
                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/setupservicepointdelivery.png', 'sendcloud-connected-shipping' ); ?>"
                         alt="<?php esc_attr_e( 'Screenshot of Service Point Delivery Method', 'sendcloud-connected-shipping' ); ?>">
                </div>
            </li>
        </ol>
    </div>
</div>
