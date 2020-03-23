// ImageDisplay - core/image
// Gallery - core/gallery
// YoutubeDisplay - embed/youtube
// Card - inside strawbees-learning/related

//
// Downloads - strawbees-learning/horizontal
// RelatedContent - strawbees-learning/related
// RelatedProduct - strawbees-learning/related
//
// Heading - core/heading
// Paragraph - core/paragraph
// List - core/list

( function( blocks, element, editor, data, components, blockEditor ) {
		var el = element.createElement
		var registerBlockType = blocks.registerBlockType
		var withSelect = data.withSelect
		var CheckboxControl = components.CheckboxControl
		var Spinner = components.Spinner
		var InnerBlocks = blockEditor.InnerBlocks

		// Component for Checkbox + Post title (and save)
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
		// Fetch posts on database
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
		var withAllPosts = withSelect(selectAllPosts)
		var related_settings = {
		  title: 'Related Posts',
		  category: 'common',
		  icon: 'admin-page',
			attributes: {
				related: { type: 'array' }
			},
		  description: 'Start with the building block of all narrative.',
		  save: function() { return null }, // Don't save any markup
		  edit: withAllPosts(function( props ) {
				if (!props.attributes.related) {
					props.setAttributes({ related: [] })
				}
				if (props.posts) {
					return el(
						'div',
						{
							style: {
								height: '150px',
								overflow: 'scroll'
							}
						},
						props.posts.map((post) => postItem(post, props))
					)
				} else {
					return el(
						Spinner
					)
				}
			})
		}
		registerBlockType('strawbees-learning/related', related_settings);

		var horizontal_settings = {
			title: 'Horizontal Section',
		  category: 'layout',
		  icon: 'leftright',
			attributes: {
				innerBlocks: { type: 'array' }
			},
		  description: 'Start with the building block of all narrative.',
		  edit: function(props) {
				return el(
					'div',
					{ className: props.className },
					el( InnerBlocks )
				);
			},
		  save: function(props) {
				return el(
					'div',
					{ className: props.className },
					el( InnerBlocks.Content )
				);
			}
		}
		registerBlockType('strawbees-learning/horizontal', horizontal_settings);
}(
		window.wp.blocks,
		window.wp.element,
		window.wp.editor,
		window.wp.data,
		window.wp.components,
		window.wp.blockEditor
) );