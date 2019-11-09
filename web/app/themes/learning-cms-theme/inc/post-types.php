<?php

function cptui_register_my_cpts() {

	/**
	 * Post Type: Configurations.
	 */

	$labels = array(
		"name" => __( "Configurations", "custom-post-type-ui" ),
		"singular_name" => __( "Configuration", "custom-post-type-ui" ),
	);

	$args = array(
		"label" => __( "Configurations", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => false,
		"show_ui" => true,
		"delete_with_user" => false,
		"show_in_rest" => true,
		"rest_base" => "configurations",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "configuration", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title" ),
		"show_in_graphql" => true,
		"graphql_single_name" => 'Configuration',
		"graphql_plural_name" => 'Configurations',
	);

	register_post_type( "configuration", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );

?>
