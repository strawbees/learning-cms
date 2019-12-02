<?php
function get_block_data_core_embed_youtube( $block ) {
	$html = convert_html_to_object( $block['innerHTML'] );
	$captionHtml = find_first_html_tag ( $html, 'figcaption' );
	return array(
		'sizeFormat'  => resolve_attrs_size_format( $block['attrs'] ),
		'url'         => resolve_attrs_prop( $block['attrs'], 'url'),
		'captionHtml' => resolve_html_children( $captionHtml ),
	);
}
?>
