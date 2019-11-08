<?php
/**
 * Add ACF options page.
 *
 * @package  Learning CMS
 */

// Add a custom options page to associate ACF fields.
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page(
		[
			'menu_title' => 'Configuration',
			'page_title' => 'Configuration',
			'menu_slug'  => 'configuration',
			'capability' => 'manage_options',
			'post_id'    => 'configuration',
			'redirect'   => false,
		]
	);
}
