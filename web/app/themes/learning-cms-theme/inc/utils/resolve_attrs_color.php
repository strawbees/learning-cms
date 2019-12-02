<?php
function resolve_attrs_color ( $attrs, $key = 'color', $customKey = 'customColor', $default = null ) {
	$editorOptions = get_theme_support( 'editor-color-palette' )[0];
	$resolved = $default;
	if ( isset( $attrs[$key] ) ) {
		$editor = null;
		foreach ( $editorOptions as $option ) {
			if ($option['slug'] === $attrs[$key] ) {
				$editor = $option['color'];
			};
		}
		if ($editor !== null) {
			$resolved = $editor;
		}
	} else if ( isset( $attrs[$customKey] ) ) {
		$resolved = $attrs[$customKey];
	}
	return $resolved;
}
