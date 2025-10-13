<?php
/**
 * Search form template.
 *
 * @package skagit_land_trust
 */

?>
<form id="searchform" role="search" class="search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
<label for="s" class="screen-reader-text"><?php echo esc_html( __( 'Search', 'skagit-land-trust' ) ); ?></label>
<input type="text" class="search-field" id="s" name="s" placeholder="<?php echo esc_attr( __( 'Site Search', 'skagit-land-trust' ) ); ?>" value="<?php echo get_search_query(); ?>">
	<button type="submit" class="search-submit"><span class="screen-reader-text"><?php echo esc_html( __( 'Submit', 'skagit-land-trust' ) ); ?></span><i class="icon-search"></i></button>
</form>
