<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://test.com
 * @since      1.0.0
 *
 * @package    Popular_Posts_Api
 * @subpackage Popular_Posts_Api/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Popular_Posts_Api
 * @subpackage Popular_Posts_Api/admin
 * @author     hiren <hirenbhanderi568@gmail.com>
 */
class Popular_Posts_Api_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Popular_Posts_Api_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Popular_Posts_Api_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/popular-posts-api-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Popular_Posts_Api_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Popular_Posts_Api_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/popular-posts-api-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function get_popular_posts_admin_menu() {
	    add_menu_page(
	        'Popular Posts Cache',
	        'Popular Posts Cache',
	        'manage_options',
	        $this->plugin_name,
	        array($this, 'render_clear_cache_page'),
	        'dashicons-update',
	        81
	    );
	}


	function render_clear_cache_page() {
	    if (isset($_POST['clear_popular_cache'])) {
	        if (!current_user_can('manage_options') || !check_admin_referer('clear_popular_cache_action')) {
	            wp_die('Unauthorized action');
	        }

	        delete_transient('popular_posts');
	        echo '<div class="notice notice-success"><p>Popular posts cache cleared successfully!</p></div>';
	    }

	    ?>
	    <div class="wrap">
	        <h1>Clear Popular Posts Cache</h1>
	        <form method="post">
	            <?php wp_nonce_field('clear_popular_cache_action'); ?>
	            <input type="submit" name="clear_popular_cache" class="button button-primary" value="Clear Cache Now">
	        </form>
	    </div>
	    <?php
	}

}
