<?php
function get_block_data_acf_posts_list($block) {
	$post_ids = $block['attrs']['data']['posts'];
	$data = array();
	$configurations = query_posts( array( 'post_type' => array('configuration') ) );
	$base_url = get_field('base_url', $configurations[0]->ID);
	$cdn_url = get_field('cdn_url', $configurations[0]->ID);
	foreach ($post_ids as $id) {
		setup_postdata( get_post( $id ) );
		$data[] = array(
			'id'            => $id,
			'title'         => get_the_title( $id ),
			'excerpt'       => get_the_excerpt( $id ),
			'link'          => str_replace( $base_url, '', get_permalink( $id ) ),
			'featuredImage' => str_replace( $base_url, $cdn_url,
				has_post_thumbnail( $id ) ? wp_get_attachment_image_src(
					get_post_thumbnail_id( $id ), 'medium'
				)[0] : ''
			),
			'categories'    => array_map(function($term) {
				return array(
					'id'    => $term->slug,
					'title' => $term->name,
					'color' => get_field( 'primary_color', 'category_' . $term->term_id ),
				);
			}, wp_get_post_terms( $id, 'category' ))
		);
		wp_reset_postdata();
	}
	return $data;
}

?>
