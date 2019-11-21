<?php
function get_block_data_core_image( $block ) {
	$url = convert_to_cdn_url(
		wp_get_attachment_image_src(
			resolve_attrs_prop( $block['attrs'], 'id' ),
			resolve_attrs_prop( $block['attrs'], 'sizeSlug', 'large' )
		)[0]
	);
	$html = convert_html_to_object( $block['innerHTML'] );
	$figcaption = find_first_html_tag ( $html, 'figcaption' );
	$a = find_first_html_tag ( $html, 'a' );
	return array(
		'sizeFormat'  => resolve_attrs_size_format( $block['attrs'] ),
		'url'         => $url,
		'link'        => resolve_html_attribute( $a, 'href' ),
		'captionHtml' => resolve_html_children( $figcaption ),
	);
}
?>
