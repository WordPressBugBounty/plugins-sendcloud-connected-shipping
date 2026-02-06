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

<!--        Configure product data header-->
    <div class="sc-accordion-header">
        <h2>
            <span class="sc-nav-arrow"></span>
            <?php
            /* translators: Instruction heading for configuring product data */
            esc_html_e(
                'Configure product data',
                'sendcloud-connected-shipping'
            );
            ?>

        </h2>
        <p>
            <?php
            /* translators: Description text explaining why to import all necessary item details into Sendcloud */
            esc_html_e(
                'To ensure all necessary item details are imported into Sendcloud, helping you stay compliant with carrier requirements and customs documentation.',
                'sendcloud-connected-shipping'
            );
            ?>
        </p>
    </div>

<!--            Configure product data content-->
    <div class="sc-accordion-content">
        <?php if ( $data['migration_required'] ) : ?>
            <?php
            printf(
            /* translators:
             * %1$s and %2$s wrap <strong>EAN</strong>
             * %3$s and %4$s wrap <strong>HS Code</strong>
             * %5$s and %6$s wrap <strong>Country of Origin</strong>
             */
                    esc_html__(
                            'Now that you’ve migrated to V2, new fields for %1$sEAN%2$s, %3$sHS Code%4$s, and %5$sCountry of Origin%6$s are available in WooCommerce. You no longer need to create custom attributes for these details.',
                            'sendcloud-connected-shipping'
                    ),
                    '<strong>', '</strong>',
                    '<strong>', '</strong>',
                    '<strong>', '</strong>'
            );
            ?>

            <div class="sc-note-highlighted">
                <?php
                printf(
                /* translators:
                 * %1$s and %2$s wrap <strong>Note:</strong>
                 */
                        esc_html__(
                                '%1$sNote:%2$s',
                                'sendcloud-connected-shipping'
                        ),
                        '<strong>', '</strong>'
                );
                ?>

                <ul>
                    <li>
                        <?php
                        esc_html_e(
                                'If both the old custom attributes and the new fields are filled in, the new fields take priority.',
                                'sendcloud-connected-shipping'
                        );
                        ?>
                    </li>
                    <li>
                        <?php
                        esc_html_e(
                                'We recommend using the new fields and removing the old custom attributes when possible to ensure accurate product data.',
                                'sendcloud-connected-shipping'
                        );
                        ?>
                    </li>
                </ul>
            </div>
        <?php endif; ?>


        <div class="sc-content-title ">
            <?php
            /* translators: Section heading for setting EAN (European Article Number) codes */
            esc_html_e(
                'Setting EAN Codes',
                'sendcloud-connected-shipping'
            );
            ?>
        </div>
        <span>
                <?php
                printf(
                /* translators: %1$s and %2$s wrap 'Products' in italics;
                   %3$s and %4$s wrap 'All Products' in italics;
                   %5$s and %6$s wrap 'Product Data > Inventory' in italics */
                    esc_html__(
                        'In your WooCommerce dashboard, go to %1$sProducts%2$s > %3$sAll Products%4$s and select a product. Under %5$sProduct Data > Inventory%6$s, enter the EAN in:',
                        'sendcloud-connected-shipping'
                    ),
                    '<i>', '</i>',
                    '<i>', '</i>',
                    '<i>', '</i>'
                );
                ?>
            </span>

        <ul>
            <li>
                <?php
                printf(
                /* translators: %1$s and %2$s wrap 'EAN by Sendcloud' in bold;
                   %3$s and %4$s wrap '9.2.' in bold */
                    esc_html__(
                        '%1$sEAN by Sendcloud%2$s field for WooCommerce versions below %3$s9.2%4$s',
                        'sendcloud-connected-shipping'
                    ),
                    '<strong>', '</strong>',
                    '<strong>', '</strong>'
                );
                ?>:
                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php echo _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/englishwoocommm1229983834.png', 'sendcloud-connected-shipping' ); ?>"
                         alt="<?php esc_attr_e( 'Screenshot of EAN Attribute', 'sendcloud-connected-shipping' ); ?>">
                </div>
            </li>
            <li>
                <?php
                printf(
                /* translators: %1$s and %2$s wrap 'EAN' in bold;
                   %3$s and %4$s wrap '9.2 and higher' in bold */
                    esc_html__(
                        'WooCommerce’s native %1$sEAN%2$s field for versions %3$s9.2 and higher%4$s',
                        'sendcloud-connected-shipping'
                    ),
                    '<strong>', '</strong>',
                    '<strong>', '</strong>'
                );
                ?>:
                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php echo _e( 'https://www.sendcloud.com/wp-content/help-center-images/ENV2inventory22.png', 'sendcloud-connected-shipping' ); ?>"
                         alt="<?php esc_attr_e( 'Screenshot of EAN Attribute', 'sendcloud-connected-shipping' ); ?>">
                </div>
                <hr/>
            </li>
        </ul>

        <div class="sc-content-title">
            <?php
            /* translators: Section heading for configuring HS Codes and Country of Origin (used in international shipping) */
            esc_html_e(
                'Setting HS Codes and Country of Origin (for international shipping)',
                'sendcloud-connected-shipping'
            );
            ?>
        </div>

        <ul>
            <li>
                     <span>
                        <?php
                        printf(
                        /* translators: %1$s and %2$s wrap the word 'Shipping' (tab name in WooCommerce Product Data) in bold */
                            esc_html__(
                                'Enter the HS Code and Country of Origin under the %1$sShipping%2$s tab in Product Data.',
                                'sendcloud-connected-shipping'
                            ),
                            '<strong>', '</strong>'
                        );
                        ?>
                    </span>
                <div class="sc-step-image">
                    <img class="sc-screenshot-thumb"
                         src="<?php _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/WCSPEN5.png', 'sendcloud-connected-shipping' ); ?>"
                         alt="<?php esc_attr_e( 'Screenshot of HS Code and Country Attribute', 'sendcloud-connected-shipping' ); ?>">
                </div>
            </li>
        </ul>
    </div>
</div>
