<?php
function resolve_attrs_size ( $attrs, $key = 'fontSize', $customKey = 'customFontSize', $default = null ) {
	$editorOptions = get_theme_support( 'editor-font-sizes' )[0];
	$resolved = $default;
	if ( isset( $attrs[$key] ) ) {
		$editor = null;
		foreach ( $editorOptions as $option ) {
			if ($option['slug'] === $attrs[$key] ) {
				$editor = $option['size'];
			};
		}
		if ($editor !== null) {
			$resolved = $editor . 'px';
		}
	} else if ( isset( $attrs[$customKey] ) ) {
		$resolved = $attrs[$customKey] . 'px';
	}
	return $resolved;
}
