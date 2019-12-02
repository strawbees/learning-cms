<?php
function sb_clean_blocks ( $blocks ) {
	return array_values(
		array_map(
			function ( $block ) {
				$get_block_data = 'get_block_data_' . preg_replace( '~[^\pL\d]+~u', '_', $block['blockName'] );
				return array (
					'name'        => $block['blockName'],
					'data'        => function_exists($get_block_data) ? $get_block_data( $block ) : (object) null,
					'innerBlocks' => sb_clean_blocks( $block['innerBlocks'] )
				);
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
