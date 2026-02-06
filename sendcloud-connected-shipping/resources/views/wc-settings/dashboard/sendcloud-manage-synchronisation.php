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

<!--            Manage synchronisation settings between Sendcloud and WooCommerce header-->
    <div class="sc-accordion-header">
        <h2>
            <?php
            /* translators: Section heading for managing synchronisation settings between Sendcloud and WooCommerce */
            esc_html_e(
                'Manage synchronisation settings between Sendcloud and WooCommerce',
                'sendcloud-connected-shipping'
            );
            ?>
        </h2>
        <span class="sc-nav-arrow"></span>
        <p>
            <?php
            /* translators: Instruction line explaining adjustment of data flow between webshop and Sendcloud */
            esc_html_e(
                'To adjust how data flows between your webshop and Sendcloud.',
                'sendcloud-connected-shipping'
            );
            ?>
        </p>
    </div>

<!--            Manage synchronisation settings between Sendcloud and WooCommerce content-->
    <div class="sc-accordion-content">
        <div class="sc-sub-accordion">
            <div class="sc-accordion-item">
                <div class="sc-sub-accordion-header">
                    <h2>
                        <?php
                        /* translators: Instruction or label to enable webhook feedback */
                        esc_html_e(
                            'Enable webhook feedback',
                            'sendcloud-connected-shipping'
                        );
                        ?>
                    </h2>
                    <span class="sc-nav-arrow"></span>
                    <p>
                        <?php
                        /* translators: Instruction explaining that enabling the feature will send real-time updates from Sendcloud to WooCommerce */
                        esc_html_e(
                            'To send real-time updates from Sendcloud to WooCommerce.',
                            'sendcloud-connected-shipping'
                        );
                        ?>
                    </p>
                </div>
                <div class="sc-sub-accordion-content">
                    <ol class="sc-accordion-list">
                        <li class="sc-accordion-list-step">
                            <div class="sc-sub-accordion-content-item">
                                <?php
                                /* translators: Instruction or label to create an API endpoint in WooCommerce/Sendcloud integration */
                                esc_html_e(
                                    'Create an API endpoint:',
                                    'sendcloud-connected-shipping'
                                );
                                ?>
                            </div>
                            <?php
                            /* translators: Instruction explaining that an API endpoint is required to receive webhook updates from Sendcloud */
                            esc_html_e(
                                'To receive webhook updates, you need an API endpoint that can handle incoming requests from Sendcloud.',
                                'sendcloud-connected-shipping'
                            );
                            ?>
                            <hr/>
                        </li>
                        <li class="sc-accordion-list-step">
                            <div class="sc-sub-accordion-content-item">
                                <?php
                                /* translators: Instruction or label to configure webhook feedback in Sendcloud */
                                esc_html_e(
                                    'Configure webhook feedback in Sendcloud:',
                                    'sendcloud-connected-shipping'
                                );
                                ?>
                            </div>
                            <ol>
                                <li>
                                        <span>
                                            <?php
                                            printf(
                                            /* translators:
                                               %1$s and %2$s wrap 'Settings' in italics;
                                               %3$s and %4$s wrap 'Integrations' as a clickable link;
                                               %5$s and %6$s wrap 'Configure' in bold */
                                                esc_html__(
                                                    'Go to your Sendcloud panel and navigate to %1$sSettings%2$s > %3$sIntegrations%4$s. Then click %5$sConfigure%6$s on your WooCommerce integration.',
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
                                        </span>
                                </li>
                                <li>
                                    <?php
                                    printf(
                                    /* translators: %1$s and %2$s wrap 'Webhook feedback enabled.' in bold */
                                        esc_html__(
                                            'Select the checkbox for %1$sWebhook feedback enabled%2$s.',
                                            'sendcloud-connected-shipping'
                                        ),
                                        '<strong>', '</strong>'
                                    );
                                    ?>
                                </li>
                            </ol>
                            <hr/>
                        </li>
                        <li class="sc-accordion-list-step">
                            <div class="sc-sub-accordion-content-item">
                                <?php
                                /* translators: Instruction or label asking the user to enter the webhook URL */
                                esc_html_e(
                                    'Enter the webhook URL:',
                                    'sendcloud-connected-shipping'
                                );
                                ?>
                            </div>
                            <?php
                            printf(
                            /* translators: %1$s and %2$s wrap 'Webhook URL field' in bold */
                                esc_html__(
                                    'Copy your webhook URL from your application and paste it into the %1$sWebhook URL field%2$s in Sendcloud.',
                                    'sendcloud-connected-shipping'
                                ),
                                '<strong>', '</strong>'
                            );
                            ?>
                            <hr/>
                        </li>
                        <li class="sc-accordion-list-step">
                            <div class="sc-sub-accordion-content-item">
                                <?php
                                /* translators: Instruction to set up a webhook Signature Key if it is available */
                                esc_html_e(
                                    'If present, set up a webhook Signature Key:',
                                    'sendcloud-connected-shipping'
                                );
                                ?>
                            </div>
                            <?php
                            /* translators: Explanation that this is a password used to sign all Sendcloud webhook requests */
                            esc_html_e(
                                'This is a password used to sign all Sendcloud webhook requests. The key should:',
                                'sendcloud-connected-shipping'
                            );
                            ?>
                            <ul>
                                <li>
                                    <?php
                                    printf(
                                    /* translators: %1$s and %2$s wrap '16 characters long' in bold */
                                        esc_html__(
                                            'Be at least %1$s16 characters long%2$s',
                                            'sendcloud-connected-shipping'
                                        ),
                                        '<strong>', '</strong>'
                                    );
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    printf(
                                    /* translators:
                                       %1$s and %2$s wrap 'one number, one uppercase letter, one lowercase letter' in bold;
                                       %3$s and %4$s wrap 'one special character' in bold */
                                        esc_html__(
                                            'Contain at least %1$sone number, one uppercase letter, one lowercase letter%2$s, and %3$sone special character%4$s',
                                            'sendcloud-connected-shipping'
                                        ),
                                        '<strong>', '</strong>',
                                        '<strong>', '</strong>'
                                    );
                                    ?>
                                </li>
                            </ul>
                            <hr/>
                        </li>
                        <li class="sc-accordion-list-step">
                            <div class="sc-sub-accordion-content-item">
                                <?php
                                /* translators: Instruction or label to test your webhook */
                                esc_html_e(
                                    'Test your webhook:',
                                    'sendcloud-connected-shipping'
                                );
                                ?>
                            </div>
                            <?php
                            printf(
                            /* translators: %1$s and %2$s wrap 'Test API webhook' button text in bold */
                                esc_html__(
                                    'Click on the %1$sTest API webhook%2$s button to verify your setup. If your webhook is working correctly, you should start receiving payloads at your application.',
                                    'sendcloud-connected-shipping'
                                ),
                                '<strong>', '</strong>'
                            );
                            ?>
                            <div class="sc-step-image">
                                <img class="sc-screenshot-thumb"
                                     src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/webhookfeedback_testAPIwebhook.png', 'sendcloud-connected-shipping' ); ?>"
                                     alt="<?php esc_attr_e( 'Screenshot of Test Webhook', 'sendcloud-connected-shipping' ); ?>">
                            </div>
                            <hr/>
                        </li>
                        <li class="sc-accordion-list-step">
                            <div class="sc-sub-accordion-content-item">
                                <?php
                                /* translators: Label for instruction to save the configuration */
                                esc_html_e(
                                    'Save',
                                    'sendcloud-connected-shipping'
                                );
                                ?>
                            </div>
                            <?php
                            printf(
                            /* translators: %1$s and %2$s wrap 'Save' button text in bold */
                                esc_html__(
                                    'Click %1$sSave%2$s to finalise the configuration.',
                                    'sendcloud-connected-shipping'
                                ),
                                '<strong>', '</strong>'
                            );
                            ?>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="sc-accordion-item">
                <div class="sc-sub-accordion-header">
                    <h2>
                        <?php
                        /* translators: Instruction or label to adjust the order status synchronization settings */
                        esc_html_e(
                            'Adjust order status sync',
                            'sendcloud-connected-shipping'
                        );
                        ?>
                    </h2>
                    <span class="sc-nav-arrow"></span>
                    <p>
                        <?php
                        /* translators: Instruction explaining how WooCommerce order statuses are updated when changes occur in Sendcloud */
                        esc_html_e(
                            'To choose how WooCommerce order statuses are updated when changes occur in Sendcloud.',
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
                            <hr/>
                        </li>
                        <li class="sc-accordion-list-step">
                            <?php
                            printf(
                            /* translators: %1$s and %2$s wrap 'Feedback to the webshop' in bold; the following list items explain feedback options */
                                esc_html__(
                                    'In the %1$sFeedback to the webshop%2$s dropdown, select whether and how you would like your WooCommerce order statuses to be updated:',
                                    'sendcloud-connected-shipping'
                                ),
                                '<strong>', '</strong>'
                            );
                            ?>
                            <ul>
                                <li><?php esc_html_e('Don’t send any feedback', 'sendcloud-connected-shipping'); ?></li>
                                <li><?php esc_html_e('Change the parcels’ status to “sent” once the carrier scans the label', 'sendcloud-connected-shipping'); ?></li>
                                <li>
                                    <?php esc_html_e('Change the parcels’ status to “sent” once the label
                                         is created', 'sendcloud-connected-shipping'); ?>
                                    <div class="sc-step-image">
                                        <img class="sc-screenshot-thumb"
                                             src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/feedbacktothewebshop.png', 'sendcloud-connected-shipping' ); ?>"
                                             alt="<?php esc_attr_e( 'Screenshot of Test Webhook', 'sendcloud-connected-shipping' ); ?>">
                                    </div>
                                    <?php esc_html_e('The default selection is for the parcels\' status to be set to "sent" once the label is created', 'sendcloud-connected-shipping'); ?>
                                </li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="sc-accordion-item">
                <div class="sc-sub-accordion-header">
                    <h2>
                        <?php
                        /* translators: Section heading for filtering imported orders by their status */
                        esc_html_e('Filter imported orders by status', 'sendcloud-connected-shipping');
                        ?>
                    </h2>
                    <span class="sc-nav-arrow"></span>
                    <p>
                        <?php
                        /* translators: Short description explaining that this controls which orders are imported into Sendcloud */
                        esc_html_e('To control which orders are imported into Sendcloud.', 'sendcloud-connected-shipping');
                        ?>
                    </p>
                </div>
                <div class="sc-sub-accordion-content">
                    <?php
                    printf(
                    /* translators:
                       %1$s and %2$s wrap 'pending, processing' in bold;
                       %3$s and %4$s wrap 'on-hold' in bold;
                       the rest of the text explains default pre-selected statuses and their purpose */
                        esc_html__(
                            'When you first integrate WooCommerce with Sendcloud, three statuses are pre-selected by default: %1$spending, processing%2$s, and %3$son-hold%4$s. This ensures that completed or cancelled orders are not imported immediately. The pre-selection helps you focus on orders that are ready to be processed.',
                            'sendcloud-connected-shipping'
                        ),
                        '<strong>', '</strong>',
                        '<strong>', '</strong>'
                    );
                    ?>

                    <div class="sc-note-highlighted">
                        <?php
                        printf(
                        /* translators:
                           %1$s and %2$s wrap 'Important:' in bold;
                           %3$s and %4$s wrap 'pending, processing, on-hold, completed, etc.' in italics;
                           %5$s and %6$s wrap 'Custom order statuses' as a clickable link;
                           the rest of the text explains limitations of order status filtering */
                            esc_html__(
                                '%1$sImportant:%2$s Order status filtering currently only works with the default WooCommerce statuses (e.g., %3$spending, processing, on-hold, completed, etc.%4$s). %5$sCustom order statuses%6$s are not supported at this time. If you’re using a plugin that adds custom statuses,
                                     those orders will not be imported into Sendcloud.',
                                'sendcloud-connected-shipping'
                            ),
                            '<strong>', '</strong>',
                            '<i>', '</i>',
                            '<a href="https://woocommerce.com/document/custom-order-status/" target="_blank">', '</a>'
                        );
                        ?>
                    </div>
                    <hr/>
                    <ol class="sc-accordion-list">
                        <li class="sc-accordion-list-step">
                            <?php
                            printf(
                            /* translators:
                               %1$s and %2$s wrap 'Settings' in italics;
                               %3$s and %4$s wrap 'Integrations' as a clickable link;
                               %5$s and %6$s wrap 'WooCommerce.' in bold */
                                esc_html__(
                                    'In your Sendcloud account, go to %1$sSettings%2$s > %3$sIntegrations%4$s > %5$sWooCommerce.%6$s',
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
                            <hr/>
                        </li>
                        <li class="sc-accordion-list-step">
                            <?php
                            printf(
                            /* translators: %1$s and %2$s wrap 'Select' in bold */
                                esc_html__(
                                    '%1$sSelect%2$s the additional order statuses you want to include in your filter.',
                                    'sendcloud-connected-shipping'
                                ),
                                '<strong>', '</strong>'
                            );
                            ?>
                            <div class="sc-step-image">
                                <img class="sc-screenshot-thumb"
                                     src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/orderfiltering.png', 'sendcloud-connected-shipping' ); ?>"
                                     alt="<?php esc_attr_e( 'Screenshot of Order Status Filter', 'sendcloud-connected-shipping' ); ?>">
                            </div>
                            <hr/>
                        </li>
                        <li class="sc-accordion-list-step">
                            <?php
                            printf(
                            /* translators: %1$s and %2$s wrap 'Save' button text in bold; the rest explains that Sendcloud will import orders with newly selected statuses */
                                esc_html__(
                                    'Click %1$sSave%2$s. Once saved, Sendcloud will automatically import orders with the newly selected statuses during future imports.',
                                    'sendcloud-connected-shipping'
                                ),
                                '<strong>', '</strong>'
                            );
                            ?>

                            <div class="sc-note-highlighted">
                                <?php
                                printf(
                                /* translators:
                                   %1$s and %2$s wrap 'Important:' in bold;
                                   %3$s and %4$s wrap 'last 30 days' in bold;
                                   %5$s and %6$s wrap 'customer support' as a clickable link;
                                   %7$s and %8$s wrap 'CSV file' as a clickable link;
                                   the rest of the text explains order import behavior */
                                    esc_html__(
                                        '%1$sImportant:%2$s When you modify your status filter, Sendcloud automatically imports orders from the %3$slast 30 days%4$s that match the new filter criteria. Orders older than 30 days will not be imported. If you need to retrieve older orders, you can either contact
                                                     our %5$scustomer support%6$s to manually trigger
                                                     the import, or upload the orders using a %7$sCSV file%8$s.',
                                        'sendcloud-connected-shipping'
                                    ),
                                    '<strong>', '</strong>',
                                    '<strong>', '</strong>',
                                    '<a href="' .
                                    __('https://support.sendcloud.com/hc/en-us/articles/360046514071-How-to-get-support-from-Sendcloud', 'sendcloud-connected-shipping')
                                    . '" target="_blank">', '</a>',
                                    '<a href="' .
                                    __('https://support.sendcloud.com/hc/en-us/articles/360025142831-How-can-I-import-CSV-bulk-orders', 'sendcloud-connected-shipping')
                                    . '" target="_blank">', '</a>'
                                );
                                ?>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="sc-accordion-item">
                <div class="sc-sub-accordion-header">
                    <h2>
                        <?php
                        /* translators: Section heading for enabling or disabling parcel weight import */
                        esc_html_e('Enable/disable parcel weight import', 'sendcloud-connected-shipping');
                        ?>
                    </h2>
                    <span class="sc-nav-arrow"></span>
                    <p>
                        <?php
                        /* translators: Short description explaining that this controls importing parcel weights from WooCommerce or assigning via Sendcloud */
                        esc_html_e('To import exact parcel weights from WooCommerce or assign weights through Sendcloud.', 'sendcloud-connected-shipping');
                        ?>
                    </p>
                </div>

                <div class="sc-sub-accordion-content">
                    <ol class="sc-accordion-list">
                        <li class="sc-accordion-list-step">
                            <?php
                            printf(
                            /* translators:
                               %1$s and %2$s wrap 'Settings' in italics;
                               %3$s and %4$s wrap 'Integrations' as a clickable link;
                               %5$s and %6$s wrap 'WooCommerce.' in bold */
                                esc_html__(
                                    'In your Sendcloud account, go to %1$sSettings%2$s > %3$sIntegrations%4$s > %5$sWooCommerce.%6$s',
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
                            <hr/>
                        </li>
                        <li class="sc-accordion-list-step">
                            <?php
                            printf(
                            /* translators:
                               %1$s and %2$s wrap 'Import the parcels’ weight' in bold;
                               the text explains choosing whether to import parcel weights from WooCommerce */
                                esc_html__(
                                    'Once on the Integration’s configuration page, check or uncheck %1$sImport the parcels’ weight%2$s to choose whether to import weights from your webshop.',
                                    'sendcloud-connected-shipping'
                                ),
                                '<strong>', '</strong>'
                            );
                            ?>
                            <div class="sc-note-highlighted">
                                <?php
                                printf(
                                /* translators:
                                   %1$s and %2$s wrap 'Note:' in bold;
                                   %3$s and %4$s wrap 'kg' in bold;
                                   %5$s and %6$s wrap 'g' in bold;
                                   the rest explains that only kg and g units are supported */
                                    esc_html__(
                                        '%1$sNote:%2$s Sendcloud currently only supports weights in %3$skg%4$s and %5$sg%6$s. Therefore, if you want to import weights, please make sure they are in one of these units. The weight is processed in kg once the order is imported in Sendcloud.',
                                        'sendcloud-connected-shipping'
                                    ),
                                    '<strong>', '</strong>',
                                    '<strong>', '</strong>',
                                    '<strong>', '</strong>'
                                );
                                ?>
                            </div>
                            <div class="sc-step-image">
                                <img class="sc-screenshot-thumb"
                                     src="<?php echo _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/importparcelweight.png', 'sendcloud-connected-shipping' ); ?>"
                                     alt="<?php esc_attr_e( 'Screenshot of Import Status Weight', 'sendcloud-connected-shipping' ); ?>">
                            </div>
                            <hr/>
                        </li>
                        <li class="sc-accordion-list-step">
                            <?php
                            printf(
                            /* translators: %1$s and %2$s wrap 'Save' button text in bold */
                                esc_html__(
                                    'Click %1$sSave%2$s.',
                                    'sendcloud-connected-shipping'
                                ),
                                '<strong>', '</strong>'
                            );
                            ?>
                            <hr/>
                            <?php
                            printf(
                            /* translators:
                               %1$s and %2$s wrap 'not' in italics;
                               %3$s and %4$s wrap 'default weight' in italics;
                               %5$s and %6$s wrap 'different weights' in italics;
                               the rest explains assigning weights if not imported from the webshop */
                                esc_html__(
                                    'If you chose %1$snot%2$s to import weights from your webshop (checkbox unticked), you can assign weights in Sendcloud. You can either: set a %3$sdefault weight%4$s for all items, or assign %5$sdifferent weights%6$s using Shipping Rules.',
                                    'sendcloud-connected-shipping'
                                ),
                                '<i>', '</i>',
                                '<i>', '</i>',
                                '<i>', '</i>'
                            );
                            ?>
                            <br/><br/>
                            <?php
                            printf(
                            /* translators: %1$s and %2$s wrap 'this Help Center article' as a clickable link */
                                esc_html__(
                                    'Learn how in %1$sthis Help Center article%2$s',
                                    'sendcloud-connected-shipping'
                                ),
                                '<a href="' .
                                __('https://support.sendcloud.com/hc/en-us/articles/11290144256788-How-do-I-manage-shipment-weights-in-Sendcloud', 'sendcloud-connected-shipping')
                                . '" target="_blank">', '</a>'
                            );
                            ?>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
