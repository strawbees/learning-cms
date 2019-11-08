<?php
/**
 * Footer Menu Type used for GraphQL.
 *
 * @package Learning CMS
 */

use \WPGraphQL\Types;
use \WPGraphQL\Type\WPObjectType;

/**
 * Footer menu type class that extends WPObjectType
 */
class FooterMenuType extends WPObjectType {
	/**
	 * Graphql Fields
	 *
	 * @var $fields FooterMenuType fields
	 */
	private static $fields;

	/**
	 * Constructor
	 */
	public function __construct() {
		$config = [
			'name'        => 'FooterMenuType',
			'fields'      => self::fields(),
			'description' => __( 'Footer Menu', 'strawbees-headless-wp' ),
		];
		parent::__construct( $config );
	}

	/**
	 * Fields generator
	 */
	protected static function fields() {
		if ( null === self::$fields ) {
			self::$fields = function () {
				$fields = [
					'label' => [
						'type'        => Types::string(),
						'description' => __( 'The URL label', 'strawbees-headless-wp' ),
					],
					'url'   => [
						'type'        => Types::string(),
						'description' => __( 'The URL', 'strawbees-headless-wp' ),
					],
					'type'  => [
						'type'        => Types::string(),
						'description' => __( 'internal or external', 'strawbees-headless-wp' ),
					],
				];
				return self::prepare_fields( $fields, 'FooterMenuType' );
			};
		}
		return ! empty( self::$fields ) ? self::$fields : null;
	}
}
