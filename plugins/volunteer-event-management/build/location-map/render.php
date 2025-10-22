<?php

?>
<div <?php echo get_block_wrapper_attributes(); ?>>
<div class="location-map-wrapper">
<?php
$access = ( get_field('loc_open_to_the_public') ) ? 'public' : 'private';
?>
	<?php
	$lat       = '';
	$lng       = '';
	$usepoints = false;
	if ( get_field( 'loc_lat' ) && get_field( 'loc_long' ) ) {
		$lng = get_field( 'loc_long' );
		$lat = get_field( 'loc_lat' );
	}
	if ( $lat && $lng ) {
		echo '<span class="data-geo" data-icon="' . $access . '" data-lat="' . $lat . '" data-lng="' . $lng . '" data-label="' . get_the_title() . ', ' . get_field('loc_location') . '" data-geo-type="LatLng"';
		if ( ! empty( $icon ) ) {
			echo 'data-icon="' . $icon . '" ';
		}
		echo '>[' . $lat . ',' . $lng . ']</span>';
	}
	echo '<div class="single-location-map"></div>';
	?>
</div>
</div>
