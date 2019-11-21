<?php
function get_block_data_core_heading($block) {
	$html = convert_html_to_object( $block['innerHTML'] );
	$align = isset( $block['attrs']['align'] ) ? $block['attrs']['align'] : 'left';
	$level = isset( $block['attrs']['level'] ) ? $block['attrs']['level'] : 2;
	$textColor = isset( $block['attrs']['textColor'] ) ? $block['attrs']['textColor'] : '';
	$customTextColor = isset( $block['attrs']['customTextColor'] ) ? $block['attrs']['customTextColor'] : '';
	return array(
		'align'     => $align,
		'level'     => $level,
		'textColor' => $customTextColor ? $customTextColor : $textColor,
		'innerHTML' => $html['innerChildren']
	);
}
?>
