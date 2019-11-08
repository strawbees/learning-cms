<?php
/**
 * Footer Menu GraphQL resolver.
 *
 * @package Learning CMS
 */

use \WPGraphQL\Types;
use \WPGraphQL\Type\WPObjectType;

require_once __DIR__ . '/../types/class-footermenutype.php';

add_action(
	'graphql_register_types',
	function () {
		register_graphql_field(
			'RootQuery',
			'footerMenu',
			[
				'type'        => Types::list_of( new FooterMenuType() ),
				'description' => __( 'Returns the footer menu items', 'strawbees-headless-wp' ),
				'resolve'     => function () {
					return get_items( 'Footer Menu' );
				},
			]
		);
	}
);
