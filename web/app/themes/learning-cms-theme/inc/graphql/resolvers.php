<?php
/**
 * Add GraphQL resolvers
 *
 * @package  Learning CMS
 */

// check if WPGraphQL plugin is active.
if ( function_exists( 'register_graphql_field' ) ) {
	// Add header menu resolver.
	require_once 'resolvers/menu.php';
}
