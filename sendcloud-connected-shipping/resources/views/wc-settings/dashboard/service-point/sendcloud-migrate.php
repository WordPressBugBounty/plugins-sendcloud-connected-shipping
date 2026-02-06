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

<!--    Migrate your Service Point delivery header-->
    <div class="sc-accordion-header">
        <h2>
            <span class="sc-nav-arrow"></span>
            <?php
            /* translators: Title or instruction to migrate the Service Point delivery configuration */
            esc_html_e(
                'Migrate your Service Point delivery configuration',
                'sendcloud-connected-shipping'
            );
            ?>
        </h2>
        <p>
            <?php
            /* translators: Instruction to copy and activate Service Point delivery methods from WooCommerce V1 integration */
            esc_html_e(
                'To copy and activate the Service Point delivery methods from your WooCommerce V1 integration',
                'sendcloud-connected-shipping'
            );
            ?>.
        </p>
    </div>

<!--    Migrate your Service Point delivery content-->
    <div class="sc-accordion-content">
        <div class="sc-content-title">
            <?php
            /* translators: Step A: Verifying your Service Point delivery settings  */
            esc_html_e(
                    'Step A: Verifying your Service Point delivery settings',
                    'sendcloud-connected-shipping'
            );
            ?>
        </div>

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
        <ul>
            <li>
                <?php
                printf(
                /* translators:
                 * %1$s and %2$s = <strong>enabled</strong>
                 */
                        esc_html__(
                                'Make sure service points are %1$senabled%2$s.',
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
                 * %1$s and %2$s = <strong>carriers</strong>
                 * %3$s and %4$s = <strong>selected</strong>
                 */
                        esc_html__(
                                'Ensure that %1$scarriers%2$s supporting service point delivery are %3$sselected%4$s.',
                                'sendcloud-connected-shipping'
                        ),
                        '<strong>', '</strong>',
                        '<strong>', '</strong>'
                );
                ?>
            </li>
        </ul>

        <div id="sc-migration-initiation" class="migrate  <?php if ($data['is_migration_completed']) {echo esc_attr('sc-hidden');}?>">
            <h4>Click the following button:</h4>
            <button id="sc-migrate-service-points" class="sendcloud-button migrate">
                <?php esc_html_e('Migrate Service Points', 'sendcloud-connected-shipping'); ?>
            </button>
            <p>This will copy all existing Service Point shipping methods from V1.</p><br/>
            After you click the button, two new steps will appear to guide you through completing the full migration.
        </div>

        <div id="sc-migration-completed-steps" class="<?php if (!$data['is_migration_completed']) {echo esc_attr('sc-hidden');} ?>">
            <div class="sc-migration-completed">
                <h2>
                    <img src="<?php echo plugins_url( 'resources/images/checked-icon.svg', SC_PLUGIN_FILE ); ?>" alt="Completed">
                    You have migrated your service points
                </h2>
                <p>All existing Service Point shipping methods from V1 were copied.</p>
            </div>
            <br/>
            Now follow the next steps to <strong>activate</strong> the new Service Point delivery methods
            <hr/>
            <ol class="sc-accordion-list">
                <li class="sc-accordion-list-step">
                    <strong>Check the new shipping methods:</strong>
                    <ol>
                        <li>
                            <?php
                            printf(
                            /* translators: %1$s and %2$s wrap WooCommerce > Settings in italics; %3$s and %4$s wrap the Shipping link */
                                __('Navigate to %1$sWooCommerce > Settings%2$s > %3$sShipping%4$s.', 'sendcloud-connected-shipping'),
                                '<i>', '</i>',
                                '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=shipping' ) ) . '" target="_blank">',
                                '</a>'
                            );
                            ?>
                        </li>
                        <li>
                            In the relevant shipping zones, you will see
                            <strong>duplicated Service Point methods.</strong>
                        </li>
                        <li>
                            The <strong>new ones will be disabled</strong> by default to avoid any potential
                            disruptions and to ensure that any changes in sensitive parts of your shop are only
                            performed with <strong>your explicit consent.</strong>
                        </li>
                    </ol>
                    <div class="sc-step-image">
                        <img class="sc-screenshot-thumb"
                             src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/MigrationSPWC22.png', 'sendcloud-connected-shipping' ); ?>"
                             alt="<?php esc_attr_e( 'Screenshot of Migrated Shipping', 'sendcloud-connected-shipping' ); ?>">
                    </div>
                    <hr/>
                </li>
                <li class="sc-accordion-list-step">
                    <strong>Review and activate:</strong> <br/>
                    Review the new shipping methods, rename or adjust settings if needed, then <strong>enable the
                        new methods</strong> and <strong>disable the old ones.</strong>
                    <div class="sc-step-image">
                        <img class="sc-screenshot-thumb"
                             src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/DisMigraSPWC.png', 'sendcloud-connected-shipping' ); ?>"
                             alt="<?php esc_attr_e( 'Screenshot of Migrated Shipping', 'sendcloud-connected-shipping' ); ?>">                    </div>
                </li>
            </ol>
        </div>
    </div>
</div>
