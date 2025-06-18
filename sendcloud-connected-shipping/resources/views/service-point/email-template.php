<?php

/**
 * Contains address info.
 *
 * @var array $data
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$address = join( '<br>', explode( '|', $data['address'] ) );

?>
<h3><?php echo esc_html(__( 'Service Point Address', 'sendcloud-connected-shipping' )); ?></h3>
<p><?php echo wp_kses($address, array('br' => array())); ?></p>
<p><?php echo esc_html($data['post_number']); ?></p>
