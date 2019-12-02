<?php
function get_block_data_core_list( $block ) {
	$html = convert_html_to_object( $block['innerHTML'] );
	return array(
		'$block' => $block,
		'html'  => $html ? $html  : (object) null
	);
}
?>
