<?php
function filter_classes_starting_with( $class, $query, $default = null) {
	$class_array = explode(' ', $class);
	$class_array = array_filter( $class_array, function( $part ) use ($query) {
		return substr( $part, 0, strlen( $query ) ) !== $query;
	});
	$variant = join(' ', $class_array );
	if ($variant === '') {
		return $default;
	}
	return $variant;
}
