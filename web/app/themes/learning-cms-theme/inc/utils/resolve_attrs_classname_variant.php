<?php
function resolve_attrs_classname_variant( $attrs, $default = null) {
	$class = resolve_attrs_prop( $attrs, 'className' );
	$variant = null;
	if ( $class !== '' ) {
		$variant = $class;
		$variant = filter_classes_starting_with( $variant, 'is-');
		$variant = filter_classes_starting_with( $variant, 'has-');
		$variant = filter_classes_starting_with( $variant, 'wp-');
	}
	return $variant;
}
