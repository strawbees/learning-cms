<?php
function sb_clean_blocks ( $blocks ) {
	return array_values(
		array_map(
			function ( $block ) {
				$get_block_data = 'get_block_data_' . preg_replace( '~[^\pL\d]+~u', '_', $block['blockName'] );
				if (function_exists($get_block_data)) {
					$block['data'] = $get_block_data( $block );
				}
				$block['innerBlocks'] = sb_clean_blocks( $block['innerBlocks'] );
				$block['innerHTMLParsed'] = html_to_object( $block['innerHTML'] );
				unset( $block['innerContent'] );
				unset( $block['innerHTML'] );
				return $block;
			},
			array_filter(
				$blocks,
				function( $block ) {
					return $block['blockName'];
				}
			)
		)
	);
}
function parse_content_to_blocks( $post_content ) {
	$blocks = parse_blocks($post_content);
	// this step is necesary to render ACF blocks
	foreach ($blocks as $key => $block) {
		$blocks[$key]['innerHTML'] = render_block($block);
	}
	return sb_clean_blocks($blocks);
};
?>
