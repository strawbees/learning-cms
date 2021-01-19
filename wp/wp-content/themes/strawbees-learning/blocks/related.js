( function( blocks, element, data, components, blockEditor ) {
	var el = element.createElement
	var useState = element.useState
	var registerBlockType = blocks.registerBlockType
	var withSelect = data.withSelect
	var CheckboxControl = components.CheckboxControl

	// Fetch posts on database
	var selectAllPosts = function(select) {
		var posts = select( 'core' )
			.getEntityRecords( 'postType', 'post', { per_page: -1 } ) || []
		var pages = select( 'core' )
			.getEntityRecords( 'postType', 'page', { per_page: -1 } ) || []
		return { posts: posts.concat(pages) }
	}
	// Higher order function that populates the `props` with data
	var dataWrapper = withSelect(selectAllPosts)
	// Block options
	var related_settings = {
		title: 'Related Posts',
		category: 'common',
		icon: 'admin-page',
		attributes: {
			related: { type: 'array' }
		},
		description: 'Related posts',
		save: function(props) {
			// This will be rendered to the html
			let related = props.attributes.related || []
			return el(
				'div',
				{ className: props.className },
				related.map(function(postId) {
					return el(
						'div',
						{ className: 'related-post' },
						el('span', { className: 'id' }, postId),
					)
				})
			);
		},
		edit: dataWrapper(function( props ) {
			// This will be displayed on Gutenberg
			var allPosts = props.posts
			var related = props.attributes.related || []
			if (props.posts && props.posts.length > 0) {
				return el(
					'div',
					{
						style: {
							borderTop: 'solid 5px #eee',
							borderBottom: 'solid 5px #eee',
							padding: '5px 0',
							maxHeight: '160px',
							overflowY: 'scroll'
						}
					},
					props.posts.map(function(post) {
						var store = useState(related.indexOf(post.id) !== -1)
						var isChecked = store[0]
						var setChecked = store[1]
						return el(
							CheckboxControl,
							{
								label: post.title.raw,
								checked: isChecked,
								onChange: function(checked) {
									setChecked(checked)
									var index = related.indexOf(post.id)
									if (checked && index === -1) {
										related.push(post.id)
									} else if (!checked && index !== -1) {
										related.splice(index, 1)
									}
									props.setAttributes({related: related.slice()})
								}
							}
						)
					})
				)
			} else {
				return el(
					'div',
					{
						style: {
							minHeight: '150px',
							background: '#eee',
							display: 'flex',
							alignItems: 'center',
							justifyContent: 'center'
						}
					},
					'Loading Posts...'
				)
			}
		})
	}
	registerBlockType('strawbees-learning/related', related_settings);
}(
	window.wp.blocks,
	window.wp.element,
	window.wp.data,
	window.wp.components,
	window.wp.blockEditor
) );
