<?php
$t_raw_str = get_query_var('feed');
$t_array = explode("/", $t_raw_str);
$t_term = get_term_by('slug', array_pop($t_array), 'audio-page');
$t_term_link = home_url();
$t_language = 'ru';
$t_start_date = function () {
    global $wpdb;
    return $wpdb->get_var("SELECT `post_date` FROM `{$wpdb->prefix}posts` 
    WHERE `post_type` = 'audio' AND `post_status` = 'publish'
     ORDER BY `post_date` ASC LIMIT 1"
    );
};
$t_modified_date = function () {
    global $wpdb;
    return $wpdb->get_var("SELECT `post_date` FROM `{$wpdb->prefix}posts` 
    WHERE `post_type` = 'audio' AND `post_status` = 'publish'
     ORDER BY `post_date` DESC LIMIT 1"
    );
};
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
        <title>Подкасты Mojo Media</title>
        <atom:link href="<?php echo esc_url(site_url($t_raw_str)); ?>" rel="self" type="application/rss+xml"/>
        <itunes:keywords>Mojo media , Mojo podcasts,Дикие утки, Работник месяца, Ребята, мы потрахались , Мы в этом
            живем, Подкасты, подкаст, itunes, podster , soundcloud
        </itunes:keywords>
        <link><?php echo $t_term_link; ?></link>
        <description>Подкасты издательства Mojo Media - дикие утки, работник месяца, ребята, мы потрахались и мы в этом
            живем
        </description>
        <pubDate><?php echo date("D, d M Y H:i:s +0000", strtotime($t_start_date())); ?></pubDate>
        <lastBuildDate><?php echo date("D, d M Y H:i:s +0000", strtotime($t_modified_date())); ?></lastBuildDate>
        <language><?php echo esc_html($t_language); ?></language>
        <copyright>All rights reserved MojoMedia</copyright>
        <itunes:author>Команда MojoMedia</itunes:author>
        <googleplay:author>Команда MojoMedia</googleplay:author>
        <googleplay:email>bilirium@gmail.com</googleplay:email>
        <itunes:summary>Подкасты издательства Mojo Media - дикие утки, работник месяца, ребята, мы потрахались и мы в
            этом живем
        </itunes:summary>
        <googleplay:description>Подкасты издательства Mojo Media - дикие утки, Работник месяца, ребята, мы потрахались и
            мы в этом живем
        </googleplay:description>
        <itunes:owner>
            <itunes:name>Команда MojoMedia</itunes:name>
            <itunes:email>bilirium@gmail.com</itunes:email>
        </itunes:owner>
        <itunes:explicit>yes</itunes:explicit>
        <googleplay:explicit>yes</googleplay:explicit>
        <itunes:image href="https://mojomedia.ru/wp-content/uploads/2018/09/cropped-icon.png"/>
        <googleplay:image href="https://mojomedia.ru/wp-content/uploads/2018/09/cropped-icon.png"/>
        <image>
            <url>https://mojomedia.ru/wp-content/uploads/2018/09/cropped-icon.png</url>
            <title>Подкасты Mojo Media</title>
            <link><?php echo $t_term_link; ?></link>
        </image>
        <itunes:category text="Society &amp; Culture"/>
        <media:copyright>All rights reserved MojoMedia</media:copyright>
        <media:thumbnail url="https://mojomedia.ru/wp-content/uploads/2018/09/cropped-icon.png"/>
        <media:keywords>Mojo media , Mojo podcasts,Дикие утки, Работник месяца, Ребята, мы потрахались , Мы в этом
            живем, Подкасты, подкаст, itunes, podster , soundcloud
        </media:keywords>
        <creativeCommons:license>https://creativecommons.org/licenses/by/4.0/</creativeCommons:license>
        <?php
        remove_action('rss2_head', 'rss2_site_icon');
        do_action('rss2_head');

        $raw_str = get_query_var('feed');
        $array = explode("/", $raw_str);

        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'audio',
        );
        $args = apply_filters('mj_podcasts_post_args', $args);

        $posts = get_posts($args);


        if (count($posts) > 0):?>
            <?php foreach ($posts as $post) : ?>
                <?php
                $current_term = wp_get_post_terms($post->ID, 'audio-page');
                if (is_array($current_term) && !empty($current_term)) {
                    $current_term = $current_term[0];
                    $current_term = $current_term->term_id;
                }
                $t_term_link = get_term_link(intval($current_term), 'audio-page');
                ?>

                <?php $fields = get_fields($post->ID); ?>
                <?php $audio_meta_data = get_post_meta($fields['audio']['ID'], '_wp_attachment_metadata', true); ?>
                <item>
                    <title><?php echo esc_html($post->post_title); ?></title>
                    <link><?php echo esc_url($t_term_link); ?></link>
                    <pubDate><?php echo esc_html(mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true, $post), false)); ?></pubDate>
                    <dc:creator><?php echo $fields['creator']; ?></dc:creator>
                    <guid isPermaLink="false"><?php echo esc_html(get_the_guid($post)); ?></guid>
                    <description><![CDATA[<?php echo sanitize_text_field($fields['description']); ?>]]></description>
                    <itunes:subtitle>
                        <![CDATA[<?php echo mb_substr(sanitize_text_field($fields['description']), 0, 210) . "..."; ?>
                        ]]>
                    </itunes:subtitle>
                    <content:encoded><![CDATA[<?php echo sanitize_text_field($fields['description']); ?>]]>
                    </content:encoded>
                    <itunes:summary><![CDATA[<?php echo sanitize_text_field($fields['description']); ?>]]>
                    </itunes:summary>
                    <googleplay:description><![CDATA[<?php echo sanitize_text_field($fields['description']); ?>]]>
                    </googleplay:description>
                    <?php if (has_post_thumbnail($post)) : ?>
                        <itunes:image href="<?php echo esc_url(get_the_post_thumbnail_url($post, 'full')); ?>"/>
                        <googleplay:image href="<?php echo esc_url(get_the_post_thumbnail_url($post, 'full')); ?>"/>
                        <media:thumbnail url="<?php echo esc_url(get_the_post_thumbnail_url($post, 'full')); ?>"/>
                    <?php endif; ?>
                    <enclosure url="<?php echo esc_url($fields["audio"]['url']); ?>"
                               length="<?php echo esc_attr($audio_meta_data['filesize']); ?>"
                               type="<?php echo $fields['audio']['mime_type']; ?>"/>
                    <media:content url="<?php echo esc_url($fields["audio"]['url']); ?>"
                                   fileSize="<?php echo esc_attr($audio_meta_data['filesize']); ?>"
                                   type="<?php echo esc_attr($fields['audio']["mime_type"]); ?>"/>
                    <itunes:explicit><?php echo esc_html($fields['explicit']); ?></itunes:explicit>
                    <googleplay:explicit><?php echo esc_html($fields['explicit']); ?></googleplay:explicit>
                    <itunes:duration><?php echo $audio_meta_data['length_formatted']; ?></itunes:duration>
                    <itunes:author><?php echo $fields['creator']; ?></itunes:author>
                </item>
            <?php endforeach; ?>
        <?php endif; ?>
    </channel>
</rss>
