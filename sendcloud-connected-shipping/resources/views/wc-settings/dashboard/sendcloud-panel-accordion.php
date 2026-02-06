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

<div class="sc-accordion">
    <?php
        echo SCCSP_View::file( '/wc-settings/dashboard/sendcloud-test-integration.php' )->render($data);
    ?>

    <?php echo SCCSP_View::file( '/wc-settings/dashboard/shipping-methods/sendcloud-setup.php' )->render($data); ?>

    <?php
    if ( $data['migration_required'] ) {
        echo SCCSP_View::file( '/wc-settings/dashboard/service-point/sendcloud-migrate.php' )->render($data);
    } else {
        echo SCCSP_View::file( '/wc-settings/dashboard/service-point/sendcloud-setup.php' )->render($data);
    }
    ?>

    <?php echo SCCSP_View::file( '/wc-settings/dashboard/sendcloud-configure-product.php' )->render($data); ?>

    <?php echo SCCSP_View::file( '/wc-settings/dashboard/sendcloud-manage-synchronisation.php' )->render($data); ?>

    <?php echo SCCSP_View::file( '/wc-settings/dashboard/sendcloud-send-tracking.php' )->render($data); ?>
</div>
