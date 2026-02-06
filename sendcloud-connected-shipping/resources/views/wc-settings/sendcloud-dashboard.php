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

<!-- After connection to SC Panel -->
<div class="sc-dashboard-container-wrapper">
    <div id="sc-dashboard-container" class="sc-dashboard-container <?php if(!$data['integration_id']) {echo esc_attr('sc-hidden');}?>">
        <header>
            <div class="sc-header">
                <div class="logo">
                    <img src="<?php echo plugins_url( 'resources/images/sendcloud-icon.svg', SC_PLUGIN_FILE ); ?>" alt="Sendcloud Logo">
                </div>
                <div class="sc-header-links">
                    <a href="<?php echo esc_url( $data['panel_url'] ); ?>" target="_blank" class="sc-header-link">
                        <span class="sc-header-text">
                            <?php
                            /* translators: Span label for link to Sendcloud Panel */
                            esc_html_e( 'Go to Sendcloud', 'sendcloud-connected-shipping' );
                            ?>
                        </span>
                        <img src="<?php echo plugins_url( 'resources/images/pointer-icon.svg', SC_PLUGIN_FILE ); ?>" alt="Pointer Icon">
                    </a>
                    <a href="<?php echo _e('https://support.sendcloud.com/hc/en-us/articles/34955558577297-WooCommerce-V2-Integration', 'sendcloud-connected-shipping'); ?>" target="_blank" class="sc-header-link">
                    <a href="" target="_blank" class="sc-header-link">
                        <span class="sc-header-text">

                            <?php
                            /* translators: Span label for link to Sendcloud support page */
                            esc_html_e( 'Support', 'sendcloud-connected-shipping' );
                            ?>
                        </span>
                        <img src="<?php echo plugins_url( 'resources/images/support-icon.svg', SC_PLUGIN_FILE ); ?>" alt="Sendcloud Support Icon">
                    </a>
                </div>
            </div>
        </header>

        <div class="sc-guide">
            <span class="sc-guide-title">
                <?php
                /* translators: Title for the integration guide section */
                esc_html_e( 'Configure your integration to get the most out of Sendcloud', 'sendcloud-connected-shipping' );
                ?>
            </span>

            <span class="sc-guide-subtitle sc-mb-32">
                <?php
                printf(
                        /* translators: %1$s is "Your WooCommerce shop is now connected to Sendcloud." in bold HTML, %2$s is the word "optional" in bold HTML */
                        esc_html__( '%1$s You\'ll find setup instructions below to help optimize your shipping.', 'sendcloud-connected-shipping' ),
                        '<strong>' . esc_html__( 'Your WooCommerce shop is now connected to Sendcloud.', 'sendcloud-connected-shipping' ) . '</strong>'
                );
                ?>
            </span>
        </div>

        <?php echo SCCSP_View::file( '/wc-settings/dashboard/sendcloud-panel-accordion.php' )->render($data); ?>
    </div>
</div>
<!-- END After connection to SC Panel -->
