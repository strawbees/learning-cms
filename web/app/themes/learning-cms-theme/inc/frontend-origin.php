<?php
/**
 * Frontend origin helper function.
 *
 * @package  Learning CMS
 */

function get_frontend_origin() {
	$default = 'http://localhost:3000';
	if ( !function_exists( 'graphql' ) ) {
		return $default;
	}
	$graphql = graphql(['query' =>'
		query {
			configurations(first: 1) {
				nodes {
					configuration {
						frontendUrl
					}
				}
			}
		}
	']);
	$url = @$graphql['data']['configurations']['nodes'][0]['configuration']['frontendUrl'];
	if ($url) {
		return $url;
	}

	return $default;
}
