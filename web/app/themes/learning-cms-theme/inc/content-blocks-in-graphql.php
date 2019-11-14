<?php
add_action( 'graphql_register_types', function() {
	$post_types = \WPGraphQL::get_allowed_post_types();

	if ( ! empty( $post_types ) && is_array( $post_types ) ) {
		foreach ( $post_types as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );
			register_graphql_field( $post_type_object->graphql_single_name, 'blocksRaw', [
				'type' => 'String',
				'description' => __( 'The raw block content of the post.', 'strawbees-headless-wp' ),
				'resolve' => function( $post ) {
					$content_post = get_post($post->ID);
					$content_raw = $content_post->post_content;
					return json_encode(parse_blocks($content_raw));
				}
			] );
		}
	}
});
?>
