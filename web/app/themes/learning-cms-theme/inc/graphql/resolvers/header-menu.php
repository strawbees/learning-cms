<?php
/**
 * Header Menu GraphQL resolver.
 *
 * @package Learning CMS
 */

use \WPGraphQL\Types;
use \WPGraphQL\Type\WPObjectType;

require_once __DIR__ . '/../types/class-headermenutype.php';

add_action(
	'graphql_register_types',
	function () {
		register_graphql_field(
			'RootQuery',
			'headerMenu',
			[
				'type'        => Types::list_of( new HeaderMenuType() ),
				'description' => __( 'Returns the header menu items', 'strawbees-headless-wp' ),
				'resolve'     => function () {
					return get_items( 'Header Menu' );
				},
			]
		);
	}
);
