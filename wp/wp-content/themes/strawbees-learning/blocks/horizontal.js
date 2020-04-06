( function( blocks, element, blockEditor ) {
	var el = element.createElement
	var registerBlockType = blocks.registerBlockType
	var InnerBlocks = blockEditor.InnerBlocks

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
				{
					className: props.className,
					style: { background: '#eee', padding: '1em 0' }
				},
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
	window.wp.blockEditor
) );
