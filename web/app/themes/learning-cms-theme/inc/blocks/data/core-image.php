<?php
function get_block_data_core_image($block) {
	$url = convert_to_cdn_url(
		wp_get_attachment_image_src(
			$block['attrs']['id'], $block['attrs']['sizeSlug']
		)[0]
	);
	$html = convert_html_to_object( $block['innerHTML'] );
	$captionHtml = find_first_html_tag ( $html, 'figcaption' );
	$linkHtml = find_first_html_tag ( $html, 'a' );
	$link = '';
	if ($linkHtml) {
		$link = convert_to_relative_url( $linkHtml['attributes']['href'] );
	}

	return array(
		'url'         => $url,
		'link'        => $link,
		'captionHtml' => $captionHtml ? $captionHtml['innerChildren'] : false
	);
}
?>
