<?php
class SB_Ads {

    public static function get_by_position($position_id) {
        $transient_name = 'sb_theme_query_ads_position_' . $position_id;
        if(false === ($query = get_transient($transient_name))) {
            $args = array(
                'post_type' => SB_Core::get_post_type_ads_name(),
                'posts_per_page' => -1
            );

            $meta_item = array(
                'key' => SB_Post::build_meta_name('ads_position'),
                'value' => $position_id,
                'compare' => '='
            );
            $args = SB_Query::build_meta_query($meta_item, $args);

            $query = SB_Query::get($args);
            set_transient($transient_name, $query, 30 * MINUTE_IN_SECONDS);
        }
        return $query;
    }

    public static function show_by_position($position_id) {
        $query = SB_Ads::get_by_position($position_id);
        if($query->have_posts()) {
            $ads = array_shift($query->posts);
            $post_id = $ads->ID;
            if(self::can_show($post_id)) {
                $ads_image = self::get_image($post_id);
                $image_data = SB_Option::get_media_detail($ads_image);
                $ads_image = $image_data['url'];
                if(empty($ads_image)) {
                    $ads_code = self::get_code($post_id);
                    echo $ads_code;
                } else {
                    $ads_link = self::get_link($post_id);
                    $img = new SB_HTML('img');
                    $atts = array(
                        'src' => $ads_image,
                        'alt' => $ads->post_title
                    );
                    $img->set_attribute_array($atts);

                    if(!empty($ads_link)) {
                        $link = new SB_HTML('a');
                        $link_atts = array(
                            'title' => $ads->post_title,
                            'href' => $ads_link
                        );
                        $link->set_attribute_array($link_atts);
                        $link->set_text($img->build());
                        echo $link->build();
                    } else {
                        echo $img->build();
                    }
                }
            }
        }
    }

    public static function get_position($post_id) {
        return SB_Post::get_sb_meta($post_id, 'ads_position');
    }

    public static function is_active($post_id) {
        return (bool)SB_Post::get_sb_meta($post_id, 'ads_active');
    }

    public static function get_link($post_id) {
        return SB_Post::get_sb_meta($post_id, 'ads_link');
    }

    public static function get_code($post_id) {
        return SB_Post::get_sb_meta($post_id, 'ads_code');
    }

    public static function get_image($post_id) {
        return SB_Post::get_sb_meta($post_id, 'ads_image');
    }

    public static function get_expire_date($post_id) {
        return SB_Post::get_sb_meta($post_id, 'ads_expire_date');
    }

    public static function is_expired($post_id) {
        $expire = self::get_expire_date($post_id);
        $current = SB_Core::get_today_timestamp();
        if(!empty($expire) && (($expire - $current) < 0)) {
            return true;
        }
        return false;
    }

    public static function can_show($post_id) {
        if(self::is_active($post_id) && !self::is_expired($post_id)) {
            return true;
        }
        return false;
    }
}