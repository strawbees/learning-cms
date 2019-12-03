<?php
/*
Plugin Name: Content blocks in API
Description: Adds a "blocks" field to the JSON API api results, with the parsed blocks content.
Version:     0.0.0
Author:      Strawbees
*/

add_action( 'rest_api_init', function () {
	$custom_post_types = get_post_types( array(
		'public'   => true,
		'_builtin' => false
	), 'names', 'and' );
	register_rest_field(
		array_merge( array( 'page', 'post' ), $custom_post_types ),
		'blocks',
		array(
			'get_callback' => function( $post ) {
				if (isset( $post ) &&
					isset( $post['content'] ) &&
					isset( $post['content']['raw'] )) {
					return parse_content_to_blocks( $post['content']['raw'], $post['id'] );
				}
				return array();
			},
			'schema' => array(
				'description' => __( 'Content blocks.' ),
				'type'        => 'json'
			)
		)
	);
});
?>
