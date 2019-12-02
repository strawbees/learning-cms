<?php
function get_block_data_core_separator( $block ) {
	return array(
		'color' => resolve_attrs_color( $block['attrs'], 'color', 'customColor', 'black' ),
	);
}
?>
