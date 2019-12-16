<?php
function get_block_data_core_table( $block ) {
	$html = convert_html_to_object( $block['innerHTML'] );
	$table = find_first_html_tag ( $html, 'table' );
	return array(
		'innerHTML'    => $table ? $table : (object) null,
		// 'innerHTMLRaw' => $block['innerHTML'],
	);
}
?>
