<?php
function convert_html_to_object( $html, $default = null ) {
	if ($default === null) {
		$default = null;
	}
	if (!$html) {
		return $default;
	}
	$dom = new DOMDocument( '1.0', 'utf-8' );
	libxml_use_internal_errors(true);
	$dom->loadHTML( mb_convert_encoding( $html, 'HTML-ENTITIES', 'UTF-8' ) );
	libxml_clear_errors();
	if (!$dom->documentElement) {
		return $default;
	}

	$obj = convert_element_to_object( $dom->documentElement );
	if ($obj &&
		$obj['innerChildren'] &&
		$obj['innerChildren'][0] &&
		$obj['innerChildren'][0]['innerChildren'] &&
		$obj['innerChildren'][0]['innerChildren'][0]){
		return $obj['innerChildren'][0]['innerChildren'][0];
	}
	return $default;
}

function convert_element_to_object( $element ) {

	if ( get_class($element) !== 'DOMElement' ) {
		return null;
	}
	$obj = array();
	$obj['tag'] = $element->tagName;
	$obj['attributes'] = array();
	foreach ( $element->attributes as $attribute ) {
		$obj['attributes'][$attribute->name] = $attribute->value ? $attribute->value : true;

		if ($obj['tag'] === 'a' && $attribute->name === 'href') {
			$obj['attributes'][$attribute->name] = convert_to_relative_url( $obj['attributes'][$attribute->name] );
		} else if ($obj['tag'] && $attribute->name === 'src') {
			$obj['attributes'][$attribute->name] = convert_to_cdn_url( $obj['attributes'][$attribute->name] );
		}

	}
	if( empty( $obj['attributes'] ) ) {
		unset( $obj['attributes'] );
	}
	foreach ( $element->childNodes as $subElement ) {
		if ( $subElement->nodeType == XML_TEXT_NODE ) {
			$obj['innerChildren'][] = array(
				'tag' => 'text',
				'text' => $subElement->wholeText
			);
		}
		else if ( $subElement->nodeName == '#cdata-section' ) {
			$obj['innerChildren'][] = array(
				'tag' => 'cdata',
				'data' => $subElement->wholeText
			);
		}
		else {
			$obj['innerChildren'][] = convert_element_to_object($subElement);
		}
	}
	return $obj;
}
?>
