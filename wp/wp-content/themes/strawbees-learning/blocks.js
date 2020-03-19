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

( function( blocks, element, editor, components ) {
		var el = element.createElement;
		var example_settings = {
		  title: 'Example',
		  category: 'common',
			attributes: { content: { type: 'string' } },
		  description: 'Start with the building block of all narrative.',
		  save: function() { return null },
		  edit: function( props ) {
				return el(
					editor.RichText,
					{
						tagName: 'div',
						value: props.attributes.content,
		        onChange: function( content ) {
		            props.setAttributes( { content: content } );
		        }
					},
				)
			}
		}
		blocks.registerBlockType('strawbees-learning/example', example_settings);
}(
		window.wp.blocks,
		window.wp.element,
		window.wp.editor,
		window.wp.components
) );
