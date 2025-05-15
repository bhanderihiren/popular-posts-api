<?php

if ( ! class_exists( 'WP_CLI' ) ) {
    return;
}

class Popular_Posts_CLI {

    /**
     * Clear the popular posts cache.
     *
     * ## EXAMPLES
     *
     *     wp popular-posts clear
     */
    public function clear( $args, $assoc_args ) {
        delete_transient('popular_posts');
        WP_CLI::success("Popular posts cache cleared.");
    }
}