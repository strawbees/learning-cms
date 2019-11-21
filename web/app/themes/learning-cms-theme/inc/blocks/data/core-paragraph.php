<?php
function get_block_data_core_paragraph( $block ) {
	$html = convert_html_to_object( $block['innerHTML'] );
	return array(
		'variant'         => resolve_html_class_variant( $html ),
		'textAlign'       => resolve_attrs_prop( $block['attrs'], 'align', 'left' ),
		'textColor'       => resolve_attrs_color( $block['attrs'], 'textColor', 'customTextColor' ),
		'backgroundColor' => resolve_attrs_color( $block['attrs'], 'backgroundColor', 'customBackgroundColor' ),
		'innerHTML'       => resolve_html_children( $html ),
	);
}
?>
