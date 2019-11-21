<?php
function convert_to_cdn_url($url) {
	$configurations = query_posts( array( 'post_type' => array('configuration') ) );
	$base_url = get_field('base_url', $configurations[0]->ID);
	$cdn_url = get_field('cdn_url', $configurations[0]->ID);

	return str_replace( $base_url, $cdn_url, $url);
}
?>
