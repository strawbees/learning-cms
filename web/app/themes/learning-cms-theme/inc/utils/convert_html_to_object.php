<?php
function convert_html_to_object( $html ) {
	if (!$html) {
		return null;
	}
	$dom = new DOMDocument();
	libxml_use_internal_errors(true);
	$dom->loadHTML( $html );
	libxml_clear_errors();
	if (!$dom->documentElement) {
		return null;
	}
	$obj = convert_element_to_object( $dom->documentElement );
	if ($obj['innerChildren'] &&
		$obj['innerChildren'][0] &&
		$obj['innerChildren'][0]['innerChildren'] &&
		$obj['innerChildren'][0]['innerChildren'][0]){
		return $obj['innerChildren'][0]['innerChildren'][0];
	}
	return null;
}

function convert_element_to_object( $element ) {
	$obj = array();
	$obj['tag'] = $element->tagName;
	$obj['attributes'] = array();
	foreach ( $element->attributes as $attribute ) {
		$obj['attributes'][$attribute->name] = $attribute->value ? $attribute->value : true;
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
		else {
			$obj['innerChildren'][] = convert_element_to_object($subElement);
		}
	}
	return $obj;
}
?>
