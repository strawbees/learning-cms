<?php
function get_block_data_core_button( $block ) {
	$html = convert_html_to_object( $block['innerHTML'] );
	$a = find_first_html_tag ( $html, 'a' );
	$link = '';
	$innerHTML = '';
	if ($a) {
		$link = resolve_html_attribute( $a, 'href' );
		$innerHTML = resolve_html_children( $a );
	}

	$variant = resolve_attrs_classname_variant( $block['attrs'] );;
	$icon = null;
	if ( $variant ) {
		if ( substr( $variant, 0, 5 ) === 'icon-' ) {
			$icon = str_replace( 'icon-', '', $variant );
			$variant = 'icon';
		}
	}

	return array(
		'variant'         => $variant,
		'icon'            => $icon,
		'textAlign'       => resolve_attrs_prop( $block['attrs'], 'align', 'left' ),
		'textColor'       => resolve_attrs_color( $block['attrs'], 'textColor', 'customTextColor' ),
		'backgroundColor' => resolve_attrs_color( $block['attrs'], 'backgroundColor', 'customBackgroundColor' ),
		'link'            => $link,
		'innerHTML'       => $innerHTML
	);
}
?>
