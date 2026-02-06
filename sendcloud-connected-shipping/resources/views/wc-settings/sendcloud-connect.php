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

<!-- Before connection to SC Panel -->
<div class="sendcloud-content connect">
    <div id="sc-connect-container" class="sendcloud-button-text-container <?php if( $data['integration_id'] ){echo esc_attr('sc-hidden');}?>">
        <img src="<?php echo plugins_url( 'resources/images/sendcloud-icon.svg', SC_PLUGIN_FILE ); ?>" alt="Sendcloud Logo">

        <h2>
            <?php esc_html_e('You’re just one step away from easier order processing, faster shipping, less work, and happier customers.', 'sendcloud-connected-shipping'); ?>
        </h2>

        <p>
            <?php printf(
            /* translators: %s is the word "Connect" in bold HTML */
                    __('Tick the checkbox to enable the Connect button and click %s to get started.', 'sendcloud-connected-shipping'),
                    '<strong>' . __('Connect', 'sendcloud-connected-shipping') . '</strong>'
            );?>
        </p>

        <div class="sendcloud-agreement-container">
            <p>
                <?php
                printf(
                /* translators: %s is API username example */
                        __('When you connect, the plugin will create a new user named %s with the Shop Manager role.', 'sendcloud-connected-shipping'),
                        '<code>sendcloud_api_***</code>'
                );
                ?>
                <?php _e('A secure connection via WooCommerce REST API keys will be established.', 'sendcloud-connected-shipping'); ?>
            </p>

            <label>
                <input type="checkbox" id="sc_agreement">
                <?php
                /* translators: This text appears next to a checkbox that the user must tick before connecting */
                esc_html_e(
                        'I acknowledge and agree to the creation of this user and API key',
                        'sendcloud-connected-shipping'
                );
                ?>
            </label>
        </div>


        <?php
        $disabled_class = !$data['permalinks_enabled'] ? ' sendcloud-button-disabled' : '';
        ?>
        <button
                class="sendcloud-button connect disabled <?php echo esc_attr($disabled_class); ?>"
                <?php disabled(!$data['permalinks_enabled']); ?>
                disabled="disabled"
        >
            <?php
            /* translators: Button label to connect the plugin with Sendcloud */
            esc_html_e('Connect', 'sendcloud-connected-shipping');
            ?>
        </button>

    </div>
</div>
<!-- END Before connection to SC Panel -->
