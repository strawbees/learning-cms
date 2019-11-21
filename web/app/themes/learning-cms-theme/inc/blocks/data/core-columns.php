<?php
function get_block_data_core_columns( $block ) {
	return array(
		'sizeFormat' => resolve_attrs_size_format( $block['attrs'] ),
	);
}
?>
