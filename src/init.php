<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package WPDEVAM
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function wpdevam_global_block_cgb_block_assets() { // phpcs:ignore

	$is_global_block_available = 0;
	if ( post_type_exists( 'cs_global_block' ) ) {
		$is_global_block_available = 1;
	}

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * Register the block on server-side to ensure that the block
	 * scripts and styles for both frontend and backend are
	 * enqueued when the editor loads.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */
	register_block_type(
		'wpdevam/block-wpdevam-global-block', array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'wpdevam_global_block-cgb-style-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'wpdevam_global_block-cgb-block-js',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'wpdevam_global_block-cgb-block-editor-css',
			'render_callback' => 'wpdevam_global_block_dynamic_render_callback'
		)
	);

	$wpdevam_args = array(
		'post_type' 	=> 'cs_global_block',
		'post_status'	=> 'tco-data'
	);
	$wpdevam_global_block_query = new WP_Query( $wpdevam_args );
	$wpdevam_result = [];

	if ( $wpdevam_global_block_query->have_posts() ) {
		array_push( $wpdevam_result, array('value' => '-1', 'label' => 'Select the Global Block...') );
		while ( $wpdevam_global_block_query->have_posts() ) {
			$wpdevam_global_block_query->the_post();
			array_push( $wpdevam_result, array( 'value' => get_the_ID(), 'label' => get_the_title() ) );
		}
	}
	wp_reset_postdata();

	// Register block styles for both frontend + backend.
	wp_register_style(
		'wpdevam_global_block-cgb-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-editor' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);

	// Register block editor script for backend.
	wp_register_script(
		'wpdevam_global_block-cgb-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-compose' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
		'wpdevam_global_block-cgb-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);

	wp_localize_script(
		'wpdevam_global_block-cgb-block-js',
		'wpdevamGlobal', // Array containing dynamic data for a JS Global.
		[
			'pluginDirPath' 			=> plugin_dir_path( __DIR__ ),
			'pluginDirUrl'  			=> plugin_dir_url( __DIR__ ),
			'globalBlocks'				=> $wpdevam_result,
			'isGlobalBlockAvailable'	=> $is_global_block_available
		]
	);

	function wpdevam_global_block_dynamic_render_callback($attributes) {
		ob_start(); // Turn on output buffering

		if ( post_type_exists( 'cs_global_block' ) ) {
			if ( empty( $attributes ) || $attributes['selectedBlock'] == -1 ) {
				echo '<p>Please select a block to show.</p>';
			} else {
				if ( empty( get_post( $attributes['selectedBlock'] ) ) ) {
					echo '<p>Please select a block to show.</p>';
				} else {
					echo '[cs_gb id=' . $attributes['selectedBlock'] . ']';
				}
			}
		} else {
			echo '<p>Please activate Themeco Pro or X theme, or Themeco Cornerstone plugin.</p>';
		}

		$output = ob_get_contents(); // collect output
		ob_end_clean(); // Turn off ouput buffer

		return $output; // Print output
	}

}

// Hook: Block assets.
add_action( 'init', 'wpdevam_global_block_cgb_block_assets' );

