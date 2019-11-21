<?php
function get_block_data_core_paragraph($block) {
	$html = convert_html_to_object( $block['innerHTML'] );
	$align = isset( $block['attrs']['align'] ) ? $block['attrs']['align'] : 'left';
	$textColor = isset( $block['attrs']['textColor'] ) ? $block['attrs']['textColor'] : '';
	$customTextColor = isset( $block['attrs']['customTextColor'] ) ? $block['attrs']['customTextColor'] : '';
	$backgroundColor = isset( $block['attrs']['backgroundColor'] ) ? $block['attrs']['backgroundColor'] : '';
	$customBackgroundColor = isset( $block['attrs']['customBackgroundColor'] ) ? $block['attrs']['customBackgroundColor'] : '';
	return array(
		'align'           => $align,
		'textColor'       => $customTextColor ? $customTextColor : $textColor,
		'backgroundColor' => $customBackgroundColor ? $customBackgroundColor : $backgroundColor,
		'innerHTML'       => $html['innerChildren']
	);
}
?>
