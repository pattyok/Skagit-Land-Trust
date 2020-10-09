<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>


	<?php get_template_part( 'template-parts/footer/info' ); ?>

</div><!-- #page -->
<!-- Retargeting Pixel -->

<script type="text/javascript">

adroll_adv_id = "QS6BSTZIAFHNNEAJJFGNXI";
adroll_pix_id = "CLR7FIWV6ZBCTCMYOBGM5X";
(function () {
	var _onload = function(){
		if (document.readyState && !/loaded|complete/.test(document.readyState)){setTimeout(_onload, 10);return}
		if (!window.__adroll_loaded){__adroll_loaded=true;setTimeout(_onload, 50);return}
		var scr = document.createElement("script");
		var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
		scr.setAttribute('async', 'true');
		scr.type = "text/javascript";
		scr.src = host + "/j/roundtrip.js";
		((document.getElementsByTagName('head') || [null])[0] ||
			document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
	};
	if (window.addEventListener) {window.addEventListener('load', _onload, false);}
	else {window.attachEvent('onload', _onload)}
}());
</script>
<?php wp_footer(); ?>

</body>
</html>
