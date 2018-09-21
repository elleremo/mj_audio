<?php
$t_raw_str       = get_query_var( 'feed' );
$t_array         = explode( "/", $t_raw_str );
$t_term          = get_term_by( 'slug', array_pop( $t_array ), 'audio-page' );
$t_term_link     = get_term_link( intval( $t_term->term_id ), 'audio-page' );
$t_language      = 'ru';
$t_fields        = get_fields( 'term_' . $t_term->term_id );
$t_categories    = get_field( 'categories', 'term_' . $t_term->term_id );
$t_start_date    = get_term_meta( $t_term->term_id, '_start_date', true );
$t_modified_date = get_term_meta( $t_term->term_id, '_modified_date', true );
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
     xmlns:googleplay="http://www.google.com/schemas/play-podcasts/1.0"
     xmlns:fh="http://purl.org/syndication/history/1.0"
     xmlns:media="http://search.yahoo.com/mrss/"
     xmlns:creativeCommons="http://backend.userland.com/creativeCommonsRssModule"
>
	<channel>
		<title><?php echo esc_html( $t_term->name ); ?></title>
		<atom:link href="<?php echo esc_url( site_url( $t_raw_str ) ); ?>" rel="self" type="application/rss+xml"/>
		<itunes:keywords><?php echo esc_html( $t_fields['keywords'] ); ?></itunes:keywords>
		<link><?php echo $t_term_link; ?></link>
		<description><?php echo esc_html( $t_term->description ); ?></description>
		<pubDate><?php echo $t_start_date; ?></pubDate>
		<lastBuildDate><?php echo $t_modified_date; ?></lastBuildDate>
		<language><?php echo esc_html( $t_language ); ?></language>
		<copyright><?php echo esc_html( $t_fields['copyright'] ); ?></copyright>
		<itunes:author><?php echo esc_html( $t_fields['author'] ); ?></itunes:author>
		<googleplay:author><?php echo esc_html( $t_fields['author'] ); ?></googleplay:author>
		<googleplay:email><?php echo esc_html( $t_fields['email'] ); ?></googleplay:email>
		<itunes:summary><?php echo esc_html( $t_term->description ); ?></itunes:summary>
		<googleplay:description><?php echo esc_html( $t_term->description ); ?></googleplay:description>
		<itunes:owner>
			<itunes:name><?php echo esc_html( $t_fields['author'] ); ?></itunes:name>
			<itunes:email><?php echo esc_html( $t_fields['email'] ); ?></itunes:email>
		</itunes:owner>
		<itunes:explicit><?php echo esc_html( $t_fields['explicit'] ); ?></itunes:explicit>
		<googleplay:explicit><?php echo esc_html( $t_fields['explicit'] ); ?></googleplay:explicit>
		<?php if ( array_key_exists( 'image', $t_fields ) ) : ?>
			<itunes:image href="<?php echo esc_url( $t_fields['image']['url'] ); ?>"/>
			<googleplay:image href="<?php echo esc_url( $t_fields['image']['url'] ); ?>"/>
			<image>
				<url><?php echo esc_url( $t_fields['image']['url'] ); ?></url>
				<title><?php echo esc_html( $t_term->name ); ?></title>
				<link><?php echo $t_term_link; ?></link>
			</image>
		<?php endif; ?>
		<itunes:category text="<?php echo esc_attr( $t_categories ); ?>"/>
		<media:copyright><?php echo esc_html( $t_fields['copyright'] ); ?></media:copyright>
		<media:thumbnail url="<?php echo esc_url( $t_fields['image']['url'] ); ?>"/>
		<media:keywords><?php echo esc_html( $t_fields['keywords'] ); ?></media:keywords>
		<?php if ( $t_categories !== false ):
			foreach ( $t_categories as $key => $val ):
				if ( count( $val ) < 1 ):
					?>
					<media:category
							scheme="http://www.itunes.com/dtds/podcast-1.0.dtd"><?php echo esc_attr( $key ); ?></media:category>
				<?php else: ?>
					<?php foreach ( $val as $name ): ?>
						<media:category
								scheme="http://www.itunes.com/dtds/podcast-1.0.dtd"><?php echo esc_attr( $name ); ?></media:category>
					<?php endforeach; ?>
				<?php endif;
			endforeach;
		endif; ?>
		<creativeCommons:license><?php echo esc_html( $t_fields['license'] ); ?></creativeCommons:license>


		<?php
		remove_action( 'rss2_head', 'rss2_site_icon' );
		do_action( 'rss2_head' );

		$raw_str = get_query_var( 'feed' );
		$array   = explode( "/", $raw_str );
		$term    = get_term_by( 'slug', array_pop( $array ), 'audio-page' );


		$args = array(
			'posts_per_page' => - 1,
			'post_type'      => 'audio',
			'tax_query'      => array(
				array(
					'taxonomy' => 'audio-page',
					'field'    => 'id',
					'terms'    => $t_term->term_id
				)
			)
		);
		$args = apply_filters( 'mj_podcasts_post_args', $args );

		$posts = get_posts( $args );


		if ( count( $posts ) > 0 ):?>
			<?php foreach ( $posts as $post ) : ?>
				<?php $fields = get_fields( $post->ID ); ?>
				<?php $audio_meta_data = get_post_meta( $fields['audio']['ID'], '_wp_attachment_metadata', true ); ?>
				<item>

					<title><?php echo esc_html( $post->post_title ); ?></title>
					<link><?php echo esc_url( $t_term_link ); ?></link>
					<pubDate><?php echo esc_html( mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true, $post ), false ) ); ?></pubDate>
					<dc:creator><?php echo $fields['creator']; ?></dc:creator>
					<guid isPermaLink="false"><?php echo esc_html( get_the_guid( $post ) ); ?></guid>


					<description><![CDATA[<?php echo sanitize_text_field( $fields['description'] ); ?>]]></description>
					<itunes:subtitle>
						<![CDATA[<?php echo mb_substr( sanitize_text_field( $fields['description'] ), 0, 210 ) . "..."; ?>
						]]>
					</itunes:subtitle>
					<content:encoded><![CDATA[<?php echo sanitize_text_field( $fields['description'] ); ?>]]>
					</content:encoded>
					<itunes:summary><![CDATA[<?php echo sanitize_text_field( $fields['description'] ); ?>]]>
					</itunes:summary>
					<googleplay:description><![CDATA[<?php echo sanitize_text_field( $fields['description'] ); ?>]]>
					</googleplay:description>

					<?php if ( has_post_thumbnail( $post ) ) { ?>
						<itunes:image href="<?php echo esc_url( get_the_post_thumbnail_url( $post, 'full' ) ); ?>"/>
						<googleplay:image href="<?php echo esc_url( get_the_post_thumbnail_url( $post, 'full' ) ); ?>"/>
						<media:thumbnail url="<?php echo esc_url( get_the_post_thumbnail_url( $post, 'full' ) ); ?>"/>
					<?php } ?>
					<enclosure url="<?php echo esc_url( $fields["audio"]['url'] ); ?>"
					           length="<?php echo esc_attr( $audio_meta_data['filesize'] ); ?>"
					           type="<?php echo $fields['audio']['mime_type']; ?>"/>
					<media:content url="<?php echo esc_url( $fields["audio"]['url'] ); ?>"
					               fileSize="<?php echo esc_attr( $audio_meta_data['filesize'] ); ?>"
					               type="<?php echo esc_attr( $fields['audio']["mime_type"] ); ?>"/>
					<itunes:explicit><?php echo esc_html( $fields['explicit'] ); ?></itunes:explicit>
					<googleplay:explicit><?php echo esc_html( $fields['explicit'] ); ?></googleplay:explicit>
					<itunes:duration><?php echo $audio_meta_data['length_formatted']; ?></itunes:duration>
					<itunes:author><?php echo $fields['creator']; ?></itunes:author>
				</item>
			<?php endforeach; ?>
		<?php endif; ?>


	</channel>
</rss>
