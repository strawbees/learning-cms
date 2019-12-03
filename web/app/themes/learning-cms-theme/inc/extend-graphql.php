<?php
/**
 * Add a "blocksRaw" property to all post types.
 */
add_action( 'graphql_register_types', function() {
	$post_types = \WPGraphQL::get_allowed_post_types();

	if ( ! empty( $post_types ) && is_array( $post_types ) ) {
		foreach ( $post_types as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );
			register_graphql_field( $post_type_object->graphql_single_name, 'blocksRaw', [
				'type' => 'String',
				'description' => __( 'The raw block content of the post.', 'strawbees-headless-wp' ),
				'resolve' => function( $post ) {
					$content_post = get_post( $post->ID );
					$content_raw = $content_post->post_content;
					return json_encode( parse_content_to_blocks( $content_raw, $post->ID ) );
				}
			] );
		}
	}
});

/**
 * Add the editor color palette and font sizes in the configuration post type
 */
add_action( 'graphql_register_types', function() {
	$post_types = array('configuration', 'setting');
	foreach ( $post_types as $post_type ) {
		$post_type_object = get_post_type_object( $post_type );
		if ($post_type_object) {
			// Color Palette
			register_graphql_object_type( 'EditorColor', [
				'description' => __( 'A color from the editor palette.', 'strawbees-headless-wp' ),
				'fields' => [
					'name' => [
						'type' => 'String',
						'description' => __( 'The name of the color', 'strawbees-headless-wp' ),
					],
					'slug' => [
						'type' => 'String',
						'description' => __( 'The slug of the color', 'strawbees-headless-wp' ),
					],
					'color' => [
						'type' => 'String',
						'description' => __( 'The css value of the color', 'strawbees-headless-wp' ),
					],
				],
			]);
			register_graphql_field( $post_type_object->graphql_single_name, 'editorColorPalette', [
				'type' => [ 'list_of' => 'EditorColor' ],
				'description' => __( 'The available colors of the editor pallete.', 'strawbees-headless-wp' ),
				'resolve' => function() {
					return get_theme_support( 'editor-color-palette' )[0];
				}
			] );
			// Font sizes
			register_graphql_object_type( 'EditorFontSize', [
				'description' => __( 'A font size from the editor.', 'strawbees-headless-wp' ),
				'fields' => [
					'name' => [
						'type' => 'String',
						'description' => __( 'The name of the size', 'strawbees-headless-wp' ),
					],
					'slug' => [
						'type' => 'String',
						'description' => __( 'The slug of the size', 'strawbees-headless-wp' ),
					],
					'size' => [
						'type' => 'String',
						'description' => __( 'The css value of the size', 'strawbees-headless-wp' ),
					],
				],
			]);
			register_graphql_field( $post_type_object->graphql_single_name, 'editorFontSizes', [
				'type' => [ 'list_of' => 'EditorFontSize' ],
				'description' => __( 'The available fonts sizes of the editor.', 'strawbees-headless-wp' ),
				'resolve' => function() {
					return get_theme_support( 'editor-font-sizes' )[0];
				}
			] );
		}
	}
});
?>
