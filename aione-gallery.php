<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://sgssandhu.com/
 * @since             1.0.0
 * @package           Aione_Gallery
 *
 * @wordpress-plugin
 * Plugin Name:       Aione Gallery
 * Plugin URI:        http://oxosolutions.com/products/wordpress-plugins/aione-gallery
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           2.0.1.1
 * Author:            SGS Sandhu
 * Author URI:        http://sgssandhu.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       aione-gallery
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/oxosolutions/aione-gallery
 * GitHub Branch: master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-aione-gallery-activator.php
 */
function activate_aione_gallery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aione-gallery-activator.php';
	Aione_Gallery_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-aione-gallery-deactivator.php
 */
function deactivate_aione_gallery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aione-gallery-deactivator.php';
	Aione_Gallery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_aione_gallery' );
register_deactivation_hook( __FILE__, 'deactivate_aione_gallery' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-aione-gallery.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_aione_gallery() {

	$plugin = new Aione_Gallery();
	$plugin->run();

}
run_aione_gallery();
