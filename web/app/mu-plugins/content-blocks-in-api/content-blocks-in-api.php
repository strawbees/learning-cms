<?php
/*
Plugin Name: Content blocks in API
Description: Adds a "blocks" field to the JSON API api results, with the parsed blocks content.
Version:     0.0.0
Author:      Strawbees
*/
// add parsed blocks to api response
function sb_html_to_obj( $html ) {
	$dom = new DOMDocument();
	$dom->loadHTML( $html );
	return sb_element_to_obj( $dom->documentElement );
}
function sb_element_to_obj( $element ) {
	$obj = array();
	$obj['tag'] = $element->tagName;
	$obj['attributes'] = array();
	foreach ( $element->attributes as $attribute ) {
		$obj['attributes'][$attribute->name] = $attribute->value;
	}
	if( empty( $obj['attributes'] ) ) {
		unset( $obj['attributes'] );
	}
	foreach ( $element->childNodes as $subElement ) {
		if ( $subElement->nodeType == XML_TEXT_NODE ) {
			$obj['children'][] = array(
				'tag' => 'text',
				'text' => $subElement->wholeText
			);
		}
		else {
			$obj['children'][] = sb_element_to_obj($subElement);
		}
	}
	return $obj;
}
function sb_clean_blocks ( $blocks ) {
	return array_values(
		array_map(
			function ( $block ) {
				unset( $block['innerContent'] );
				$block['innerBlocks'] = sb_clean_blocks( $block['innerBlocks'] );
				$block['innerHTML'] = sb_html_to_obj( $block['innerHTML'] )['children'][0]['children'];
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
add_action( 'rest_api_init', function () {
	$custom_post_types = get_post_types( array(
		'public'   => true,
		'_builtin' => false
	), 'names', 'and' );
	register_rest_field(
		array_merge( array( 'page', 'post' ), $custom_post_types ),
		'blocks',
		array(
			'get_callback' => function( $post ) {
				if (isset( $post ) &&
					isset( $post['content'] ) &&
					isset( $post['content']['raw'] )) {
					return sb_clean_blocks( parse_blocks( $post['content']['raw'] ) );
				}
				return array();
			},
			'schema' => array(
				'description' => __( 'Content blocks.' ),
				'type'        => 'json'
			)
		)
	);
});
?>
