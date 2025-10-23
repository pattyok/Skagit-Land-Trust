/** Register custom styles for the button, remove the outline style */

document.addEventListener( 'DOMContentLoaded', function () {
	wp.plugins.unregisterPlugin( 'block-directory' );
	wp.blocks.unregisterBlockType( 'core/gallery' );
	wp.blocks.unregisterBlockType( 'core/quote' );
	wp.blocks.unregisterBlockType( 'core/details' );
	wp.blocks.unregisterBlockType( 'carkeek-blocks/button-expand-collapse' );
	wp.blocks.registerBlockStyle( 'core/video', {
		name: 'overlay-caption',
		label: 'Overlay Caption',
	} );

	wp.blocks.registerBlockStyle( 'core/list', {
		name: 'no-bullets',
		label: 'No Bullets',
	} );

	wp.blocks.registerBlockStyle( 'core/heading', {
		name: 'light',
		label: 'Light',
	} );

	wp.blocks.registerBlockStyle( 'core/list', {
		name: 'two-col',
		label: 'Two Column',
	} );

	wp.blocks.registerBlockStyle( 'core/column', {
		name: 'stretch',
		label: 'Stretch Content',
	} );

	wp.blocks.registerBlockStyle( 'carkeek-blocks/extended-gallery', {
		name: 'mosaic',
		label: 'Mosaic Layout',
	} );



	// Remove Core Image Style.
	wp.blocks.unregisterBlockStyle( 'core/image', 'rounded' );

	wp.blocks.unregisterBlockStyle( 'core/button', 'fill' );

	// Remove Core Button Style.
	wp.blocks.unregisterBlockStyle( 'core/button', 'outline' );

	// Remove Core HR Style.
	wp.blocks.unregisterBlockStyle( 'core/separator', 'dots' );
	// Remove Core HR Style.
	wp.blocks.unregisterBlockStyle( 'core/separator', 'wide' );

	wp.blocks.unregisterBlockStyle( 'core/quote', 'large' );


	wp.blocks.registerBlockStyle( 'core/button', [
		{
			name: 'arrow-link',
			label: 'Call to Action'
		},
	] );

	wp.blocks.registerBlockStyle( 'core/button', [
		{
			name: 'down-arrow',
			label: 'Down Arrow'
		},
	] );

	wp.blocks.registerBlockStyle( 'core/cover', [
		{
			name: 'page-header',
			label: 'Page Header',
		},
	] );

	wp.blocks.registerBlockStyle( 'core/cover', [
		{
			name: 'quote-background',
			label: 'Quote Background',
		},
	] );


	wp.blocks.registerBlockStyle( 'core/group', [
		{
			name: 'half-tone',
			label: 'Half Tone Background',
		},
	] );

	wp.blocks.registerBlockStyle( 'core/group', [
		{
			name: 'equal-height',
			label: 'Circle',
		},
	] );


	wp.blocks.registerBlockStyle( 'core/columns', [
		{
			name: 'mobile-full',
			label: 'Full Width on Mobile',
		},
	] );


	wp.blocks.registerBlockStyle( 'core/column', [
		{
			name: 'center-mobile',
			label: 'Center on Mobile',
		},
	] );

	wp.blocks.registerBlockStyle( 'core/media-text', [
		{
			name: 'stacked',
			label: 'Stacked',
		},
	] );
	wp.blocks.registerBlockStyle( 'core/media-text', [
		{
			name: 'stacked-icon',
			label: 'Icon on Top',
		},
	] );

	wp.blocks.registerBlockStyle( 'core/media-text', [
		{
			name: 'side-icon',
			label: 'Icon on Side',
		},
	] );

	wp.blocks.registerBlockVariation( 'core/buttons', {
		name: 'buttons-standard',
		title: 'Buttons',
		icon: 'button',
		isDefault: true,
		innerBlocks: [ [ 'core/button', { className: 'is-style-arrow-link' } ] ],
		scope: [ 'inserter' ],
		keywords: [ 'button', 'buttons', 'call to action', 'cta' ],
	} );

	wp.blocks.registerBlockVariation( 'core/media-text', {
		name: 'media-text-standard',
		title: 'Media Text',
		icon: 'align-pull-left',
		isDefault: true,
		innerBlocks: [
			[ 'core/heading', {} ],
			[ 'core/paragraph', { fontSize: 'default' } ],
			[
				'core/buttons',
				{},
				[ [ 'core/button', { className: 'is-style-arrow-link' } ] ],
			],
		],
		attributes: {
			align: 'wide',
			verticalAlignment: 'middle',
			imageFill: true,
		},
		scope: [ 'inserter' ],
		keywords: [ 'image', 'media-text' ],
	} );

	wp.blocks.registerBlockVariation( 'core/group', {
		name: 'group',
		title: 'Group',
		isDefault: true,
		attributes: { layout: { inherit: true } },
		scope: [ 'inserter' ],
		keywords: [ 'group' ],
	} );

	wp.blocks.registerBlockVariation( 'core/group', {
		name: 'page-intro',
		title: 'Page Intro',
		isDefault: true,
		attributes: { layout: { inherit: true }, className: 'page-intro' },
		innerBlocks: [ [ 'core/paragraph', { fontSize: 'large', align: "center" } ] ],
		scope: [ 'inserter' ],
		keywords: [ 'intro', 'page-intro' ],
	} );

	wp.blocks.registerBlockVariation( 'core/spacer', {
		name: 'spacer',
		title: 'Spacer',
		isDefault: true,
		attributes: { height: '36px' },
		scope: [ 'inserter' ],
	} );
} );

//Set the default dimratio on the cover block to 0.
function setBlockDefaults( settings, name ) {
	if ( name === 'core/separator' ) {
		if ( settings.supports ) {
			settings.supports.align = [ 'wide', 'full' ];
		} else {
			settings.supports = {
				align: [ 'wide', 'full' ],
			};
		}
	} else if ( name === 'core/media-text' ) {
		settings.attributes.align.default = '';
	}

	return settings;
}

wp.hooks.addFilter(
	'blocks.registerBlockType',
	'wprig-theme',
	setBlockDefaults
);

