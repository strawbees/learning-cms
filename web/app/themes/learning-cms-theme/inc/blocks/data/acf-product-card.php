<?php
function get_block_data_acf_product_card( $block, $post_id ) {
	$data = resolve_attrs_prop($block['attrs'], 'data', array());
	$image = convert_to_cdn_url(
		$data['image'] ? wp_get_attachment_image_src(
			$data['image'], 'large'
		)[0] : ''
	);
	$color = get_field( 'primary_color', $post_id );
	return array(
		'title'          => $data['title'],
		'color'          => $color,
		'imageUrl'       => $image,
		'excerpt'        => $data['description'],
		'buyButtonLink'  => convert_to_relative_url( $data['buy_button_url'] ),
		'buyButtonLabel' => $data['buy_button_cta'],
	);
}
?>
