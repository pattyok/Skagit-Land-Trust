<?php
/**
 * Template part for displaying the page header of events
 *
 * @package skagit_land_trust
 */

namespace WP_Rig\WP_Rig;

use WP_Rig\WP_Rig\Helpers;

?>
<div class="page-header-event">
	<div class="entry-title">
			<?php
			wp_rig()->make_breadcrumbs( get_post_type() );
			?>
	</div>

</div>
