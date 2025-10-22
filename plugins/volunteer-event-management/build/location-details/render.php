<?php
$activities = get_the_terms( get_the_ID(), 'skgt_location_cat' );
?>
<div <?php echo get_block_wrapper_attributes( ); ?>>

<ul class="location-details-list no-bullets">
	<?php $loc_type = get_field( 'loc_type' ); ?>
	<?php if ( ! empty( $loc_type ) && !empty( $loc_type['label'] ) ) : ?>
		<li><strong>Property Type:</strong> <?php echo esc_html( $loc_type['label'] ); ?></li>
	<?php endif; ?>
	<?php if ( ! empty( get_field( 'loc_date_added_notes' ) ) ) : ?>
		<li><strong>Date Added:</strong> <?php echo esc_html( get_field( 'loc_date_added_notes' ) ); ?></li>
	<?php elseif ( ! empty( get_field( 'loc_date_added' ) ) ) : ?>
		<li><strong>Date Added:</strong> <?php echo esc_html( get_field( 'loc_date_added' ) ); ?></li>
	<?php endif; ?>
</ul>
<ul class="location-data-list no-bullets">
	<?php if ( ! empty( get_field( 'loc_location' ) ) ) : ?>
		<li class="location-data-item"><span class="icon-location" title="Location"></span> <?php echo esc_html( get_field( 'loc_location' ) ); ?></li>
	<?php endif; ?>
	<?php if ( ! empty( get_field( 'loc_acreage' ) ) ) : ?>
		<li class="location-data-item"><span class="icon-acreage" title="Acreage"></span> <?php echo esc_html( get_field( 'loc_acreage' ) ); ?> acres</li>
	<?php endif; ?>

	<li class="location-data-item"><span class="icon-access" title="Access"></span> <?php echo ( get_field('loc_open_to_the_public') ) ? 'Open to the Public' : 'Closed to the Public'; ?></li>
	<?php if ( $activities && ! is_wp_error( $activities ) ) :
		foreach ( $activities as $activity ) { ?>
		<li class="location-data-item"><span class="icon-<?php echo esc_attr( $activity->slug ); ?>"></span>
			<?php echo esc_html( $activity->name ); ?>
		</li>

	<?php }
	endif; ?>
	</ul>
	<?php if ( (get_field('loc_open_to_the_public') == true) && ! empty( get_field( 'loc_lat' ) ) && ! empty( get_field( 'loc_long' ) ) ) : ?>
		<ul class="location-data-list no-bullets">
		<li class="location-data-link"><a class="arrow-link" href="https://www.google.com/maps/search/?api=1&query=<?php echo esc_html( get_field( 'loc_lat' ) ); ?>,<?php echo esc_html( get_field( 'loc_long' ) ); ?>" target="_blank" rel="noopener noreferrer">Get Directions</a></li>
		</ul>
		<?php endif; ?>

</div>
