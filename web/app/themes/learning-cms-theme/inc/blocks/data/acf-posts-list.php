<?php
function get_block_data_acf_posts_list( $block ) {
	$configurations = query_posts( array( 'post_type' => array('configuration') ) );
	$product_categories = get_field('product_categories', $configurations[0]->ID);
	$post_ids = $block['attrs']['data']['posts'];
	$posts = array();
	foreach ($post_ids as $id) {
		setup_postdata( get_post( $id ) );
		// figure out the color by checking the category, and if it is a product
		$categories = wp_get_post_terms( $id, 'category' );
		$is_product = false;
		$label = '';
		$label_color = '';
		foreach ( $categories as $category ) {
			foreach ( $product_categories as $product_category ) {
				if ( $product_category->term_id === $category->term_id ) {
					$is_product = true;
					$label = $category->name;
					break;
				}
			}
			if ($is_product) {
				break;
			}
		}
		if ( $is_product ) {
			$label_color = get_field( 'primary_color', $id );
		} else if ( count( $categories ) ){
			$label_color = get_field( 'primary_color', 'category_' . $categories[0]->term_id );
			$label = $categories[0]->name;
		}

		$posts[] = array(
			'id'         => $id,
			'isProduct'  => $is_product,
			'title'      => get_the_title( $id ),
			'excerpt'    => get_the_excerpt( $id ),
			'link'       => convert_to_relative_url( get_permalink( $id ) ),
			'color'      => get_field( 'primary_color', $id ),
			'label'      => $label,
			'labelColor' => $label_color,
			'imageUrl'   => convert_to_cdn_url(
				has_post_thumbnail( $id ) ? wp_get_attachment_image_src(
					get_post_thumbnail_id( $id ), 'large'
				)[0] : ''
			),
		);
		wp_reset_postdata();
	}
	return array(
		'sizeFormat' => resolve_attrs_size_format( $block['attrs'] ),
		'posts'      => $posts,
	);
}
?>
