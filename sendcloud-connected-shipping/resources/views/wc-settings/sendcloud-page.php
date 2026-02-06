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

<?php echo SCCSP_View::file( '/wc-settings/sendcloud-connect.php' )->render($data); ?>

<?php echo SCCSP_View::file( '/wc-settings/sendcloud-dashboard.php' )->render($data); ?>

<input
        type="hidden"
        id="sc-connecting-label"
        value="<?php
        /* translators: Hidden input value shown during connection process */
        echo esc_attr__('Connecting...', 'sendcloud-connected-shipping');
        ?>"
>

