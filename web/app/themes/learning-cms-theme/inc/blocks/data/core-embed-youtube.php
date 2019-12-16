<?php
function get_block_data_core_embed_youtube( $block ) {
	$html = convert_html_to_object( $block['innerHTML'] );
	$figcaption = find_first_html_tag ( $html, 'figcaption' );
	$innerHTML = resolve_html_children( $figcaption );
	return array(
		'sizeFormat'  => resolve_attrs_size_format( $block['attrs'] ),
		'url'         => resolve_attrs_prop( $block['attrs'], 'url'),
		'innerHTML'   => $innerHTML,
	);
}
?>
