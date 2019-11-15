<?php
// add parsed blocks to api response
function sb_html_to_obj( $html ) {
	$dom = new DOMDocument();
	libxml_use_internal_errors(true);
	$dom->loadHTML( $html );
	libxml_clear_errors();
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
				$block['innerHTMLParsed'] = sb_html_to_obj( $block['innerHTML'] )['children'][0]['children'];
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

add_action( 'graphql_register_types', function() {
	$post_types = \WPGraphQL::get_allowed_post_types();

	if ( ! empty( $post_types ) && is_array( $post_types ) ) {
		foreach ( $post_types as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );
			register_graphql_field( $post_type_object->graphql_single_name, 'blocksRaw', [
				'type' => 'String',
				'description' => __( 'The raw block content of the post.', 'strawbees-headless-wp' ),
				'resolve' => function( $post ) {
					$content_post = get_post($post->ID);
					$content_raw = $content_post->post_content;
					return json_encode(sb_clean_blocks(parse_blocks($content_raw)));
				}
			] );
		}
	}
});
?>
