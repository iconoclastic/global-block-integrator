/**
 * BLOCK: wpdevam-global-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { SelectControl } = wp.components;

const theBlockInfo = wpdevamGlobal.globalBlocks;
const isGlobalBlockAvailable = wpdevamGlobal.isGlobalBlockAvailable;

const WPDEVAMSelector = ( props ) => {
	if ( isGlobalBlockAvailable === '1' ) {
		return (
			<div className={ props.className }>
				<SelectControl
					label="Select the Global Block"
					value={ props.attributes.selectedBlock }
					options={ theBlockInfo && theBlockInfo.length ? theBlockInfo : [ { label: 'No Global Block Available', value: '0' } ] }
					onChange={ ( theSelectedGlobalBlock ) => {
						props.setAttributes( { selectedBlock: theSelectedGlobalBlock } );
					} }
				/>
			</div>
		);
	// eslint-disable-next-line no-else-return
	} else {
		return (
			<div className={ props.className }>
				<p>Please activate Themeco Pro or X theme, or Themeco Cornerstone plugin.</p>
			</div>
		);
	}
};

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */

registerBlockType( 'wpdevam/block-wpdevam-global-block', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'WPDEVAM Global Block' ), // Block title.
	icon: 'shield', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'WPDEVAM Global Block' ),
		__( 'Themeco Global Block' ),
		__( 'Cornerstone' ),
		__( 'Pro' ),
	],
	attributes: {
		selectedBlock: {
			type: 'string',
			default: theBlockInfo[ 0 ],
		},
	},
	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Component.
	 */
	// The "edit" property must be a valid function.
	edit: function( props ) {
		return (
			<WPDEVAMSelector { ...props } />
		);
	},

	// The "save" property must be specified and must be a valid function.
	// save: function( props ) {
	// 	const theShortcode = '[cs_gb id=' + props.attributes.selectedBlock + ']';
	// 	return (
	// 		<RawHTML>{ theShortcode }</RawHTML>
	// 	);
	// },
	save: () => {
		return null;
	},

} );
