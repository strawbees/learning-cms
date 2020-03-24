<?php
// https://wp-dd.com/how-to-restrict-disable-blocks-in-wordpress-block-editor-gutenberg/
// https://developer.wordpress.org/block-editor/developers/filters/block-filters/#hiding-blocks-from-the-inserter
function strawbees_learning_allowed_block_types( $allowed_block_types, $post ) {
    if ( $post->post_type == 'post'  || $post->post_type == 'page' ) {
			return array(
				'core/heading',
				'core/button',
				'core/paragraph',
				'core/list',
				'core/image',
				'core/gallery',
				'core/file',
				'core-embed/youtube',
				'embed/youtube',
				'strawbees-learning/related',
				'strawbees-learning/horizontal'
			);
    }
		return $allowed_block_types;
}
add_filter( 'allowed_block_types', 'strawbees_learning_allowed_block_types', 10, 2 );

function strawbees_learning_blocks() {

    wp_register_script(
        'strawbees-learning-blocks',
        get_theme_file_uri( 'blocks.js' ),
        array(
					'wp-blocks',
					'wp-components',
					'wp-element',
					'wp-block-editor',
					'wp-data',
					'wp-editor',
					'wp-element'
				),
        '1.0.0'
    );

    register_block_type( 'strawbees-learning/blocks', array(
        'editor_script' => 'strawbees-learning-blocks',
    ) );

}
add_action( 'init', 'strawbees_learning_blocks' );

/* Add Featured Image Support To Your WordPress Theme */
function add_feature_image() {
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'add_feature_image' );

/* Add excerpts to pages */
add_post_type_support( 'page', 'excerpt' );

// Register menu
function register_custom_nav_menus() {
	register_nav_menus( array(
		'header_menu' => 'Header Menu',
		'footer_menu' => 'Footer Menu'
	) );
}
add_action( 'after_setup_theme', 'register_custom_nav_menus' );

add_action(
	'rest_api_init',
	function ( )
	{

		if ( ! function_exists( 'use_block_editor_for_post_type' ) )
		{
			require ABSPATH . 'wp-admin/includes/post.php';
		}

		$key_name = 'blocks';
		$post_types = get_post_types_by_support( [ 'editor' ] );
		foreach ( $post_types as $post_type )
		{
			if ( use_block_editor_for_post_type( $post_type ) )
			{
				register_rest_field(
					$post_type,
					$key_name,
					[
						'get_callback' => function ( array $post )
						{
							return parse_blocks( $post['content']['raw'] );
						},
					]
				);
			}
		}
	}
);
