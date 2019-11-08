<?php
/**
 * Menu GraphQL resolver.
 *
 * @package Learning CMS
 */

use \WPGraphQL\Types;
use \WPGraphQL\Type\WPObjectType;

/**
 * Get header menu items
 */
function get_items($menu_name) {
	$counter    = 0;
	$menu_items = wp_get_nav_menu_items( $menu_name );
	foreach ( $menu_items as $item ) {
		$url_arr = explode( '/', $item->url );
		$slug    = $url_arr[ count( $url_arr ) - 2 ];

		$resolve[ $counter ]['label'] = $item->title;
		$resolve[ $counter ]['type']  = 'internal';
		switch ( $item->object ) {
			case 'post':
				$resolve[ $counter ]['url'] = '/post/' . $slug;
				break;
			case 'category':
				$resolve[ $counter ]['url'] = '/category/' . $slug;
				break;
			case 'page':
				$resolve[ $counter ]['url'] = '/page/' . $slug;
				break;
			case 'custom':
				$resolve[ $counter ]['url']  = $item->url;
				$resolve[ $counter ]['type'] = 'external';
				break;
			default:
				break;
		}
		$counter++;
	}

	return $resolve;
}


require_once __DIR__ . '/header-menu.php';
require_once __DIR__ . '/footer-menu.php';
