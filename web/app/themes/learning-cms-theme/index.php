<?php
global $wp_query;

$obj = $wp_query->queried_object;
$res = array();
if (!$obj || !isset($obj->post_type)) {
	return wp_send_json($res);
}
$res['ID'] = $obj->ID;
$res['post_type'] = $obj->post_type;
$res['acl'] = get_field('acl', $obj->ID);

return wp_send_json($res);
?>
