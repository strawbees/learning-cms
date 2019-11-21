<?php
function resolve_attrs_prop ( $attrs, $key, $default = null ){
	if ( isset( $attrs[$key] ) && $attrs[$key] ) {
		return $attrs[$key];
	}
	return $default;
}
