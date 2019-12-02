<?php
function get_block_data_core_heading( $block ) {
	$html = convert_html_to_object( $block['innerHTML'] );
	return array(
		'variant'   => resolve_html_class_variant( $html ),
		'anchor'    => resolve_html_attribute( $html, 'id' ),
		'textAlign' => resolve_attrs_prop( $block['attrs'], 'align', 'left' ),
		'level'     => resolve_attrs_prop( $block['attrs'], 'level', 2 ),
		'textColor' => resolve_attrs_color( $block['attrs'], 'textColor', 'customTextColor' ),
		'innerHTML' => resolve_html_children( $html ),
	);
}
?>
