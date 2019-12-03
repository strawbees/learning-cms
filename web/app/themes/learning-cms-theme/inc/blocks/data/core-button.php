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

	$variant = resolve_attrs_classname_variant( $block['attrs'] );
	$icon = null;
	if ( $variant ) {
		if ( substr( $variant, 0, 5 ) === 'icon-' ) {
			$icon = str_replace( 'icon-', '', $variant );
		}
	}

	$textAlign = resolve_attrs_prop( $block['attrs'], 'align', 'left' );

	$editorOptions = get_theme_support( 'editor-color-palette' )[0];
	$white = null;
	$black = null;
	foreach ( $editorOptions as $option ) {
		if ( $option['slug'] === 'white' ) {
			$white = $option['color'];
		}
		if ( $option['slug'] === 'black' ) {
			$black = $option['color'];
		}
	}
	$textColor = resolve_attrs_color( $block['attrs'], 'textColor', 'customTextColor' );
	$backgroundColor = resolve_attrs_color( $block['attrs'], 'backgroundColor', 'customBackgroundColor' );
	$outlineColor = 'rgba(0,0,0,0)';

	$class = resolve_attrs_prop( $block['attrs'], 'className' );
	// default colors with / without outline
	if ( strpos( $class, 'is-style-outline' ) !== false ) {
		if (!$backgroundColor) {
			$backgroundColor = 'rgba(0,0,0,0)';
		}
		if (!$textColor) {
			$textColor = $black;
		}
		$outlineColor = $textColor;
	} else {
		if (!$backgroundColor) {
			$backgroundColor = $black;
		}
		if (!$textColor) {
			$textColor = $white;
		}
	}

	return array(
		'icon'            => $icon,
		'textAlign'       => $textAlign,
		'textColor'       => $textColor,
		'outlineColor'    => $outlineColor,
		'backgroundColor' => $backgroundColor,
		'link'            => $link,
		'innerHTML'       => $innerHTML
	);
}
?>
