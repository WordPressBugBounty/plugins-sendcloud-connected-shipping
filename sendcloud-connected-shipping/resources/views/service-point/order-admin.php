<?php
/**
 * Contains service point data.
 *
 * @var array $data
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$address = join( '<br>', explode( '|', $data['address'] ) )
?>
<div class="address">
	<h3><?php echo esc_html( __( 'Service Point Address', 'sendcloud-connected-shipping' ) ); ?></h3>
	<?php echo wp_kses( $address, array( 'br' => array() ) ); ?>
	<br>
	<?php echo esc_html( $data['post_number'] ); ?>
	<span class="description">
	<?php
	echo wp_kses( wc_help_tip( esc_html__( "You can't change the selected Service Point", 'sendcloud-connected-shipping' ) ) . ' '
	              . esc_html__( 'Non editable', 'sendcloud-connected-shipping' ),
		array( 'span' => array( 'data-tip' => array(), 'class' => array() ) ) );
	?>
										 </span>
</div>
