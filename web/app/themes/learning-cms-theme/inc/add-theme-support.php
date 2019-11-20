<?php
/**
 * Add specific theme supports,
 *
 * @package  Learning CMS
 */
add_theme_support( 'post-thumbnails' );
add_theme_support( 'align-wide' );
add_theme_support( 'disable-custom-font-sizes' );
add_theme_support( 'editor-font-sizes', array(
	array(
		'name' => __( 'Small', 'strawbees-headless-wp' ),
		'size' => 12,
		'slug' => 'small'
	),
	array(
		'name' => __( 'Normal', 'strawbees-headless-wp' ),
		'size' => 16,
		'slug' => 'normal'
	),
	array(
		'name' => __( 'Medium', 'strawbees-headless-wp' ),
		'size' => 24,
		'slug' => 'medium'
	),
	array(
		'name' => __( 'Large', 'strawbees-headless-wp' ),
		'size' => 32,
		'slug' => 'large'
	),
	array(
		'name' => __( 'Huge', 'strawbees-headless-wp' ),
		'size' => 48,
		'slug' => 'huge'
	)
) );
add_theme_support(
	'editor-color-palette', array(
		array(
			'name'  => esc_html__( 'Red', 'strawbees-headless-wp' ),
			'slug' => 'red',
			'color' => 'red',
		),
		array(
			'name'  => esc_html__( 'Green', 'strawbees-headless-wp' ),
			'slug' => 'green',
			'color' => 'green',
		),
		array(
			'name'  => esc_html__( 'Yellow', 'strawbees-headless-wp' ),
			'slug' => 'yellow',
			'color' => 'rgb(255,255,0)',
		)
	)
);
