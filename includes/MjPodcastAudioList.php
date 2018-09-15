<?php

class MjPodcastAudioList {
	private $version = '1.2.0';
	private $taxonomy = 'audio-page';
	private $file;
	private $counter = 1;

	function __construct( $file ) {
		$this->file = $file;
		add_action( 'MojoMediaContentBefore', array( $this, 'panel_header' ), 5 );
		add_action( 'MojoMediaContentBefore', array( $this, 'panel_before' ), 10 );
		add_action( 'MojoMediaTemplatePodcastAudio', array( $this, 'panel' ), 20 );
		add_action( 'MojoMediaContentAfter', array( $this, 'panel_after' ), 30 );
		add_action( "wp", array( $this, "add_js_css" ) );
	}

	function add_js_css() {
		wp_register_style(
			"MjPodcastAudioList_css",
			plugin_dir_url( $this->file ) . "public/css/MjPodcastAudioList.css"
			, array(),
			$this->version
		);


		wp_register_script(
			"MjPodcastAudioList_js",
			plugin_dir_url( $this->file ) . "public/js/player.js",
			array( "jquery", "audio-player-responsive-and-touch-friendly" ),
			$this->version,
			true
		);


		wp_register_style(
			"player-new_css",
			plugin_dir_url( $this->file ) . "public/css/player-new.css"
			, array(),
			$this->version
		);


		wp_register_script(
			"audio-player-responsive-and-touch-friendly",
			plugin_dir_url( $this->file ) . "public/js/vendor/audio-player-responsive-and-touch-friendly/audioplayer.js",
			array( "jquery" ),
			$this->version,
			true
		);

		if ( is_tax( $this->taxonomy ) ) {
			wp_enqueue_script( "audio-player-responsive-and-touch-friendly" );
			wp_enqueue_style( "player-new_css" );
			wp_enqueue_style( "MjPodcastAudioList_css" );
			wp_enqueue_script( "MjPodcastAudioList_js" );

		}
	}

	function panel_header() {
		if ( is_tax( $this->taxonomy ) && is_main_query() ) :?>

            <div class="podcast-audio-panel-header">
                <div class="podcast-audio-panel-header-coll num">
                    <div class="icon"></div>
                </div>
                <div class="podcast-audio-panel-header-coll title">
                    Название
                </div>
                <div class="podcast-audio-panel-header-coll date">
                    Релиз
                </div>
                <div class="podcast-audio-panel-header-coll description">
                    Описание
                </div>
                <div class="podcast-audio-panel-header-coll play">
                    Слушать
                </div>
                <div class="podcast-audio-panel-header-coll download">
                    Загрузить
                </div>
            </div>
		<?php endif;
	}

	function panel_before() {
		if ( is_tax( $this->taxonomy ) && is_main_query() ) {
			echo "<div class='podcast-audio-panel-items'>";
		}
	}

	function panel_after() {
		if ( is_tax( $this->taxonomy ) && is_main_query() ) {
			echo "</div>";
		}
	}

	function panel( $post_id ) {
		$fields = get_fields( $post_id );
		?>
        <article class="podcast-audio-panel-item">
            <div class="podcast-audio-panel-item-content">
                <div class="podcast-audio-panel-item-coll num">
					<?php echo $this->counter; ?>
                </div>
                <div class="podcast-audio-panel-item-coll title">
					<?php echo get_the_title( $post_id ); ?>
                </div>
                <div class="podcast-audio-panel-item-coll date">
					<?php
					echo mysql2date( 'd. m. Y', get_post_meta( $post_id, 'date', true ), false )
					?>
                </div>
                <div class="podcast-audio-panel-item-coll description">
					<?php echo wp_trim_words( $fields['description'], 4, '...' ); ?>
                </div>
                <div class="podcast-audio-panel-item-coll play" data-index="<?php echo $this->counter; ?>">
                    <div class="icon">

                    </div>
                </div>
                <div class="podcast-audio-panel-item-coll download">
                    <a class="icon" href="<?php echo $fields['audio']['url']; ?>" download>

                    </a>
                </div>

            </div>

            <div class="podcast-audio-panel-item-coll-full description">
				<?php echo $fields['description']; ?>
            </div>
            <div class="podcast-audio-panel-item-coll-full play">
                <div class="mj_audio_player" data-index="<?php echo $this->counter; ?>">

                    <audio src="<?php echo $fields['audio']['url']; ?>"></audio>

                </div>
            </div>

        </article>
		<?php
		$this->counter ++;
	}

}