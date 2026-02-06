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

<!--        Set up shipping methods and rules header-->
    <div class="sc-accordion-header">
        <h2>
            <?php
            /* translators: Heading for the setup step where a merchant adds shipping zones and rates */
            esc_html_e( 'Set up shipping methods and rules', 'sendcloud-connected-shipping' );
            ?>
        </h2>
        <span class="sc-nav-arrow"></span>
        <p>
            <?php
            /* translators: Description text explaining why to configure shipping zones and rates */
            esc_html_e(
                'To deliver orders using the right method and rate based on the customer’s location and other conditions.',
                'sendcloud-connected-shipping'
            );
            ?>
        </p>
    </div>

<!--        Set up shipping methods and rules content-->
    <div class="sc-accordion-content">
        <?php if( $data['migration_required'] ) : ?>
            <?php echo SCCSP_View::file( '/wc-settings/dashboard/shipping-methods/sendcloud-migrate.php' )->render($data); ?>

            <div class="sc-content-title">
                <?php
                /* translators: (Re)setting your shipping rules  */
                esc_html_e(
                        '(Re)setting your shipping rules',
                        'sendcloud-connected-shipping'
                );
                ?>
            </div>
        <?php else : ?>
            <div class="sc-content-title">
                <?php
                /* translators: Step A: Adding shipping zones  */
                esc_html_e(
                        'Step A: Adding shipping zones, methods and rates in WooCommerce',
                        'sendcloud-connected-shipping'
                );
                ?>
            </div>

            <?php echo SCCSP_View::file( '/wc-settings/dashboard/shipping-methods/sendcloud-add-shipping-zones.php' )->render($data); ?>

            <div class="sc-content-title">
                <?php
                /* translators: Step B: Setting up shipping rules in Sendcloud  */
                esc_html_e(
                        'Step B: Setting up shipping rules in Sendcloud',
                        'sendcloud-connected-shipping'
                );
                ?>
            </div>
        <?php endif; ?>

        <span>
            <?php
            /* translators: %s: Example of a carrier service in italic tags */
            printf(
                esc_html__(
                    'Shipping rules link the shipping method you added in WooCommerce (the one customers see and select at checkout) to a real carrier service, such as %s.',
                    'sendcloud-connected-shipping'
                ),
                '<i>' . esc_html__( 'DHL Home delivery', 'sendcloud-connected-shipping' ) . '</i>'
            );
            ?>
        </span>

        <ol class="sc-accordion-list">
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators: %1$s = <i>, %2$s = </i>, %3$s = opening link tag, %4$s = closing link tag */
                        esc_html__(
                                'In your Sendcloud account, go to %1$sShipping%2$s > %3$sShipping Rules%4$s.',
                                'sendcloud-connected-shipping'
                        ),
                        '<i>', '</i>',
                        '<a href="' . esc_url('https://app.sendcloud.com/v2/shipping/rules') . '" target="_blank">', '</a>'
                );
                ?>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators:
                %1$s and %2$s wrap "Click Create new rule"
                %3$s and %4$s wrap "Open template"
                %5$s and %6$s wrap "Select shipping method based on customer choice at checkout"
                %7$s and %8$s wrap "Start creating"
                */
                        esc_html__(
                                'Click %1$sCreate new rule%2$s, then choose %3$sOpen template%4$s in
                                 “%5$sSelect shipping method based on customer choice at checkout%6$s”
                                  (or %7$sStart creating%8$s in “Build a custom rule from scratch”).',
                                'sendcloud-connected-shipping'
                        ),
                        '<strong>', '</strong>',
                        '<strong>', '</strong>',
                        '<strong>', '</strong>',
                        '<strong>', '</strong>'
                );
                ?>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators: %1$s and %2$s wrap the example rule name in italic tags */
                        esc_html__(
                                'Name your shipping rule, for example “%1$sDelivery 24-48 hrs Peninsular%2$s”.',
                                'sendcloud-connected-shipping'
                        ),
                        '<i>',
                        '</i>'
                );
                ?>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators: %1$s and %2$s wrap the example rule name in strong tags */
                        esc_html__(
                                'Use the condition %1$s“Checkout delivery method”%2$s.',
                                'sendcloud-connected-shipping'
                        ),
                        '<strong>',
                        '</strong>'
                );
                ?>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators: %1$s and %2$s wrap “Contains”, %3$s and %4$s wrap “Is” */
                        esc_html__(
                                'Select the operator “%1$sContains%2$s” (if the name could be longer) or “%3$sIs%4$s” (for an exact match).',
                                'sendcloud-connected-shipping'
                        ),
                        '<strong>', '</strong>',
                        '<strong>', '</strong>'
                );
                ?>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators:
                    %1$s and %2$s wrap “Contains”
                    %3$s and %4$s wrap “Is”
                    %5$s and %6$s wrap “Delivery 24–48 hrs”
                */
                        esc_html__(
                                'In the next field, enter part of the name from your WooCommerce shipping method (if you selected “%1$sContains%2$s”) or the exact name (if you selected “%3$sIs%4$s”). In this example: “%5$sDelivery 24–48 hrs%6$s”.',
                                'sendcloud-connected-shipping'
                        ),
                        '<i>', '</i>',
                        '<i>', '</i>',
                        '<i>', '</i>'
                );
                ?>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                printf(
                /* translators: %1$s and %2$s wrap “Actions” in strong */
                        esc_html__(
                                'Under “Actions”, use the option %1$s“Ship with”%2$s.',
                                'sendcloud-connected-shipping'
                        ),
                        '<strong>', '</strong>'
                );
                ?>
                <hr/>
            </li>
            <li class="sc-accordion-list-step">
                <?php
                /* translators: Select a value */
                esc_html_e(
                        'From the “Select a value” drop-down, select the applicable shipping method.',
                        'sendcloud-connected-shipping'
                    )
                ?>
            </li>
        </ol>

        <div class="sc-note-highlighted">
            <?php
            printf(
            /* translators:
            %1$s and %2$s = <strong>Note:</strong>
            %3$s = <i>“Correos Premium Home Delivery”</i>
            %4$s = <i>“Correos Premium Home Delivery 0–1 kg, 1–2 kg”</i>
            %5$s = <i>“In some cases, certain carriers may require a separate rule for each weight range.”</i>
            */
                    esc_html__(
                                '%1$sNote:%2$s If you ship parcels with different weights, in most cases, you only need to create one shipping rule for your chosen shipping option (for example, %3$s ...). Select any method within that option; the system will automatically choose the correct one based on the parcel’s weight (for example, %4$s, and so on). %5$s',
                                'sendcloud-connected-shipping'
                    ),
                    '<strong>', '</strong>',
                    '<i>' . esc_html__( 'Correos Premium Home Delivery', 'sendcloud-connected-shipping' ) . '</i>',
                    '<i>' . esc_html__( 'Correos Premium Home Delivery 0–1 kg, 1–2 kg', 'sendcloud-connected-shipping' ) . '</i>',
                    '<i>' . esc_html__( 'In some cases, certain carriers may require a separate rule for each weight range.', 'sendcloud-connected-shipping' ) . '</i>'
            );
            ?>
        </div>

        <div class="sc-step-image">
            <img class="sc-screenshot-thumb"
                 src="<?php echo _e( 'https://www.sendcloud.com/wp-content/help-center-images/WooCommerce/setup_checklist/Checkout%20method_shipping%20rule.png', 'sendcloud-connected-shipping' ) ?>"
                 alt="<?php esc_attr_e( 'Screenshot of WooCommerce Shipping Settings', 'sendcloud-connected-shipping' ); ?>">
        </div>

        <?php
        /* translators: Instruction for repeating the process for all shipping methods, with a note about subscription plans. */
        esc_html_e(
                'Repeat this process for all the shipping methods available at checkout. Keep in mind that each 
                subscription plan includes a limited number of shipping rules (unlimited for the Premium and Pro plans).',
                'sendcloud-connected-shipping'
        );
        ?>
    </div>
</div>