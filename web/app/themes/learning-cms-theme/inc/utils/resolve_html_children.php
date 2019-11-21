<?php
function resolve_html_children ( $html, $defaul = array() ) {
	if ( !$html || !isset($html['innerChildren']) || !$html['innerChildren'] ) {
		return $defaul;
	}
	return $html['innerChildren'];
}
