<?php
/**
 * Theme for the Strawbees Headless WordPress Starter Kit.
 *
 * Read more about this project at:
 * https://postlight.com/trackchanges/introducing-postlights-wordpress-react-starter-kit
 *
 * @package  Learning CMS
 */

// Theme support
require_once 'inc/theme-support.php';

// Frontend origin.
require_once 'inc/frontend-origin.php';

// ACF commands.
require_once 'inc/class-acf-commands.php';

// Manage blocks.
require_once 'inc/blocks.php';

// Content Blocks in API
// require_once 'inc/content-blocks-in-api.php';

// Content Blocks in GraphQL
require_once 'inc/content-blocks-in-graphql.php';

// Logging functions.
require_once 'inc/log.php';

// CORS handling.
require_once 'inc/cors.php';

// Admin modifications.
require_once 'inc/admin.php';

// Add Menus.
require_once 'inc/menus.php';

// Add Headless Settings area.
require_once 'inc/acf-options.php';

// Register (or modify) custom post types.
require_once 'inc/post-types.php';
