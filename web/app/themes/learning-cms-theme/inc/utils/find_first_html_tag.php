<?php
function find_first_html_tag ($html, $tag) {
	if (!$html || !isset($html['tag']) || !$html['tag']) {
		return false;
	}
	if ($html['tag'] === $tag) {
		return $html;
	}
	if (!isset( $html['innerChildren'] ) || !$html['innerChildren'] || !count( $html['innerChildren'] )) {
		return false;
	}
	foreach ($html['innerChildren'] as $child) {
		$child_tag = find_first_html_tag( $child, $tag );
		if ($child_tag) {
			return $child_tag;
		}
	}
	return false;
}
