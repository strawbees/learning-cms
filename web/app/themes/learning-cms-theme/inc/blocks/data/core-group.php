<?php
function get_block_data_core_group( $block ) {
	$html = convert_html_to_object( $block['innerHTML'] );
	$variant = resolve_attrs_classname_variant( $block['attrs'] );
	return array(
		'variant'         => $variant,
		'sizeFormat'      => resolve_attrs_size_format( $block['attrs'] ),
		'anchor'          => resolve_html_attribute( $html, 'id' ),
		'backgroundColor' => resolve_attrs_color( $block['attrs'], 'backgroundColor', 'customBackgroundColor' ),
	);
}
?>
