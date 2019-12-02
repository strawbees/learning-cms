<?php
function get_block_data_acf_file_download( $block ) {
	$data = resolve_attrs_prop($block['attrs'], 'data', array());
	$file_media_url = convert_to_cdn_url( wp_get_attachment_url( $data['file_media_url'] ) );
	$file_external_url = $data['file_external_url'];
	$file_name = $data['file_name'];
	$file_extension = $data['file_extension'];
	$download_button_label = $data['download_button_label'];
	return array(
		'title' => $download_button_label ? $download_button_label : $file_name,
		'extension' => $file_extension,
		'url' => $file_external_url ? $file_external_url : $file_media_url,
	);
}
?>
