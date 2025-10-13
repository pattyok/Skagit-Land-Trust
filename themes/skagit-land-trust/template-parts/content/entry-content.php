<?php
/**
 * Template part for displaying a post's content
 *
 * @package skagit_land_trust
 */

namespace WP_Rig\WP_Rig;

?>

		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'skagit-land-trust' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);

