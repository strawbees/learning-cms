<?php
function get_block_data_core_gallery( $block ) {
	$html = convert_html_to_object( $block['innerHTML'] );
	$ul = find_first_html_tag ( $html, 'ul' );
	$items = array();
	foreach ($ul['innerChildren'] as $li) {
		$a = find_first_html_tag ( $li, 'a' );
		$img = find_first_html_tag ( $li, 'img' );
		$figcaption = find_first_html_tag ( $li, 'figcaption' );
		$link = '';
		if ( $a ) {
			$link = resolve_html_attribute( $a, 'href' );
		}
		$captionHtml = null;
		if ( $figcaption ) {
			$captionHtml = resolve_html_children( $figcaption );
		}
		$items[] = array(
			'url'         => resolve_html_attribute( $img, 'src' ),
			'link'        => $link,
			'captionHtml' => $captionHtml,
		);
	}
	return array(
		'sizeFormat' => resolve_attrs_size_format( $block['attrs'] ),
		'items'      => $items,
	);
}
?>
