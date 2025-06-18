<?php
/**
 * Contains shipping method data.
 *
 * @var array $data
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<input type="hidden" id="<?php echo esc_attr( $data['field_id'] ); ?>"
	   value="<?php echo esc_attr( $data['carrier_select'] ); ?>">
