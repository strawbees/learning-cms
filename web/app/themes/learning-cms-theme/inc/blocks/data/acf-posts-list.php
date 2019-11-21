<?php
function get_block_data_acf_posts_list( $block ) {
	$post_ids = $block['attrs']['data']['posts'];
	$posts = array();
	foreach ($post_ids as $id) {
		setup_postdata( get_post( $id ) );
		$posts[] = array(
			'id'         => $id,
			'title'      => get_the_title( $id ),
			'excerpt'    => get_the_excerpt( $id ),
			'link'       => convert_to_relative_url( get_permalink( $id ) ),
			'imageUrl'   => convert_to_cdn_url(
				has_post_thumbnail( $id ) ? wp_get_attachment_image_src(
					get_post_thumbnail_id( $id ), 'medium'
				)[0] : ''
			),
			'categories' => array_map(function($term) {
				return array(
					'id'    => $term->slug,
					'title' => $term->name,
					'color' => get_field( 'primary_color', 'category_' . $term->term_id ),
				);
			}, wp_get_post_terms( $id, 'category' ))
		);
		wp_reset_postdata();
	}
	return array(
		'sizeFormat' => resolve_attrs_size_format( $block['attrs'] ),
		'posts'      => $posts,
	);
}

?>
