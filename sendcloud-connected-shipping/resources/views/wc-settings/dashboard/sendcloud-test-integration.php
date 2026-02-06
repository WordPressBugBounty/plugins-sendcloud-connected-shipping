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
    <?php if( $data['migration_required'] ) : ?>
        <span class="tag tag-necessary"><?php _e('Necessary', 'sendcloud-connected-shipping'); ?></span>
    <?php else : ?>
        <span class="tag tag-recommended"><?php _e('Recommended', 'sendcloud-connected-shipping'); ?></span>
    <?php endif; ?>

<!--        Test the integration header-->
    <div class="sc-accordion-header">
        <h2>
            <?php
            /* translators: Step for testing the Sendcloud integration */
            esc_html_e( 'Test the integration', 'sendcloud-connected-shipping' );
            ?>
        </h2>
        <span class="sc-nav-arrow"></span>
        <p>
            <?php
            /* translators: Description for testing the Sendcloud integration */
            esc_html_e(
                'To confirm that Sendcloud and your webshop are syncing properly.',
                'sendcloud-connected-shipping'
            );
            ?>
        </p>
    </div>

<!--        Test the integration content-->
    <div class="sc-accordion-content">
        <ol class="sc-accordion-list">
            <li class="sc-accordion-list-step">
                <?php
                /* translators: PLace a test order  */
                esc_html_e(
                        'Place a test order in your webshop.',
                        'sendcloud-connected-shipping'
                );
                ?>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators:
                %1$s and %2$s = <a href="link-to-orders">Orders</a>
                %3$s and %4$s = <strong>Unstamped Letter</strong>
                */
                        esc_html__(
                                'In your Sendcloud account, go to Shipping > %1$sOrders%2$s. Find the test order, select %3$sUnstamped Letter%4$s as the shipping method (to avoid charges), and create the label.',
                                'sendcloud-connected-shipping'
                        ),
                        '<a href="' . esc_url( admin_url( 'admin.php?page=wc-orders' ) ) . '" target="_blank">',
                        '</a>',
                        '<strong>',
                        '</strong>'
                );
                ?>
                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php _e('https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/unstampedletter.png', 'sendcloud-connected-shipping'); ?>"
                         alt="<?php esc_attr_e( 'Screenshot of WooCommerce Shipping Settings', 'sendcloud-connected-shipping' ); ?>">
                </div>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators:
                %1$s and %2$s = <i>WooCommerce</i>
                %3$s and %4$s = <a href="link-to-orders">Orders</a>
                %5$s and %6$s = <strong>Completed</strong>
                */
                        esc_html__(
                                'Verify that the order status in WooCommerce (%1$sWooCommerce%2$s > %3$sOrders%4$s) has updated to %5$sCompleted%6$s.',
                                'sendcloud-connected-shipping'
                        ),
                        '<i>', '</i>',
                        '<a href="' . esc_url( admin_url( 'edit.php?post_type=shop_order' ) ) . '" target="_blank">', '</a>',
                        '<strong>', '</strong>'
                );
                ?>
                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php echo _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/wccompletedstatus.png', 'sendcloud-connected-shipping' ); ?>"
                         alt="<?php esc_attr_e( 'Screenshot of WooCommerce Shipping Settings', 'sendcloud-connected-shipping' ); ?>">
                </div>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators:
                %1$s and %2$s = <strong>'Order notes'</strong>
                */
                        esc_html__(
                                'Also in WooCommerce’s Orders section, open the order and check the %1$s‘Order notes’%2$s panel on the right. Tracking details should appear here.',
                                'sendcloud-connected-shipping'
                        ),
                        '<strong>', '</strong>'
                );
                ?>
                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php echo _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/wcordernotes.png', 'sendcloud-connected-shipping' ); ?>"
                         alt="<?php esc_attr_e( 'Screenshot of WooCommerce Shipping Settings', 'sendcloud-connected-shipping' ); ?>">
                </div>
            </li>
        </ol>

        <?php if ( $data['migration_required'] ) : ?>
            <div class="sc-content-title">
                <?php
                /* translators: If the order status updated  */
                esc_html_e(
                        'If the order status updated correctly and tracking details appear',
                        'sendcloud-connected-shipping'
                )
                ?>
            </div>

            <span>
                <?php
                /* translators: You can now disconnect and delete the old integration  */
                esc_html_e(
                        'You can now disconnect and delete the old integration. Remember to remove it from both
                         Sendcloud and WooCommerce. Here’s how:',
                        'sendcloud-connected-shipping'
                    )
                ?>
            </span>

            <ol class="sc-accordion-list">
                <li class="sc-accordion-list-step">
                    <div>
                        <strong>
                            <?php
                            /* translators: Disconnect WooCommerce  */
                            esc_html_e(
                                    'Disconnect WooCommerce V1 in Sendcloud:',
                                    'sendcloud-connected-shipping'
                            )
                            ?>
                        </strong>
                    </div>
                    <br/>
                    <?php
                    printf(
                    /* translators:
                     * %1$s and %2$s = <i>Settings</i>
                     * %3$s and %4$s = <a>Integrations</a> link
                     * %5$s and %6$s = <strong>Disconnect</strong>
                     */
                            esc_html__(
                                    'In your Sendcloud account, go to %1$sSettings%2$s > %3$sIntegrations%4$s, 
                                    find your old WooCommerce integration (the one without the “V2”) and click %5$sDisconnect%6$s.',
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
                             src="<?php echo _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/disconnectV1.png', 'sendcloud-connected-shipping' ); ?>"
                             alt="<?php esc_attr_e( 'Screenshot of WooCommerce Shipping Settings', 'sendcloud-connected-shipping' ); ?>">
                    </div>
                    <hr/>
                </li>
                <li class="sc-accordion-list-step">
                    <div>
                        <strong>
                            <?php
                            /* translators: Delete the old Sendcloud plugin  */
                            esc_html_e(
                                    'Delete the old Sendcloud plugin in WooCommerce:',
                                    'sendcloud-connected-shipping'
                            )
                            ?>
                        </strong>
                    </div>
                    <br/>
                    <?php
                    printf(
                    /* translators:
                     * %1$s and %2$s = <i>Plugins</i>
                     * %3$s and %4$s = <a><i>Installed Plugins</i></a> link
                     * %5$s and %6$s = <a><i>“Sendcloud | Smart Shipping Service”</i></a> plugin name (quotes included)
                     * %7$s and %8$s = <strong>Deactivate</strong>
                     * %9$s and %10$s = <strong>Delete</strong>
                     */
                            esc_html__(
                                    'In your WooCommerce admin panel, go to %1$sPlugins%2$s > %3$sInstalled Plugins%4$s, 
                                    find the %5$s“Sendcloud | Smart Shipping Service”%6$s plugin, click %7$sDeactivate%8$s and then %9$sDelete%10$s.',
                                    'sendcloud-connected-shipping'
                            ),
                            '<i>', '</i>',
                            '<a href="' . esc_url( admin_url( 'plugins.php' ) ) . '" target="_blank"><i>', '</i></a>',
                            '<i>', '</i>',
                            '<strong>', '</strong>',
                            '<strong>', '</strong>'
                    );
                    ?>

                    <div class="sc-step-image">
                        <img class="sc-screenshot-thumb"
                             src="<?php echo _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/wcdeactivateanddelete.png', 'sendcloud-connected-shipping' ); ?>"
                             alt="<?php esc_attr_e( 'Screenshot of WooCommerce Shipping Settings', 'sendcloud-connected-shipping' ); ?>">
                    </div>
                </li>
            </ol>
        <?php endif; ?>

        <div class="sc-content-title">
            <?php
            /* translators: If the order status didn’t update  */
            esc_html_e(
                    'If the order status didn’t update and tracking details do not appear',
                    'sendcloud-connected-shipping'
            );
            ?>
        </div>

        <ol class="sc-accordion-list">
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators:
                %1$s and %2$s = <i>Settings</i>
                %3$s and %4$s = <a href="link-to-integrations">Integrations</a>
                %5$s and %6$s = <strong>WooCommerce V2</strong>
                %7$s and %8$s = <strong>‘Change the parcels’ status to “sent” once the label is created’</strong>
                */
                        esc_html__(
                                'In your Sendcloud account, in %1$sSettings%2$s > %3$sIntegrations%4$s > %5$sWooCommerce V2%6$s, make sure the ‘Feedback to the webshop’ dropdown is set to: %7$s‘Change the parcels’ status to “sent” once the label is created’%8$s.',
                                'sendcloud-connected-shipping'
                        ),
                        '<i>', '</i>',
                        '<a href="' . esc_url(
                                sprintf(
                                        'https://app.sendcloud.com/v2/settings/integrations/woocommerce_v2/%d',
                                        $data['integration_id']
                                )
                        ) . '" target="_blank">', '</a>',
                        '<strong>', '</strong>',
                        '<strong>', '</strong>'
                );
                ?>

                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php echo _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/configurationfeedbacktowebshop.png', 'sendcloud-connected-shipping' ); ?>"
                         alt="<?php esc_attr_e( 'Screenshot of WooCommerce Shipping Settings', 'sendcloud-connected-shipping' ); ?>">
                </div>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators:
                %1$s and %2$s = <a href="link-to-users">Users</a>
                %3$s and %4$s = <strong>Shop manager</strong>
                %5$s and %6$s = <strong>Read/Write permissions</strong>
                */
                        esc_html__(
                                'In your WooCommerce admin panel, in %1$sUsers%2$s, check that the Sendcloud user role is set to %3$sShop manager%4$s. Also make sure your Sendcloud API keys have %5$sRead/Write permissions%6$s.',
                                'sendcloud-connected-shipping'
                        ),
                        '<a href="' . esc_url( admin_url( 'users.php' ) ) . '" target="_blank">', '</a>',
                        '<strong>', '</strong>',
                        '<strong>', '</strong>'
                );
                ?>
                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php echo _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/wcshopmanagerrole.png', 'sendcloud-connected-shipping' );; ?>"
                         alt="<?php esc_attr_e( 'Screenshot of WooCommerce Shipping Settings', 'sendcloud-connected-shipping' ); ?>">
                </div>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators:
                %1$s and %2$s = <a href="link-to-users">Users</a>
                %3$s and %4$s = <strong>Shop manager</strong>
                %5$s and %6$s = <strong>Read/Write permissions</strong>
                */
                        esc_html__(
                                'Check whether your server configuration allows %1$sPUT%2$s requests for the REST API. Sendcloud updates orders using %3$sPUT%4$s.',
                                'sendcloud-connected-shipping'
                        ),
                        '<strong>', '</strong>',
                        '<i>', '</i>'
                );
                ?>

                <ol>
                    <li>
                        <?php
                        printf(
                        /* translators: Instruction to open the .htaccess file */
                                esc_html__(
                                        'Open your %1$s.htaccess%2$s file (located in your %3$spublic_html%4$s folder).',
                                        'sendcloud-connected-shipping'
                                ),
                                '<strong>', '</strong>',
                                '<i>', '</i>'
                        );
                        ?>
                    </li>
                    <li>
                        <?php
                        /* translators: Instruction to look for a block in .htaccess */
                        esc_html_e(
                                'Look for a block similar to:',
                                'sendcloud-connected-shipping'
                        );
                        ?>
                        <br/>
                        <strong>
                            <i>
                                &lt;LimitExcept GET POST HEAD&gt; <br/>
                                Deny from all <br/>
                                &lt;/LimitExcept&gt;
                            </i>
                        </strong>
                    </li>
                    <li>
                        <?php
                        printf(
                        /* translators: Ensure PUT is included */
                                esc_html__(
                                        'Ensure that %1$s‘PUT’%2$s is included:',
                                        'sendcloud-connected-shipping'
                                ),
                                '<strong>', '</strong>'
                        );
                        ?>
                        <br/>
                        <strong>
                            <i>
                                &lt;LimitExcept GET POST HEAD PUT&gt; <br/>
                                Deny from all <br/>
                                &lt;/LimitExcept&gt;
                            </i>
                        </strong>
                    </li>
                </ol>
            </li>
        </ol>
    </div>
</div>
