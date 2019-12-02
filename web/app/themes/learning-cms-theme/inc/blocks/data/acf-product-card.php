<?php
function get_block_data_acf_product_card( $block ) {
	$data = resolve_attrs_prop($block['attrs'], 'data', array());
	$image = convert_to_cdn_url(
		$data['image'] ? wp_get_attachment_image_src(
			$data['image'], 'large'
		)[0] : ''
	);
	return array(
		'title'        => $data['title'],
		'image'        => $image,
		'description'  => $data['description'],
		'buyButtonURL' => convert_to_relative_url( $data['buy_button_url'] ),
		'buyButtonCTA' => $data['buy_button_cta'],
	);
}
?>
