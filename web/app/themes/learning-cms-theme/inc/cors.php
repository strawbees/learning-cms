<?php
/**
 * REST API CORS filter.
 *
 * @package  Learning CMS
 */

/**
 * Allow GET requests from origin
 * Thanks to https://joshpress.net/access-control-headers-for-the-wordpress-rest-api/
 */
// add_action(
// 	'rest_api_init',
// 	function () {
// 		remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
// 
// 		add_filter(
// 			'rest_pre_serve_request',
// 			function ( $value ) {
// 				header( 'Access-Control-Allow-Origin: ' . '*' );
// 				header( 'Access-Control-Allow-Methods: GET' );
// 				header( 'Access-Control-Allow-Credentials: true' );
// 				return $value;
// 			}
// 		);
// 	},
// 	15
// );

/**
 * Allow for nonce on GraphQL (used for revisions previews)
 */
//add_filter( 'graphql_access_control_allow_headers', function( $headers ) {
//	return array_merge( $headers, [ 'X-WP-Nonce' ] );
//});
//add_filter( 'graphql_response_headers_to_send', function( $headers ) {
//	return array_merge( $headers, [
//		'Access-Control-Allow-Origin'  =>  '*',
//		'Access-Control-Allow-Credentials' => 'true'
//	] );
//} );
