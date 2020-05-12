<?php
/**
 * Plugin Name: Global Block Integrator
 * Plugin URI: https://christopher.am
 * Description: A Gutenberg Block to load the Themeco Global Block.
 * Author: Christopher Amirian
 * Author URI: https://christopher.am
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt

 * @package GBINT
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
