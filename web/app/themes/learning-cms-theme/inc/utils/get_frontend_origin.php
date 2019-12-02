<?php
/**
 * Frontend origin helper function.
 *
 * @package  Learning CMS
 */

function get_frontend_origin() {
	$default = 'http://localhost:3000';
	if ( !function_exists( 'graphql' ) ) {
		return $default;
	}
	// If there is a 'configuration' post_type, get the first object, and see
	// if it has a 'frontend_url' field
	$configurations = query_posts( array( 'post_type' => array('configuration') ) );
	if ( empty($configurations) ) {
		return $default;
	}
	if ( !function_exists( 'get_field' ) ) {
		return $default;
	}
	$field = get_field('frontend_url', $configurations[0]->ID);
	if ($field) {
		return $field;
	}
	// if not, return the default
	return $default;
}
