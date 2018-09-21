<?php

class MjPodcastFeed {
	private $file;
	private $plugin_path;
	private $slug = 'feed-podcasts';
	private $taxonomy = 'audio-page';

	function __construct( $file ) {
		$this->file        = $file;
		$this->plugin_path = plugin_dir_path( $file );
		add_action( 'init', array( $this, 'add_multi_feed' ), 11 );
		add_action( 'init', array( $this, 'general_feed' ), 11 );

		register_activation_hook( $file, array( $this, 'activate' ) );
		register_deactivation_hook( $file, array( $this, 'deactivate' ) );
		add_action( "edited_{$this->taxonomy}", array( $this, 'feed_flush_rewrite' ), 10, 2 );
		add_action( "template_redirect", array( $this, "template_rule" ) );

		add_action( "edited_{$this->taxonomy}", array( $this, 'edited_term' ) );
		add_action( "create_{$this->taxonomy}", array( $this, 'create_term' ) );
	}

	function edited_term( $term_id ) {
		$this->update_modified_date( $term_id );
	}

	function create_term( $term_id) {
		$this->update_start_modified( $term_id );
	}

	private function update_modified_date( $term_id ) {
		$value = current_time( "D, d M Y H:i:s +0000" );
		update_term_meta( $term_id, "_modified_date", $value );
	}

	private function update_start_modified( $term_id ) {
		$value = current_time( "D, d M Y H:i:s +0000" );
		update_term_meta( $term_id, "_start_date", $value );
	}

	function feed_flush_rewrite( $term_id, $tt_id ) {
		flush_rewrite_rules();
	}

	private function get_feed_pages() {
		$terms = get_terms(
			array(
				'taxonomy'     => array( $this->taxonomy ),
				'hierarchical' => false,
				'hide_empty'   => true,
			) );
		if ( is_array( $terms ) && count( $terms ) > 0 ) {
			return $terms;
		}

		return false;
	}

	public function general_feed(){
        add_feed( $this->slug."/all", array( $this, 'feed_markup_general' ) );
    }

	public function add_multi_feed() {

		$terms = $this->get_feed_pages();
		foreach ( $terms as $term ) {
			$this->add_feed( $term->slug );
		}

	}

	public function add_feed( $postfix ) {
		$feed_slug = apply_filters( 'mj_feed_slug', $this->slug . "/{$postfix}" );
		add_feed( $feed_slug, array( $this, 'feed_markup' ) );
	}

	function template_rule() {
		global $wp_query;
		if ( strpos( $wp_query->query_vars['feed'], $this->slug ) !== false ) {
			$wp_query->is_404     = false;
			$wp_query->have_posts = true;
			$wp_query->is_archive = true;
		}
	}

	function feed_markup_general() {
		header( 'Content-Type: ' . feed_content_type( 'rss' ) . '; charset=' . get_option( 'blog_charset' ), true );
		status_header( 200 );

		global $wp_query;

		do_action( 'mj_before_feed' );

		$template = apply_filters( 'mj_template_rss', $this->plugin_path . "templates/feed-general-podcast.php" );

		include( $template );
		do_action( 'mj_after_feed' );
		exit;
	}

	function feed_markup() {
		header( 'Content-Type: ' . feed_content_type( 'rss' ) . '; charset=' . get_option( 'blog_charset' ), true );
		status_header( 200 );
		global $wp_query;

		do_action( 'mj_before_feed' );

		$template = apply_filters( 'mj_template_rss', $this->plugin_path . "templates/feed-podcast.php" );

		require( $template );

		do_action( 'mj_after_feed' );

		exit;

	}

	public function activate() {
		flush_rewrite_rules();
	}

	public function deactivate() {
		flush_rewrite_rules();
	}


}