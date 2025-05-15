<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://test.com
 * @since             1.0.0
 * @package           Popular_Posts_Api
 *
 * @wordpress-plugin
 * Plugin Name:       Popular Posts API
 * Plugin URI:        https://test.com
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            hiren
 * Author URI:        https://test.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       popular-posts-api
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'POPULAR_POSTS_API_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-popular-posts-api-activator.php
 */
function activate_popular_posts_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-popular-posts-api-activator.php';
	Popular_Posts_Api_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-popular-posts-api-deactivator.php
 */
function deactivate_popular_posts_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-popular-posts-api-deactivator.php';
	Popular_Posts_Api_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_popular_posts_api' );
register_deactivation_hook( __FILE__, 'deactivate_popular_posts_api' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-popular-posts-api.php';


if ( defined( 'WP_CLI' ) && WP_CLI ) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-popular-posts-cli.php';
    WP_CLI::add_command( 'popular-posts', 'Popular_Posts_CLI' );
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_popular_posts_api() {

	$plugin = new Popular_Posts_Api();
	$plugin->run();

}
run_popular_posts_api();
