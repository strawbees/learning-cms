<?php
acf_register_block_type(array(
	'name'              => 'product-card',
	'mode'              => 'auto',
	'supports'          => array(
		'align'           => false,
		'customClassName' => false,
	),
	'title'             => __('Product card', 'strawbees-headless-wp'),
	'description'       => __('The card of a product, usually used in the product page hero.', 'strawbees-headless-wp'),
	'category'          => 'embed',
	'render_callback'   => function ( $block, $content = '', $is_preview = false, $post_id = 0 ) {
		$title = get_field('title');
		$image = get_field('image');
		$description = get_field('description');
		$buy_button_url = get_field('buy_button_url');
		$buy_button_cta = get_field('buy_button_cta');
		?>
		<div style="background-color:#fff8be; color:#000;">
			<h5>Product Card</h5>
			<img src="<?php echo $image['url'];?>"/>
			<p><?php echo $description;?></p>
			<a href="<?php echo $buy_button_url;?>">
				<?php echo $buy_button_cta;?>
			</a>
		</div>
		<?php
	}
));
?>
