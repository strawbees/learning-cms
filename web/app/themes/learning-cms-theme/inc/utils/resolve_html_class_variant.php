<?php
function resolve_html_class_variant( $html, $default = null) {
	$class = resolve_html_attribute( $html, 'class');
	if ($class === null) {
		return $default;
	}
	$variant = $class;
	$variant = filter_classes_starting_with( $variant, 'is-', $default);
	$variant = filter_classes_starting_with( $variant, 'has-', $default);
	$variant = filter_classes_starting_with( $variant, 'wp-', $default);
	return $variant;
}
