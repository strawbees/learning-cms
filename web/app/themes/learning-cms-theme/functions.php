<?php
/**
 * Theme for the Strawbees Headless WordPress.
 * @package  Learning CMS
 */
foreach ( glob(  __DIR__ . '/inc/utils/*.php' ) as $filename ){
	require_once $filename;
}
require_once 'inc/add-theme-support.php';
require_once 'inc/register-blocks.php';
require_once 'inc/extend-api.php';
require_once 'inc/extend-graphql.php';
require_once 'inc/customize-admin.php';
require_once 'inc/register-menus.php';
require_once 'inc/register-post-types.php';
