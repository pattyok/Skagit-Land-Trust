<?php
/** This content displays in the print view only
 *
 * @package wp_rig
 */

?>
<div class="print-only">
	<?php
	echo do_shortcode( '[site_copy]' ) . '<br>';
	echo get_permalink() . '<br>'; //phpcs:ignore
	echo date( 'F j, Y g:i a' ); //phpcs:ignore
	?>
</div>
