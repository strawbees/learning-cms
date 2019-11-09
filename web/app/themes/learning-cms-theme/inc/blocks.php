<?php
/**
 * Register custom blocks.
 *
 * @package  Learning CMS
 */

function register_acf_block_types() {
	acf_register_block_type(array(
		'name'              => 'posts-list',
		'title'             => __('Posts list', 'strawbees-headless-wp'),
		'description'       => __('A list of posts.', 'strawbees-headless-wp'),
		'render_template'   => 'blocks/posts-list.php',
		'category'          => 'embed'
	));
}
if( function_exists('acf_register_block_type') ) {
	add_action('acf/init', 'register_acf_block_types');
}

function sb_allowed_block_types( $allowed_block_types, $post ) {
	// list of all blocks was retrived by typing:
	// wp.data.select( 'core/blocks' ).getBlockTypes().forEach(({name}) => console.log(`'${name}',`))
	// in the console, while in the post editor page in the admin
	return array(
		'acf/posts-list',
		// 'core-embed/amazon-kindle',
		// 'core-embed/animoto',
		// 'core-embed/cloudup',
		// 'core-embed/collegehumor',
		// 'core-embed/crowdsignal',
		// 'core-embed/dailymotion',
		// 'core-embed/facebook',
		// 'core-embed/flickr',
		// 'core-embed/hulu',
		// 'core-embed/imgur',
		// 'core-embed/instagram',
		// 'core-embed/issuu',
		// 'core-embed/kickstarter',
		// 'core-embed/meetup-com',
		// 'core-embed/mixcloud',
		// 'core-embed/polldaddy',
		// 'core-embed/reddit',
		// 'core-embed/reverbnation',
		// 'core-embed/screencast',
		// 'core-embed/scribd',
		// 'core-embed/slideshare',
		// 'core-embed/smugmug',
		// 'core-embed/soundcloud',
		// 'core-embed/speaker',
		// 'core-embed/speaker-deck',
		// 'core-embed/spotify',
		// 'core-embed/ted',
		// 'core-embed/tumblr',
		// 'core-embed/twitter',
		// 'core-embed/videopress',
		// 'core-embed/vimeo',
		// 'core-embed/wordpress',
		// 'core-embed/wordpress-tv',
		'core-embed/youtube',
		// 'core/archives',
		// 'core/audio',
		'core/block',
		// 'core/button',
		// 'core/calendar',
		// 'core/categories',
		// 'core/code',
		'core/column',
		'core/columns',
		'core/cover',
		// 'core/embed',
		// 'core/file',
		// 'core/freeform',
		'core/gallery',
		'core/group',
		'core/heading',
		// 'core/html',
		'core/image',
		// 'core/latest-comments',
		'core/latest-posts',
		'core/list',
		// 'core/media-text',
		// 'core/missing',
		// 'core/more',
		// 'core/nextpage',
		'core/paragraph',
		// 'core/preformatted',
		// 'core/pullquote',
		// 'core/quote',
		// 'core/rss',
		// 'core/search',
		'core/separator',
		// 'core/shortcode',
		// 'core/social-link-amazon',
		// 'core/social-link-bandcamp',
		// 'core/social-link-behance',
		// 'core/social-link-chain',
		// 'core/social-link-codepen',
		// 'core/social-link-deviantart',
		// 'core/social-link-dribbble',
		// 'core/social-link-dropbox',
		// 'core/social-link-etsy',
		// 'core/social-link-facebook',
		// 'core/social-link-feed',
		// 'core/social-link-fivehundredpx',
		// 'core/social-link-flickr',
		// 'core/social-link-foursquare',
		// 'core/social-link-github',
		// 'core/social-link-goodreads',
		// 'core/social-link-google',
		// 'core/social-link-instagram',
		// 'core/social-link-lastfm',
		// 'core/social-link-linkedin',
		// 'core/social-link-mail',
		// 'core/social-link-mastodon',
		// 'core/social-link-medium',
		// 'core/social-link-meetup',
		// 'core/social-link-pinterest',
		// 'core/social-link-pocket',
		// 'core/social-link-reddit',
		// 'core/social-link-skype',
		// 'core/social-link-snapchat',
		// 'core/social-link-soundcloud',
		// 'core/social-link-spotify',
		// 'core/social-link-tumblr',
		// 'core/social-link-twitch',
		// 'core/social-link-twitter',
		// 'core/social-link-vimeo',
		// 'core/social-link-vk',
		// 'core/social-link-wordpress',
		// 'core/social-link-yelp',
		// 'core/social-link-youtube',
		// 'core/social-links',
		// 'core/spacer',
		// 'core/subhead',
		// 'core/table',
		// 'core/tag-cloud',
		// 'core/text-columns',
		// 'core/verse',
		// 'core/video',
	);
}
add_filter( 'allowed_block_types', 'sb_allowed_block_types', 10, 2 );
