<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://test.com
 * @since      1.0.0
 *
 * @package    Popular_Posts_Api
 * @subpackage Popular_Posts_Api/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Popular_Posts_Api
 * @subpackage Popular_Posts_Api/public
 * @author     hiren <hirenbhanderi568@gmail.com>
 */

class Popular_Posts_Api_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/popular-posts-api-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/popular-posts-api-public.js', array( 'jquery' ), $this->version, false );

	}

	public function register_routes(){

		register_rest_route('custom/v1', '/popular', [
            'methods' => 'GET',
            'callback' => [$this, 'get_popular_posts'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('custom/v1', '/upvote', [
            'methods' => 'POST',
            'callback' => [$this, 'upvote_post'],
            'permission_callback' => '__return_true'
        ]);

	}

	public function get_popular_posts() {
        $cached = get_transient('popular_posts');
        if ($cached) {
            return $cached;
        }

        $args = [
            'post_type' => 'post',
            'posts_per_page' => 8,
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
        ];

        $query = new WP_Query($args);
        $posts = [];

        while ($query->have_posts()) {
            $query->the_post();
            $posts[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'permalink' => get_permalink(),
                'upvotes' => (int) get_post_meta(get_the_ID(), '_upvotes', true),
            ];
        }

        wp_reset_postdata();

        set_transient('popular_posts', $posts, 5 * MINUTE_IN_SECONDS);

        return $posts;
    }

    public function upvote_post(WP_REST_Request $request){
        $post_id = $request->get_param('post_id');

        if (!is_numeric($post_id) || get_post_type($post_id) !== 'post') {
            return new WP_Error('invalid_post', 'Invalid post ID', ['status' => 400]);
        }

        $user_ip = $_SERVER['REMOTE_ADDR'];
        $cookie_key = 'upvoted_' . $post_id;

        if (isset($_COOKIE[$cookie_key])) {
            return new WP_REST_Response(['message' => 'Already upvoted'], 403);
        }

        $current = (int) get_post_meta($post_id, '_upvotes', true);
        update_post_meta($post_id, '_upvotes', $current + 1);

        setcookie($cookie_key, '1', time() + DAY_IN_SECONDS, "/");

        // Invalidate cache
        delete_transient('popular_posts');

        return ['message' => 'Upvoted'];
    }
}