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
));
add_theme_support( 'editor-color-palette', array(
	array(
		'name'  => esc_html__( 'Transparent', 'strawbees-headless-wp' ),
		'slug' => 'transparent',
		'color' => 'rgba(0,0,0,0)',
	),
	array(
		'name'  => esc_html__( 'White', 'strawbees-headless-wp' ),
		'slug' => 'white',
		'color' => '#FFFFFF',
	),
	array(
		'name'  => esc_html__( 'Light Grey', 'strawbees-headless-wp' ),
		'slug' => 'lightGrey',
		'color' => '#EBEBEB',
	),
	array(
		'name'  => esc_html__( 'Dark Grey', 'strawbees-headless-wp' ),
		'slug' => 'darkGrey',
		'color' => '#525252',
	),
	array(
		'name'  => esc_html__( 'Black', 'strawbees-headless-wp' ),
		'slug' => 'black',
		'color' => '#303030',
	),
	array(
		'name'  => esc_html__( 'Blue', 'strawbees-headless-wp' ),
		'slug' => 'blue',
		'color' => '#00ADEE',
	),
	array(
		'name'  => esc_html__( 'Green', 'strawbees-headless-wp' ),
		'slug' => 'green',
		'color' => '#00A99D',
	),
	array(
		'name'  => esc_html__( 'Pink', 'strawbees-headless-wp' ),
		'slug' => 'pink',
		'color' => '#FF7381',
	),
	array(
		'name'  => esc_html__( 'Yellow', 'strawbees-headless-wp' ),
		'slug' => 'yellow',
		'color' => '#FEB702',
	),
	array(
		'name'  => esc_html__( 'White (75% transparent)', 'strawbees-headless-wp' ),
		'slug' => 'white-alpha-75',
		'color' => 'rgba(255,255,255,0.75)',
	),
	array(
		'name'  => esc_html__( 'Light Grey (75% transparent)', 'strawbees-headless-wp' ),
		'slug' => 'lightGrey-alpha-75',
		'color' => 'rgba(235,235,235,0.75)',
	),
	array(
		'name'  => esc_html__( 'Dark Grey (75% transparent)', 'strawbees-headless-wp' ),
		'slug' => 'darkGrey-alpha-75',
		'color' => 'rgba(82,82,82,0.75)',
	),
	array(
		'name'  => esc_html__( 'Black (75% transparent)', 'strawbees-headless-wp' ),
		'slug' => 'black-alpha-75',
		'color' => 'rgba(48,48,48,0.75)',
	),
	array(
		'name'  => esc_html__( 'Blue (75% transparent)', 'strawbees-headless-wp' ),
		'slug' => 'blue-alpha-75',
		'color' => 'rgba(0,173,238,0.75)',
	),
	array(
		'name'  => esc_html__( 'Green (75% transparent)', 'strawbees-headless-wp' ),
		'slug' => 'green-alpha-75',
		'color' => 'rgba(0,169,157,0.75)',
	),
	array(
		'name'  => esc_html__( 'Pink (75% transparent)', 'strawbees-headless-wp' ),
		'slug' => 'pink-alpha-75',
		'color' => 'rgba(255,115,129,0.75)',
	),
	array(
		'name'  => esc_html__( 'Yellow (75% transparent)', 'strawbees-headless-wp' ),
		'slug' => 'yellow-alpha-75',
		'color' => 'rgb(254,183,2,0.75)',
	),
));
add_theme_support( '__experimental-editor-gradient-presets', array() );
