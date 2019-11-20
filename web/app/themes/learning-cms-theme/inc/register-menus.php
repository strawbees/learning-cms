<?php
/**
 * Register main menu.
 *
 * @package  Learning CMS
 */

/**
 * Register navigation menu.
 *
 * @return void
 */

function register_custom_nav_menus() {
	register_nav_menus( array(
		'header-menu' => __( 'Header Menu', 'strawbees-headless-wp' ),
		'footer-menu' => __( 'Footer Menu', 'strawbees-headless-wp' ),
	) );
}
add_action( 'after_setup_theme', 'register_custom_nav_menus' );
