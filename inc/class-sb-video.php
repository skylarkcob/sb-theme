<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Video {
    public static $youtube_embed_base_url = 'http://www.youtube.com/embed/';

    public static function get_youtube_video_id($post_id) {
        return SB_Post::get_sb_meta($post_id, 'youtube_id');
    }

    public static function get_youtube_thumbnail_url($video_id) {
        return 'http://img.youtube.com/vi/' . $video_id . '/0.jpg';
    }

    public static function get_youtube_thumbnail_data_object($api_key, $video_id) {
        $transient_name = 'sb_theme_youtube_' . $video_id . '_thumbnail_object';
        if(false === ($data = get_transient($transient_name))) {
            $data = self::get_youtube_data_object($api_key, $video_id);
            $data = $data->items[0]->snippet->thumbnails;
            set_transient($transient_name, $data, WEEK_IN_SECONDS);
        }
        return $data;
    }

    public static function the_thumbnail_youtube($args = array()) {
        $post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
        $post = get_post($post_id);

        if(!SB_Post::is($post)) {
            return;
        }
        $video_id = self::get_youtube_video_id($post_id);
        $width = isset($args['width']) ? $args['width'] : '';
        $height = isset($args['height']) ? $args['height'] : '';

        if(has_post_thumbnail($post_id) || empty($video_id)) {
            SB_Post::the_thumbnail(array('width' => $width, 'height' => $height, 'post_id' => $post_id));
        } else {
            $image = new SB_HTML('img');
            $atts = array(
                'itemprop' => 'image',
                'class' => 'wp-post-image sb-post-image img-responsive thumbnail-image youtube-thumbnail',
                'alt' => $post->post_title,
                'src' => self::get_youtube_thumbnail_url($video_id)
            );

            if(!empty($width)) {
                $atts['width'] = $width;
            }
            if(!empty($height)) {
                $atts['height'] = $height;
            }
            $image->set_attribute_array($atts);
            ?>
            <a href="<?php echo get_permalink($post); ?>" title="<?php echo $post->post_title; ?>" class="img-hyperlink"><?php echo $image->build(); ?></a>
        <?php
        }
    }

    public static function get_youtube_data_object($api_key, $video_id) {
        $transient_name = 'sb_theme_youtube_' . $video_id . '_data_object';
        if(false === ($data = get_transient($transient_name))) {
            $data = file_get_contents('https://www.googleapis.com/youtube/v3/videos?key=' . $api_key . '&part=snippet&id=' . $video_id);
            $data = json_decode($data);
            set_transient($transient_name, $data, WEEK_IN_SECONDS);
        }
        return $data;
    }

    public static function youtube_player($args = array()) {
        $id = isset($args['id']) ? $args['id'] : '';
        if(empty($id)) {
            return;
        }
        $width = isset($args['width']) ? $args['width'] : '';
        $height = isset($args['height']) ? $args['height'] : '';
        $autoplay = isset($args['autoplay']) ? $args['autoplay'] : 1;
        $origin = isset($args['origin']) ? $args['origin'] : '';
        $full_screen = isset($args['full_screen']) ? (bool)$args['full_screen'] : true;
        $iframe = new SB_HTML('iframe');
        $atts = array(
            'type' => 'text/html',
            'class' => 'youtube-video sb-youtube-player',
            'data-server' => 'youtube',
            'data-id' => $id
        );
        if($full_screen) {
            $atts['allowfullscreen'] = 'allowfullscreen';
        }
        if(!empty($width)) {
            $atts['width'] = $width;
        }
        if(!empty($height)) {
            $atts['height'] = $height;
        }
        $url = self::$youtube_embed_base_url . $id;
        $url = add_query_arg(array('autoplay' => $autoplay), $url);
        if(!empty($origin)) {
            $url = add_query_arg(array('origin' => $origin), $url);
        }
        $atts['src'] = $url;
        $iframe->set_attribute_array($atts);
        echo $iframe->build();
    }
}