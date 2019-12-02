<?php
function get_block_data_core_column( $block ) {
	return array(
		'verticalAlignment' => resolve_attrs_prop( $block['attrs'], 'verticalAlignment', 'top' ),
		'width'             => resolve_attrs_prop( $block['attrs'], 'width', 100 ),
	);
}
?>
