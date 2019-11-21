<?php
function get_block_data_core_group( $block ) {
	return array(
		'sizeFormat'      => resolve_attrs_size_format( $block['attrs'] ),
		'anchor'          => resolve_html_attribute( $html, 'id' ),
		'backgroundColor' => resolve_attrs_color( $block['attrs'], 'backgroundColor', 'customBackgroundColor' ),
	);
}
?>
