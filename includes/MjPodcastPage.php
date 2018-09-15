<?php

class MjPodcastPage {
	private $version = '1.0.0';
	private $file;
	private $shortcode = 'mj_audio_tax';
	private $taxonomy = 'audio-page';

	function __construct( $file ) {
		$this->file = $file;
		add_shortcode( $this->shortcode, array( $this, 'shortcode' ) );
		add_action( "wp", array( $this, 'add_js_css' ) );
	}

	function add_js_css() {
		global $post;
		wp_register_style(
			'MjPodcastPage_css',
			plugin_dir_url( $this->file ) . "public/css/MjPodcastPage_css.css",
			array(),
			$this->version
		);
		if ( has_shortcode( $post->post_content, $this->shortcode ) ) {
			wp_enqueue_style( 'MjPodcastPage_css' );
		}
	}

	function shortcode() {

		$args  = array(
			'taxonomy'   => $this->taxonomy,
			'hide_empty' => true,
		);
		$res   = "";
		$terms = get_terms( $args );

		if ( is_array( $terms ) && count( $terms ) ) {
			$res .= "<div class='podcasts__items'>";
			foreach ( $terms as $term ) {
				$image = get_field( 'image', 'term_' . $term->term_id );
				$link  = get_term_link( $term->term_id, $this->taxonomy );

				$res .= "<a href='{$link}' class='podcasts__item'>";

				$res .= "<div class='podcasts__item-col one'>";
				$res .= wp_get_attachment_image( $image['ID'], 'image_100x100' );
				$res .= "</div>";

				$res .= "<div class='podcasts__item-col two'>";

				$res .= "<div class='podcasts__item-title'>";
				$res .= $term->name;
				$res .= "</div>";

				$res .= "<div class='podcasts__item-description'>";
				$res .= $term->description;
				$res .= "</div>";

				$res .= "</div>";

				$res .= "</a>";
			}
			$res .= "</div>";
		}

		echo $res;
	}

}