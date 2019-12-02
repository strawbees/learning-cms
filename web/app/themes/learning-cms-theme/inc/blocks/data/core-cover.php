<?php
function get_block_data_core_cover( $block ) {
	$focal_point = resolve_attrs_prop( $block['attrs'], 'focalPoint', array( 'x' => 0.5, 'y' => 0.5 ) );
	return array(
		'sizeFormat'              => resolve_attrs_size_format( $block['attrs'] ),
		'backgroundImageUrl'      => convert_to_cdn_url ( resolve_attrs_prop( $block['attrs'], 'url' ) ),
		'backgroundImageParalax'  => resolve_attrs_prop( $block['attrs'], 'hasParallax', false ),
		'backgroundImagePosition' => ($focal_point['x'] * 100) . '% ' . ($focal_point['y'] * 100) . '%',
		'overlayColor'            => resolve_attrs_color( $block['attrs'], 'overlayColor', 'customOverlayColor', '#303030' ),
		'overlayOpacity'          => resolve_attrs_prop( $block['attrs'], 'dimRatio', 0 ) / 100,
		'minHeight'               => resolve_attrs_prop( $block['attrs'], 'minHeight', null ),
	);
}
?>
