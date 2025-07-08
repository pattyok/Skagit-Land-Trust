<?php
/**
 * Search form template.
 *
 * @package lazar_2025
 */

?>
<form id="searchform" role="search" class="search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
<label for="s" class="screen-reader-text"><?php echo esc_html( __( 'Search', 'lazar-2025' ) ); ?></label>
<input type="text" class="search-field" id="s" name="s" placeholder="<?php echo esc_attr( __( 'Site Search', 'lazar-2025' ) ); ?>" value="<?php echo get_search_query(); ?>">
	<button type="submit" class="search-submit"><span class="screen-reader-text"><?php echo esc_html( __( 'Submit', 'lazar-2025' ) ); ?></span><i class="icon-search"></i></button>
</form>
