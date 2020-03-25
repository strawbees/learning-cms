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
			var postAlreadyExists = props.attributes.related.find(function(related) {
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
					var featured_media_object = {
						source_url: post.featured_media_object ? post.featured_media_object.source_url : ''
					}
					props.setAttributes({
						related: [
							{
									id: post.id,
									title: post.title,
									categories_objects: post.categories_objects,
									excerpt: post.excerpt,
									featured_media_object: featured_media_object,
									link: post.link,
									path: post.path
							},
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
			var allCategories =  select( 'core' ).getEntityRecords( 'taxonomy',  'category' )
			return { posts: posts.map(function(post) {
				// Append a full object with featured image data
				if (post.featured_media) {
					var media = select( 'core' ).getMedia(post.featured_media)
					post.featured_media_object = media
				}
				// Append full objects with category data inside
				if (allCategories && post.categories && post.categories.length > 0) {
					var categoryHash = allCategories.reduce(function(acc, category) {
						acc[category.id] = category
						return acc
					}, {})
					var categoryObjects = post.categories.map(function(category) {
						return categoryHash[category]
					})
					post.categories_objects = categoryObjects
				}
				return post
			}) }
		}

		var related_settings = {
		  title: 'Related Posts',
		  category: 'common',
		  icon: 'admin-page',
			attributes: {
				related: { type: 'array' }
			},
		  description: 'Related posts',
			save: function(props) {
				// This will be rendered to the html tree
				return el(
					'div',
					{ className: props.className },
					props.attributes.related.map(function(post) {
						let category = {}
						if (post.categories_objects && post.categories_objects[0]) {
							category = post.categories_objects[0]
						}
						return el(
							'div', { className: 'related-post' },
							[
								el('a', { className: 'title', href: post.link }, post.title.raw),
								el('img', { className: 'featured-media', src: post.featured_media_object.source_url  }),
								el('p', { className: 'excerpt' }, post.excerpt.raw),
								el('a', { className: 'category', href: category.link }, category.name),
							]
						)
					})
				);
			},
		  edit: withSelect(selectAllPosts)
			(function( props ) {
				// This will be displayed on Gutenberg
				if (!props.attributes.related) {
					props.setAttributes({ related: [] })
				}
				if (props.posts) {
					return el(
						'div',
						{ style: { height: '150px', overflow: 'scroll' } },
						props.posts.map(function(post) {
							return postItem(post, props)
						})
					)
				} else {
					return el(
						'div', { style: { height: '150px' } }, 'Loading...'
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
		  description: 'A grey horizontal section',
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
					{},
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
