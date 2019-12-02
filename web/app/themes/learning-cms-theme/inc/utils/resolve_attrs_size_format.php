<?php
function resolve_attrs_size_format ( $attrs, $default = 'normal' ) {
	$align = resolve_attrs_prop( $attrs, 'align', $default );
	if ( !$align ||
		$align === null ||
		$align === 'center' ||
		$align === 'left' ||
		$align === 'right') {
		$align = $default;
	}
	return $align;
}
