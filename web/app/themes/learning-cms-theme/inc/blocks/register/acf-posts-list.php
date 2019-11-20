<?php
acf_register_block_type(array(
	'name'              => 'posts-list',
	'mode'              => 'auto',
	'supports'          => array(
		'align' => false,
		'customClassName' => false,
	),
	'title'             => __('Posts list', 'strawbees-headless-wp'),
	'description'       => __('A list of posts.', 'strawbees-headless-wp'),
	'category'          => 'embed',
	'render_callback'   => function ( $block, $content = '', $is_preview = false, $post_id = 0 ) {
		$post_ids = get_field('posts');
		$posts = array();
		$configurations = query_posts( array( 'post_type' => array('configuration') ) );
		$base_url = get_field('base_url', $configurations[0]->ID);
		$cdn_url = get_field('cdn_url', $configurations[0]->ID);
		foreach ($post_ids as $id) {
			setup_postdata( get_post( $id ) );
			$posts[] = array(
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
		?>
		<div data-block="<?php echo htmlspecialchars(json_encode($posts));?>" style="background-color:#fff8be;">
			<h5>Post List</h5>
			<ul>
				<?php foreach ($posts as $post):?>
					<li><?php echo $post['title'];?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}
));
?>
