<?php
function sb_clean_blocks ( $blocks, $post_id = null ) {

	return array_values(
		array_map(
			function ( $block ) use ( $post_id ){
				$get_block_data = 'get_block_data_' . preg_replace( '~[^\pL\d]+~u', '_', $block['blockName'] );
				return array (
					'name'        => $block['blockName'],
					'data'        => function_exists($get_block_data) ? $get_block_data( $block, $post_id ) : (object) null,
					'innerBlocks' => sb_clean_blocks( $block['innerBlocks'], $post_id )
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
function parse_content_to_blocks( $post_content, $post_id ) {
	$blocks = parse_blocks( $post_content );
	// this step is necesary to render ACF blocks
	foreach ($blocks as $key => $block) {
		$blocks[$key]['innerHTML'] = render_block( $block );
	}
	return sb_clean_blocks( $blocks, $post_id );
};
?>
