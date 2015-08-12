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

    public static function get_vimeo_data($id) {
        $transient_name = 'sb_theme_vimeo_' . $id . '_data';
        if(false === ($data = get_transient($transient_name))) {
            $url = 'http://vimeo.com/api/v2/video/' . $id . '.php';
            $data = unserialize(file_get_contents($url));
            $data = isset($data[0]) ? $data[0] : array();
            set_transient($transient_name, $data, DAY_IN_SECONDS);
        }
        return $data;
    }

    public static function get_vimeo_thumbnails($id) {
        $data = self::get_vimeo_data($id);
        $result = array(
            'thumbnail_small' => isset($data['thumbnail_small']) ? $data['thumbnail_small'] : '',
            'thumbnail_medium' => isset($data['thumbnail_medium']) ? $data['thumbnail_medium'] : '',
            'thumbnail_large' => isset($data['thumbnail_large']) ? $data['thumbnail_large'] : ''
        );
        return $result;
    }

    public static function get_thumbnail_vimeo($id, $type = 'thumbnail_large') {
        $thumbnails = self::get_vimeo_thumbnails($id);
        return $thumbnails[$type];
    }

    public static function vimeo_player($args = array()) {
        $id = isset($args['id']) ? $args['id'] : '';
        if(empty($id)) {
            return;
        }
        $autoplay = isset($args['autoplay']) ? $args['autoplay'] : false;
        $autoplay = (bool)$autoplay;

        $color = isset($args['color']) ? $args['color'] : '90d5ec';
        $thumbnail = isset($args['thumbnail']) ? $args['thumbnail'] : true;
        $thumbnail = (bool)$thumbnail;
        $width = isset($args['width']) ? $args['width'] : '';
        $width = absint($width);
        $height = isset($args['height']) ? $args['height'] : '';
        $height = absint($height);
        if($thumbnail) {
            $autoplay = 1;
        }
        $autoplay = absint($autoplay);
        $style = '';
        if($width > 0) {
            $style .= 'width: ' . $width . 'px;';
        } else {
            $style .= 'width: 100%;';
        }
        if($height > 0) {
            $style .= 'height: ' . $height . 'px;';
        }
        $container_style = $style;
        $container_style .= 'background-color: #000;';
        $iframe = '<iframe class="player" src="//player.vimeo.com/video/' . $id . '?autoplay=' . $autoplay . '&amp;color=' . $color . '" style="' . $style . '"></iframe>';
        if($thumbnail) {
            $thumbnail_url = self::get_thumbnail_vimeo($id);
            ?>
            <div class="vimeo-video video-container show-thumbnail" data-player="<?php echo esc_html($iframe); ?>" style="<?php echo $container_style; ?>">
                <img class="video-thumbnail" src="<?php echo $thumbnail_url; ?>" alt="">
                <img class="loading" src="<?php echo SB_Theme::get_image_url('loading-youtube-dark.gif'); ?>" alt="">
            </div>
            <?php
        } else {
            echo $iframe;
        }
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