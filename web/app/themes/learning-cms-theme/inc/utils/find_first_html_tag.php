<?php
function find_first_html_tag ($html, $tag, $default = null ) {
	if ($default === null) {
		$default = (object) null;
	}
	if (!$html || !isset($html['tag']) || !$html['tag']) {
		return $defalt;
	}
	if ($html['tag'] === $tag) {
		return $html;
	}
	if (!isset( $html['innerChildren'] ) || !$html['innerChildren'] || !count( $html['innerChildren'] )) {
		return $defalt;
	}
	foreach ($html['innerChildren'] as $child) {
		$child_tag = find_first_html_tag( $child, $tag );
		if ($child_tag) {
			return $child_tag;
		}
	}
	return $defalt;
}
