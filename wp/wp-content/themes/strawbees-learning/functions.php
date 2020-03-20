<?php
// https://wp-dd.com/how-to-restrict-disable-blocks-in-wordpress-block-editor-gutenberg/
// https://developer.wordpress.org/block-editor/developers/filters/block-filters/#hiding-blocks-from-the-inserter
function strawbees_learning_allowed_block_types( $allowed_block_types, $post ) {
    if ( $post->post_type !== 'post' ) {
        return $allowed_block_types;
    }
    return array(
			'core/heading',
			'core/paragraph',
			'core/list',
			'core/image',
			'core/gallery',
			'core/file',
			'embed/youtube'
		);
}

add_filter( 'allowed_block_types', 'strawbees_learning_allowed_block_types', 10, 2 );

function strawbees_learning_blocks() {

    wp_register_script(
        'strawbees-learning-blocks',
        get_theme_file_uri( 'blocks.js' ),
        false,
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
