<?php
acf_register_block_type(array(
	'name'              => 'file-download',
	'mode'              => 'auto',
	'supports'          => array(
		'align'           => false,
		'customClassName' => false,
	),
	'title'             => __('File download', 'strawbees-headless-wp'),
	'description'       => __('A file that can be downloaded.', 'strawbees-headless-wp'),
	'category'          => 'common',
	'render_callback'   => function ( $block, $content = '', $is_preview = false, $post_id = 0 ) {
		$file_media_url = get_field('file_media_url');
		$file_external_url = get_field('file_external_url');
		$file_name = get_field('file_name');
		$file_extension = get_field('file_extension');
		$download_button_label = get_field('download_button_label');

		$data = array(
			'title' => $download_button_label ? $download_button_label : $file_name,
			'extension' => $file_extension,
			'url' => $file_external_url ? $file_external_url : $file_media_url,
		);
		?>
		<div style="background-color:#fff8be;">
			<h5>File Download</h5>
			<?php echo sprintf('%s.%s', $data['title'], $data['extension']); ?>
		</div>
		<?php
	}
));
?>
