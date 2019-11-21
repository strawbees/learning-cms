<?php
function resolve_html_attribute( $html, $key, $default = null ) {
	if ( !$html ||
		!isset($html['attributes']) ||
		!$html['attributes'] ||
		!isset($html['attributes'][$key]) ||
		!$html['attributes'][$key] ) {
		return $default;
	}
	return $html['attributes'][$key];
}
