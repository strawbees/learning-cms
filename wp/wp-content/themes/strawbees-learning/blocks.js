// ImageDisplay - core/image
// Gallery - core/gallery
// YoutubeDisplay - embed/youtube
// Card - TODO

//
// Downloads - TODO
// RelatedContent - TODO
// RelatedProduct - TODO
//
// Heading - core/heading
// Paragraph - core/paragraph
// List - core/list

( function( blocks, element, editor, data, components ) {
		var el = element.createElement
		var registerBlockType = blocks.registerBlockType
		var withSelect = data.withSelect
		var CheckboxControl = components.CheckboxControl
		var Spinner = components.Spinner

		var postItem = function(post, props) {
			let postAlreadyExists = props.attributes.related.find(function(related) {
				return related.id === post.id
			})
			var state = element.useState(!!postAlreadyExists)
			var setChecked = function(checked) {
				state[1](checked)
				if (postAlreadyExists && !checked) {
					props.setAttributes({
						related: props.attributes.related.filter(function(related) {
							return related.id !== post.id
						})
					})
				}
				if (!postAlreadyExists && checked) {
					props.setAttributes({
						related: [
							post,
							...props.attributes.related
						]
					})
				}
			}
			return el(
				CheckboxControl,
				{
					label: post.title.raw,
					checked: state[0],
					onChange: setChecked
				}
			)
		}
		var selectAllPosts = function(select) {
			var posts = select( 'core' ).getEntityRecords( 'postType', 'post' ) || []
			posts = posts.map(function(p) {
				if (p.featured_media) {
					var media =  select( 'core' ).getMedia(p.featured_media)
					p.featured_media_object = media
				}
				return p
			})
			return {
				posts: posts
			}
		}
		var withData = withSelect(selectAllPosts)
		var example_settings = {
		  title: 'Related Posts',
		  category: 'common',
			attributes: {
				related: { type: 'array' }
			},
		  description: 'Start with the building block of all narrative.',
		  save: function() { return null }, // Don't save any markup
		  edit: withData(function( props ) {
				if (!props.attributes.related) {
					props.setAttributes({ related: [] })
				}
				if (props.posts) {
					return el(
						'div',
						{ style: {
							height: '150px',
							overflow: 'scroll'
						} },
						props.posts.map((post) => postItem(post, props))
					)
				} else {
					return el(
						Spinner
					)
				}
			})
		}

		registerBlockType('strawbees-learning/related', example_settings);
}(
		window.wp.blocks,
		window.wp.element,
		window.wp.editor,
		window.wp.data,
		window.wp.components
) );
