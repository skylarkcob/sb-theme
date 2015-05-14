<?php
class SB_Post {
    public static function get_images($post_id) {
        return self::get_all_media_images($post_id);
    }

    public static function get_all_media_images($post_id) {
        $transient_name = SB_Cache::build_post_media_images_transient_name($post_id);
        if(!SB_Cache::post_images_cache() || false === ($result = get_transient($transient_name))) {
            $result = array();
            $query = SB_Query::get(array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image'));
            $files = $query->posts;
            foreach($files as $file) {
                $image_file = get_attached_file($file->ID);
                if(file_exists($image_file)) {
                    array_push($result, $file);
                }
            }
            if(SB_Cache::post_images_cache()) {
                set_transient($transient_name, $result, YEAR_IN_SECONDS);
            }
        }
        return $result;
    }

    public static function get_page_children($page_id, $posts_per_page = -1) {
        $query = SB_Query::get(array('post_type' => 'page', 'posts_per_page' => -1, 'orderby' => 'rand'));
        $child_pages = get_page_children($page_id, $query->posts);
        $page_number = count($child_pages);
        if($page_number > 1 && $page_number > $posts_per_page && $posts_per_page > 1) {
            $child_pages = array_slice($child_pages, 0, $posts_per_page);
        }
        return $child_pages;
    }

    public static function go_to_child_page_if_empty_content() {
        if(is_page()) {
            $post_id = get_the_ID();
            $current_page = get_post($post_id);
            $content = $current_page->post_content;
            if(empty($content)) {
                $childs = self::get_page_children($post_id);
                foreach($childs as $page) {
                    wp_redirect(get_permalink($page));
                    die();
                }
            }
        }
    }

    public static function get_excerpt($post_id) {
        $result = '';
        if(is_numeric($post_id) && $post_id > 0) {
            $my_post = get_post($post_id);
            if(self::is($my_post)) {
                global $post;
                $save_post = $post;
                $post = $my_post;
                setup_postdata($post);
                $result = get_the_excerpt();
                wp_reset_postdata();
                $post = $save_post;
            }
        }
        return $result;
    }

    public static function get_template_name($page_id) {
        return self::get_meta($page_id, '_wp_page_template');
    }

    public static function get_status($post_id) {
        return get_post_status($post_id);
    }

    public static function is_published($post_id) {
        if('publish' == self::get_status($post_id)) {
            return true;
        }
        return false;
    }

    public static function get_media_url($media_id) {
        return wp_get_attachment_url($media_id);
    }

    public static function get_comment_number($post_id = '') {
        if(empty($post_id)) {
            $post_id = get_the_ID();
        }
        $transient_name = SB_Cache::build_post_comment_number_transient_name($post_id);
        if(!SB_Cache::post_comment_count_cache() || false === ($comment_number = get_transient($transient_name))) {
            $comment_number = get_comments_number($post_id);
            if($comment_number == 1) {
                $comments = SB_Post::get_comments($post_id);
                $comment_number = count($comments);
            }
            if(SB_Cache::post_comment_count_cache() && !empty($post_id)) {
                set_transient($transient_name, $comment_number, DAY_IN_SECONDS);
            }
        }
        return $comment_number;
    }

    public static function the_comment_number($post_id = '') {
        echo self::get_comment_number($post_id);
    }

    public static function get_attachments($post_id) {
        return self::get_images($post_id);
    }

    public static function get_first_attachment($post_id) {
        return self::get_first_image($post_id);
    }

    public static function get_first_image($post_id) {
        $images = self::get_images($post_id);
        if(is_array($images)) {
            $image = array_shift($images);
            return $image;
        }
        return '';
    }

    public static function clean_all_revision() {
        SB_Core::delete_revision();
    }

    public static function set_post_term($post_id, $terms, $taxonomy) {
        return wp_set_post_terms($post_id, $terms, $taxonomy);
    }

    public static function auto_set_thumbnail($post_id) {
        $first_image = self::get_first_image($post_id);
        if(!is_object($first_image) || empty($first_image)) {
            $post = get_post($post_id);
            if($post) {
                $first_image = SB_PHP::get_first_image($post->post_content);
                self::set_thumbnail_from_url($post_id, $first_image);
            }
        } else {
            self::set_thumbnail($post_id, $first_image->ID);
        }
    }

    public static function get_first_image_url($post_id) {
        $image = self::get_first_image($post_id);
        $url = '';
        if($image && !is_wp_error($image)) {
            $atts = wp_get_attachment_image_src($image->ID, 'full');
            if(is_array($atts) && isset($atts[0])) {
                $url = $atts[0];
            } else {
                $url = $image->guid;
            }
        } else {
            $post = get_post($post_id);
            $url = SB_PHP::get_first_image($post->post_content);
        }
        return $url;
    }

    public static function check_duplicate_comment($commentdata) {
        if(!isset($commentdata['comment_post_ID']) || !isset($commentdata['comment_content']) || !isset($commentdata['comment_author'])) {
            return false;
        }
        if(!isset($commentdata['comment_parent'])) {
            $commentdata['comment_parent'] = 0;
        }
        global $wpdb;
        $dupe = $wpdb->prepare(
            "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_parent = %s AND comment_approved != 'trash' AND ( comment_author = %s ",
            wp_unslash($commentdata['comment_post_ID']),
            wp_unslash($commentdata['comment_parent']),
            wp_unslash($commentdata['comment_author'])
        );
        if(isset($commentdata['comment_author_email'])) {
            $dupe .= $wpdb->prepare(
                "OR comment_author_email = %s ",
                wp_unslash($commentdata['comment_author_email'])
            );
        }
        $dupe .= $wpdb->prepare(
            ") AND comment_content = %s LIMIT 1",
            wp_unslash($commentdata['comment_content'])
        );
        if($wpdb->get_var($dupe)) {
            return true;
        }
        return false;
    }

    public static function update_product_price($post_id, $price) {
        if(!is_numeric($price) || !is_numeric($post_id) || $post_id < 1) {
            return;
        }
        $sale_price = floatval(self::get_meta($post_id, '_sale_price'));
        $regular_price = floatval(self::get_meta($post_id, '_regular_price'));
        if($sale_price > 0 && $price < $regular_price) {
            self::update_meta($post_id, '_sale_price', $price);
        } else {
            self::update_meta($post_id, '_regular_price', $price);
        }
        self::update_meta($post_id, '_price', $price);
    }

    public static function plus_product_price($post_id, $price_plus) {
        if(!is_numeric($price_plus) || !is_numeric($post_id) || $post_id < 1) {
            return;
        }
        $price = floatval(self::get_meta($post_id, '_price'));
        $price += $price_plus;
        self::update_product_price($post_id, $price);
    }

    public static function minus_product_price($post_id, $price_minus) {
        if(!is_numeric($price_minus) || !is_numeric($post_id) || $post_id < 1) {
            return;
        }
        $price = floatval(self::get_meta($post_id, '_price'));
        $price -= $price_minus;
        self::update_product_price($post_id, $price);
    }

    public static function remove_duplicate_comment($post_id = 0) {
        global $wpdb;
        $query = "SELECT * FROM $wpdb->comments";
        if($post_id > 0) {
            $query .= " WHERE comment_post_ID = " . $post_id;
        }
        $comments = $wpdb->get_results($query);
        $compare_comments = $comments;
        foreach($comments as $comment) {
            $comment_id = $comment->comment_ID;
            foreach($compare_comments as $compare) {
                $compare_comment_id = $compare->comment_ID;
                if($comment_id == $compare_comment_id) {
                    continue;
                }
                if($comment->comment_content == $compare->comment_content) {
                    $query = "DELETE FROM $wpdb->comments WHERE comment_ID = " . $compare_comment_id;
                    $wpdb->query($query);
                }
            }
        }
    }

    public static function change_all_url($args = array()) {
        $query = self::get_all();
        if($query->have_posts()) {
            $my_posts = $query->posts;
            global $post;
            foreach($my_posts as $post) {
                if(SB_Core::is_error($post)) {
                    continue;
                }
                setup_postdata($post);
                $args['post_content'] = get_the_content();
                $args['post_id'] = get_the_ID();
                self::change_url_in_content($args);
            }
            wp_reset_postdata();
        }
    }

    public static function change_url_in_content($args = array()) {
        $post_id = isset($args['post_id']) ? $args['post_id'] : 0;
        $content = isset($args['post_content']) ? $args['post_content'] : '';
        if(empty($content)) {
            $post = isset($args['post']) ? $args['post'] : null;
            if(!SB_Core::is_error($post)) {
                $content = $post->post_content;
                if($post_id < 1) {
                    $post_id = $post->ID;
                }
            }
        }
        $url = isset($args['url']) ? $args['url'] : '';
        $site_url = isset($args['site_url']) ? $args['site_url'] : '';
        $content = str_replace($url, $site_url, $content);
        $post_data = array(
            'ID' => $post_id,
            'post_content' => $content
        );
        self::update($post_data);
    }

    public static function update($post_data) {
        $post_id = isset($post_data['ID']) ? intval($post_data['ID']) : 0;
        if($post_id > 0) {
            wp_update_post($post_data);
        }
    }

    public static function get_all($post_type = 'post') {
        $args = array(
            'post_type' => $post_type,
            'posts_per_page' => -1
        );
        return SB_Query::get($args);
    }

    public static function get_file_path($media_id) {
        return get_attached_file($media_id);
    }

    public static function get_thumbnail_id($post_id) {
        return get_post_thumbnail_id($post_id);
    }

    public static function get_thumbnail_detail($post_id) {
        $result = array();
        if(has_post_thumbnail($post_id)) {
            $thumbnail_id = self::get_thumbnail_id($post_id);
            $image_path = self::get_file_path($thumbnail_id);
            if(file_exists($image_path)) {
                $result = wp_get_attachment_image_src($thumbnail_id, $size);
            }
        }
        return $result;
    }

    public static function get_thumbnail_url($args = array()) {
        $post_id = isset($args['post_id']) ? absint($args['post_id']) : get_the_ID();
        $result = '';
        if($post_id > 0) {
            $size = isset($args['size']) ? $args['size'] : '';
            $size_key = is_array($size) ? implode('_', $size) : $size;
            if(empty($size_key)) {
                $size_key = 'default';
            }
            $transient_name = SB_Cache::build_post_thumbnail_url_transient_name($post_id, $size_key);
            $result = get_transient($transient_name);
            $cache = isset($args['cache']) ? $args['cache'] : true;
            if(!SB_Cache::enabled() || false === $result || !SB_PHP::is_image_url($result) || !$cache) {
                if(has_post_thumbnail($post_id)) {
                    $thumbnail_id = self::get_thumbnail_id($post_id);
                    $image_path = self::get_file_path($thumbnail_id);
                    if(file_exists($image_path)) {
                        $image_attributes = wp_get_attachment_image_src($thumbnail_id, $size);
                        if($image_attributes) {
                            $result = $image_attributes[0];
                        }
                    }
                }
                if(empty($result)) {
                    $result = apply_filters('hocwp_post_image_url', '', $post_id);
                }
                if(empty($result)) {
                    $result = self::get_first_image_url($post_id);
                }
                if(empty($result)) {
                    $result = SB_Option::get_theme_thumbnail_url();
                }
                if(SB_Cache::enabled() && $cache && !empty($result) && SB_PHP::is_image_url($result)) {
                    set_transient($transient_name, $result, WEEK_IN_SECONDS);
                }
            }
        }
        $result = apply_filters('sb_thumbnail_url', $result, $post_id);
        $result = apply_filters('sb_theme_post_thumbnail_url', $result, $post_id);
        return $result;
    }

    public static function get_thumbnail_full_url($post_id = '') {
        $result = self::get_thumbnail_url(array('post_id' => $post_id));
        $result = apply_filters('sb_thumbnail_full_url', $result, $post_id);
        $result = apply_filters('sb_theme_post_thumbnail_full_url', $result, $post_id);
        return $result;
    }

    public static function get_default_thumbnail_url() {
        return SB_CORE_URL . '/images/no-thumbnail-grey-100.png';
    }

    public static function get_thumbnail_html($args = array()) {
        $post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
        $result = '';
        if($post_id > 0) {
            $size = isset($args['size']) ? $args['size'] : '';
            $size_key = is_array($size) ? implode('_', $size) : $size;
            if(empty($size_key)) {
                $size_key = 'default';
            }
            $trasient_name = SB_Cache::build_post_thumbnail_image_transient_name($post_id, $size_key);
            $result = get_transient($trasient_name);
            $image_source = SB_PHP::get_image_source($result);
            $cache = isset($args['cache']) ? $args['cache'] : true;
            if(!SB_Cache::enabled() || false === $result || empty($image_source) || !$cache) {
                $width = isset($args['width']) ? $args['width'] : '';
                $height = isset($args['height']) ? $args['height'] : '';
                $style = isset($args['style']) ? $args['style'] : '';
                $crop = isset($args['crop']) ? (bool)$args['crop'] : false;
                $bfi_thumb = isset($args['bfi_thumb']) ? (bool)$args['bfi_thumb'] : true;
                if(is_array($size) && count($size) == 1) {
                    $size = array($size, $size);
                }
                if(is_array($size) && count($size) == 2) {
                    $width = absint($size[0]);
                    $height = absint($size[1]);
                    if(empty($style)) {
                        $style = 'width:' . $width . 'px; height:' . $height . 'px;';
                    }
                }
                $args['size'] = $size;
                $thumbnail_url = isset($args['thumbnail_url']) ? $args['thumbnail_url'] : '';
                if(empty($thumbnail_url)) {
                    $thumbnail_url = self::get_thumbnail_url($args);
                }
                if(!empty($thumbnail_url)) {
                    if($bfi_thumb) {
                        if($height < 1) {
                            $height = $width;
                        }
                        if($height > 0) {
                            $tmp = bfi_thumb($thumbnail_url, array('width' => $width, 'height' => $height, 'crop' => $crop));
                        } else {
                            $tmp = bfi_thumb($thumbnail_url, array('width' => $width, 'crop' => $crop));
                        }
                        if(!empty($tmp)) {
                            $thumbnail_url = $tmp;
                        }
                    }
                    $thumbnail_image_class = apply_filters('sb_theme_post_thumbnail_image_class', '', $post_id);
                    $thumbnail_image_class .= SB_PHP::add_string_with_space_before($thumbnail_image_class, 'wp-post-image sb-post-image img-responsive thumbnail-image');
                    $result = '<img class="' . $thumbnail_image_class . '" alt="' . get_the_title($post_id) . '" width="' . $width . '" height="' . $height . '" src="' . $thumbnail_url . '" style="' . $style . '" itemprop="image">';
                }
                if(SB_Cache::enabled() && $cache && !empty($result)) {
                    set_transient($trasient_name, $result, WEEK_IN_SECONDS);
                }
            }
        }
        $result = apply_filters('sb_thumbnail_html', $result, $post_id);
        $result = apply_filters('sb_theme_post_thumbnail_image', $result, $post_id);
        return $result;
    }

    public static function is($post) {
        if(!is_a($post, 'WP_Post') || SB_Core::is_error($post) || empty($post)) {
            return false;
        }
        return true;
    }

    public static function the_thumbnail_html($args = array()) {
        echo self::get_thumbnail_html($args);
    }

    public static function the_thumbnail_link($args = array()) {
        self::the_thumbnail($args);
    }

    public static function get_thumbnail_link($args = array()) {
        $link = isset($args['link']) ? (bool)$args['link'] : true;
        $link_class = isset($args['link_class']) ? $args['link_class'] : '';
        $link_class = SB_PHP::add_string_with_space_before($link_class, 'img-hyperlink');
        $post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
        $result = '';
        if($link) {
            $link_html = new SB_HTML('a');
            $atts = array(
                'class' => $link_class,
                'title' => get_the_title($post_id),
                'href' => get_permalink($post_id),
                'text' => self::get_thumbnail_html($args)
            );
            $link_html->set_attribute_array($atts);
            $result = $link_html->build();
        } else {
            $result = self::get_thumbnail_html($args);
        }
        return $result;
    }

    public static function the_thumbnail($args = array()) {
        echo self::get_thumbnail_link($args);
    }

    public static function the_thumbnail_only_link_image_html($args = array()) {
        self::the_thumbnail($args);
    }

    public static function the_thumbnail_only_image_html($args = array()) {
        $args['link'] = false;
        self::the_thumbnail($args);
    }

    public static function the_thumbnail_crop_html_by_id($post_id, $width, $height) {
        $args = array(
            'width' => $width,
            'height' => $height,
            'crop' => true,
            'post_id' => $post_id
        );
        self::the_thumbnail($args);
    }

    public static function the_thumbnail_crop_html($width, $height) {
        $args = array(
            'width' => $width,
            'height' => $height,
            'crop' => true
        );
        self::the_thumbnail($args);
    }

    public static function the_thumbnail_crop_only_link_image_html($width, $height) {
        $args = array(
            'width' => $width,
            'height' => $height,
            'crop' => true
        );
        self::the_thumbnail($args);
    }

    public static function the_thumbnail_crop_only_image_html($width, $height) {
        $args = array(
            'width' => $width,
            'height' => $height,
            'crop' => true
        );
        self::the_thumbnail($args);
    }

    public static function set_thumbnail($post_id, $attach_id) {
        return set_post_thumbnail($post_id, $attach_id);
    }

    public static function set_thumbnail_from_url($post_id, $image_url) {
        if(!current_theme_supports('post-thumbnails') || has_post_thumbnail($post_id) || empty($image_url)) {
            return false;
        }
        $attach_id = SB_Core::fetch_media($image_url);
        return self::set_thumbnail($post_id, $attach_id);
    }

    public static function get_author_url() {
        return get_author_posts_url(get_the_author_meta('ID'));
    }

    public static function the_author() {
        self::the_author_link();
    }

    public static function get_the_date($format = '') {
        if(empty($format)) {
            $format = SB_Option::get_date_format();
        }
        $post_date = get_the_date($format);
        if(empty($post_date)) {
            $post_id = get_the_ID();
            $post = get_post($post_id);
            $post_date = $post->post_date_gmt;
            $post_date = date($format, strtotime($post_date));
        }
        return $post_date;
    }

    public static function the_title($args = array()) {
        $post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
        $link = isset($args['link']) ? $args['link'] : true;
        $headline = isset($args['headline']) ? $args['headline'] : '';
        if(empty($headline) && (is_singular() || is_page())) {
            $current_id = get_the_ID();
            if($current_id == $post_id) {
                $headline = 'h1';
            }
        }
        if(empty($headline)) {
            $headline = 'h2';
        }
        $title = new SB_HTML($headline);
        $title->set_attribute('class', 'entry-title');
        $title->set_attribute('itemprop', 'headline');

        $title_text = '';
        if($link) {
            $post_link = new SB_HTML('a');
            $post_link->set_attribute('itemprop', 'url');
            $post_link->set_attribute('rel', 'bookmark');
            $post_link->set_attribute('href', get_permalink($post_id));
            $post_link->set_text(get_the_title($post_id));
            $title_text = $post_link->build();
        } else {
            $title_text = get_the_title($post_id);
        }
        $title->set_text($title_text);
        echo $title->build();
    }

    public static function get_date_meta($date_format = '', $has_time = false, $time_format = '') {
        $post_date = self::get_the_date($date_format);
        $time = new SB_HTML('time');
        $date_class = apply_filters('sb_theme_date_class', '');
        $date_class = SB_PHP::add_string_with_space_before($date_class, 'entry-published date updated post-date a-post-meta');
        if($has_time) {
            $post_date = SB_PHP::add_string_with_space_before($post_date, get_the_time($time_format));
        }
        $atts = array(
            'title' => get_the_date('l, F d, Y, h:i a'),
            'datetime' => get_the_time('c', false),
            'class' => $date_class,
            'text' => $post_date
        );
        $time->set_attribute_array($atts);
        return $time->build();
    }

    public static function the_date($date_format = '', $has_time = false, $time_format = '') {
        echo self::get_date_meta($date_format, $has_time, $time_format);
    }

    public static function the_date_time($date_format = '', $time_format = '') {
        self::the_date($date_format, true, $time_format);
    }

    public static function get_time_compare($post) {
        return get_post_time('G', false, $post);
    }

    public static function get_human_minute_diff($post) {
        return SB_Core::get_human_minute_diff(self::get_time_compare($post));
    }

    public static function get_human_time_diff($post) {
        return SB_Core::get_human_time_diff(self::get_time_compare($post));
    }

    public static function get_first_term($post_id, $taxonomy) {
        $terms = self::get_terms($post_id, $taxonomy);
        $first_term = new WP_Error();
        if(is_array($terms) && count($terms) > 0) {
            $first_term = array_shift($terms);
        }
        return $first_term;
    }

    public static function get_types($args = array(), $output = 'names', $operator = 'and') {
        $args['public'] = true;
        return get_post_types($args, $output, $operator);
    }

    public static function update_custom_menu_url($post_id, $meta_value) {
        self::update_meta($post_id, '_menu_item_url', $meta_value);
    }

    public static function get_comment_link_meta($post_id = 0) {
        if($post_id == 0) {
            $post_id = get_the_ID();
        }
        $result = '';
        $comment_number = self::get_comment_number($post_id);
        $comment_link = get_comments_link($post_id);
        if(!post_password_required() && (comments_open() || get_comments_number())) {
            $span = new SB_HTML('span');
            $span->set_attribute('class', 'comments-link-container post-comment a-post-meta');

            $span_text = '<i class="fa fa-comments icon-left"></i>';

            $link = new SB_HTML('a');
            $link->set_attribute('class', 'comments-link');
            $link->set_attribute('href', $comment_link);
            $link->set_attribute('itemprop', 'discussionURL');
            $link->set_text('<span class="count">' . $comment_number . '</span> <span class="text">' . SB_Message::get_comment() . '</span>');

            $span_text .= $link->build();

            $span->set_text($span_text);

            $result = $span->build();
        }
        return $result;
    }

    public static function the_comment_link($post_id = 0) {
        echo self::get_comment_link_meta($post_id);
    }

    public static function get_tag_ids($post_id) {
        $tags = (array)wp_get_post_tags($post_id, array('fields' => 'ids'));
        return $tags;
    }

    public static function get_category_ids($post_id) {
        return wp_get_post_categories($post_id, array('fields' => 'ids'));
    }

    public static function get_term_ids($post_id, $taxonomy) {
        return wp_get_post_terms($post_id, $taxonomy, array('fields' => 'ids'));
    }

    public static function get_terms($post_id, $taxonomy) {
        return wp_get_post_terms($post_id, $taxonomy);
    }

    public static function get_next_post_url() {
        $result = '';
        $post = get_adjacent_post(false, '', true);
        if(!SB_Core::is_error($post)) {
            $result = get_permalink($post);
        }
        return $result;
    }

    public static function get_previous_post_url() {
        $result = '';
        $post = get_adjacent_post(false, '', false);
        if(!SB_Core::is_error($post)) {
            $result = get_permalink($post);
        }
        return $result;
    }

    public static function get_meta($post_id, $meta_key) {
        return get_post_meta($post_id, $meta_key, true);
    }

    public static function get_sb_meta($post_id, $meta_key) {
        $meta_key = sb_build_meta_name($meta_key);
        return self::get_meta($post_id, $meta_key);
    }

    public static function update_meta($post_id, $meta_key, $meta_value) {
        update_post_meta($post_id, $meta_key, $meta_value);
    }

    public static function update_sb_meta($post_id, $meta_key, $meta_value) {
        $meta_key = sb_build_meta_name($meta_key);
        self::update_meta($post_id, $meta_key, $meta_value);
    }

    public static function build_meta_name($meta_key) {
        return sb_build_meta_name($meta_key);
    }

    public static function get_transaction_by_secure_code($secure_code, $args = array()) {
        $transient_name = SB_Cache::build_transaction_secure_code_transient_name($secure_code);
        if(false === ($query = get_transient($transient_name))) {
            $args['post_type'] = 'transaction';
            $args['post_status'] = 'any';
            $args['posts_per_page'] = 1;
            $meta_item = array(
                'key' => self::build_meta_name('ngan_luong_secure_code'),
                'value' => $secure_code
            );
            $args = SB_Query::build_meta_query($meta_item, $args);
            $query = SB_Query::get($args);
            set_transient($transient_name, $query, DAY_IN_SECONDS);
        }
        return $query;
    }

    public static function get_private_post_types() {
        global $sb_theme_private_post_types;
        $sb_theme_private_post_types = (array)$sb_theme_private_post_types;
        return apply_filters('sb_theme_private_post_types', $sb_theme_private_post_types);
    }

    public static function comment_closed($post_id) {
        $result = false;
        if(!comments_open($post_id)) {
            $result = true;
        }
        return $result;
    }

    public static function get_views($post_id) {
        $views = self::get_meta($post_id, 'views');
        if(empty($views)) {
            $views = 0;
        }
        return $views;
    }

    public static function insert_comment($post_id, $comment_data) {
        $comment_data['comment_post_ID'] = $post_id;
        $comment_id = 0;
        if(!self::check_duplicate_comment($comment_data)) {
            $comment_id = wp_insert_comment($comment_data);
            $comment_id = intval($comment_id);
        }
        return $comment_id;
    }

    public static function the_temperature($post_id) {
        $temp = self::get_temperature($post_id);
        echo '<span class="temperature"><i class="fa fa-fire icon-left"></i><span class="count">' . $temp . '°</span></span>';
    }

    public static function the_temperature_html($post_id) {
        $class = 'item-temp';
        $temp = SB_Post::get_temperature($post_id);
        if($temp < 50) {
            $class = SB_PHP::add_string_with_space_before($class, 'temp-50');
        } elseif($temp >= 50 && $temp < 250) {
            $class = SB_PHP::add_string_with_space_before($class, 'temp-100');
        } elseif($temp >= 250 && $temp < 500) {
            $class = SB_PHP::add_string_with_space_before($class, 'temp-250');
        } elseif($temp >= 500 && $temp < 700) {
            $class = SB_PHP::add_string_with_space_before($class, 'temp-500');
        } else {
            $class = SB_PHP::add_string_with_space_before($class, 'temp-1000');
        }
        ?>
        <div class="<?php echo $class; ?>"><?php SB_Post::the_temperature($post_id); ?></div>
        <?php
    }

    public static function get_temperature($post_id) {
        $result = 0;
        $views = self::get_views($post_id);
        if(0 < $views) {
            $result = sqrt($views);
            $result *= 5;
            $result = ceil($result);
        }
        $comment_count = self::get_comment_number($post_id);
        $result += $comment_count;
        if(1 > $result) {
            $result = 1;
        } elseif(1000000 < $result) {
            $result = 1000000;
        }
        return $result;
    }

    public static function update_views($post_id) {
        $views = self::get_views($post_id);
        $views++;
        self::update_meta($post_id, 'views', $views);
        $views_week = intval(self::get_meta($post_id, 'views_week'));
        $new_week = intval(get_option('sb_new_week'));
        if(SB_PHP::is_monday() && $new_week != 1) {
            $views_week = 0;
            update_option('sb_new_week', 1);
        } else {
            if(!SB_PHP::is_monday() && $new_week != 0) {
                update_option('sb_new_week', 0);
            }
        }
        $views_week++;
        self::update_meta($post_id, 'views_week', $views_week);
    }

    public static function update_metas($post_id, $metas = array()) {
        foreach($metas as $meta) {
            $meta_key = isset($meta['key']) ? $meta['key'] : '';
            $meta_value = isset($meta['value']) ? $meta['value'] : '';
            if(empty($meta_key)) {
                continue;
            }
            self::update_meta($post_id, $meta_key, $meta_value);
        }
    }

    public static function get_menu_custom_items() {
        $result = array();
        $transient_name = SB_Cache::build_custom_menu_transient_name();
        if(!SB_Cache::enabled() || false === ($result = get_transient($transient_name))) {
            $menus = wp_get_nav_menus();
            if(!is_array($menus)) {
                return;
            }
            foreach($menus as $menu) {
                $menu_items = wp_get_nav_menu_items($menu->term_id);
                if(!is_array($menu_items)) {
                    continue;
                }
                foreach($menu_items as $item) {
                    if('custom' == $item->type) {
                        array_push($result, $item);
                    }
                }
            }
            if(SB_Cache::enabled() && count($result) > 0) {
                set_transient($transient_name, $result, YEAR_IN_SECONDS);
            }
        }
        return $result;
    }

    public static function get_menu_items_by_location($location) {
        $locations = SB_Core::get_menu_location();
        $menu_id = isset($locations[$location]) ? $locations[$location] : 0;
        return wp_get_nav_menu_items($menu_id);
    }

    public static function get_sticky_post_ids() {
        $ids = get_option('sticky_posts');
        return (array)$ids;
    }

    public static function change_custom_menu_url($args = array()) {
        $site_url = isset($args['site_url']) ? $args['site_url'] : '';
        $url = isset($args['url']) ? $args['url'] : '';
        if(empty($url)) {
            $url = SB_Option::get_site_url();
        }
        if(empty($site_url) || $url == $site_url) {
            return;
        }
        $menu_items = self::get_menu_custom_items();
        if(!is_array($menu_items)) {
            return;
        }
        foreach($menu_items as $item) {
            if('trang-chu' == $item->post_name || 'home' == $item->post_name) {
                $item_url = $item->url;
                $item_url = mb_ereg_replace($url, $site_url, $item_url);
                SB_Post::update_custom_menu_url($item->ID, $item_url);
            }
        }
    }

    public static function get_by_slug($slug, $post_type = 'post') {
        return get_page_by_path($slug, OBJECT, $post_type);
    }

    public static function add($args = array()) {
        self::insert($args);
    }

    public static function get_administrative_boundaries($post_id) {
        $result = array(
            'province' => 0,
            'district' => 0,
            'ward' => 0,
            'hamlet' => 0,
            'street' => 0
        );
        $term = self::get_first_term($post_id, 'province');
        if(is_object($term) && !is_wp_error($term)) {
            $result['province'] = $term->term_id;
        }
        $term = self::get_first_term($post_id, 'district');
        if(is_object($term) && !is_wp_error($term)) {
            $result['district'] = $term->term_id;
        }
        $term = self::get_first_term($post_id, 'ward');
        if(is_object($term) && !is_wp_error($term)) {
            $result['ward'] = $term->term_id;
        }
        $term = self::get_first_term($post_id, 'hamlet');
        if(is_object($term) && !is_wp_error($term)) {
            $result['hamlet'] = $term->term_id;
        }
        $term = self::get_first_term($post_id, 'street');
        if(is_object($term) && !is_wp_error($term)) {
            $result['street'] = $term->term_id;
        }
        return $result;
    }

    public static function insert($args = array()) {
        $post_title = '';
        $post_content = '';
        $post_status = 'pending';
        $post_type = 'post';
        $post_author = 1;
        $first_admin = SB_User::get_first_admin();
        if($first_admin) {
            $post_author = $first_admin->ID;
        }
        $defaults = array(
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_status'           => $post_status,
            'post_type'             => $post_type,
            'post_author'           => $post_author,
            'ping_status'           => get_option('default_ping_status'),
            'post_parent'           => 0,
            'menu_order'            => 0,
            'to_ping'               =>  '',
            'pinged'                => '',
            'post_password'         => '',
            'guid'                  => '',
            'post_content_filtered' => '',
            'post_excerpt'          => '',
            'import_id'             => 0
        );
        $args = wp_parse_args($args, $defaults);
        $args['post_title'] = wp_strip_all_tags($args['post_title']);
        $post_id = wp_insert_post($args);
        return $post_id;
    }

    public static function get_all_image_from_content($content) {
        return SB_PHP::get_all_image_html_from_string($content);
    }

    public static function get_all_image_html_from_content($content) {
        return SB_PHP::get_all_image_html_from_string($content);
    }

    public static function get_author_link() {
        $user_nicename = get_the_author_meta('user_nicename');

        $author_name = new SB_HTML('span');
        $atts = array(
            'itemprop' => 'name',
            'text' => $user_nicename
        );
        $author_name->set_attribute_array($atts);

        $author_link = new SB_HTML('a');
        $atts = array(
            'class' => 'url fn n',
            'itemprop' => 'url',
            'text' => $author_name->build(),
            'rel' => 'author',
            'href' => esc_url( self::get_author_url()),
            'title' => sprintf(SB_Message::get_posts_by(), $user_nicename)
        );
        $author_link->set_attribute_array($atts);

        $span = new SB_HTML('span');
        $atts = array(
            'class' => 'post-author entry-author a-post-meta',
            'itemtype' => 'http://schema.org/Person',
            'itemscope' => 'itemscope',
            'itemprop' => 'author',
            'text' => '<i class="fa fa-user icon-left"></i> <span>' . $author_link->build() . '</span>'
        );
        $span->set_attribute_array($atts);

        return $span->build();
    }

    public static function the_author_link() {
        echo self::get_author_link();
    }

    public static function get_comments($post_id, $args = array()) {
        $args['post_id'] = $post_id;
        return get_comments($args);
    }

    public static function get_rate_average($post_id, $precision = 2) {
        $value = floatval(self::get_meta($post_id, 'rate'));
        $value = round($value, $precision);
        return $value;
    }

    public static function update_rate_average($post_id, $score) {
        $old_score = self::get_rate_average($post_id);
        $value = $old_score + $score;
        if($old_score > 0) {
            $value /= 2;
        }
        update_post_meta($post_id, 'rate', $value);
        return $value;
    }

    public static function get_rate_count($post_id) {
        return intval(self::get_meta($post_id, 'rates'));
    }

    public static function update_rate_count($post_id) {
        $count = self::get_rate_count($post_id);
        $count++;
        update_post_meta($post_id, 'rates', $count);
        return $count;
    }

    public static function build_transient_name_prefix($post_id) {
        return SB_Cache::build_post_transient_name($post_id);
    }

    public static function the_term($post_id, $taxonomy, $before = '', $sep = ', ', $after = '') {
        echo self::get_term_list_html($post_id, $taxonomy, $before, $sep, $after);
    }

    public static function get_tag_list_html($post_id, $before = '', $sep = ', ', $after = '') {
        $taxonomy = 'post_tag';
        $transient_name = SB_Cache::build_post_term_list_transient_name($post_id, $taxonomy);
        if(!SB_Cache::enabled() || false === ($term_list = get_transient($transient_name))) {
            $before = '<span class="entry-terms ' . $taxonomy . '" itemprop="keywords"><span class="entry-utility-prep">' . SB_Message::get_category() . ': </span>';
            $after = '</span>';
            $term_list = get_the_tag_list($before, ', ', $after, $post_id);
            if(SB_Cache::enabled() && !is_wp_error($term_list)) {
                set_transient($transient_name, $term_list, 4 * WEEK_IN_SECONDS);
            }
        }
        return $term_list;
    }

    public static function the_tags($post_id, $before = '', $sep = ', ', $after = '') {
        echo self::get_tag_list_html($post_id, $before, $sep, $after);
    }

    public static function get_term_list_html($post_id, $taxonomy, $before = '', $sep = ', ', $after = '') {
        $transient_name = SB_Cache::build_post_term_list_transient_name($post_id, $taxonomy);
        if(!SB_Cache::enabled() || false === ($term_list = get_transient($transient_name))) {
            $before = '<span class="cat-links entry-terms ' . $taxonomy . '" itemprop="articleSection"><span class="entry-utility-prep">' . SB_Message::get_category() . ': </span>';
            $after = '</span>';
            $term_list = get_the_term_list($post_id, $taxonomy, $before, ', ', $after);
            if(SB_Cache::enabled() && !is_wp_error($term_list)) {
                set_transient($transient_name, $term_list, 4 * WEEK_IN_SECONDS);
            }
        }
        return $term_list;
    }

    public static function the_term_html($post_id, $taxonomy, $before = '', $sep = ', ', $after = '') {
        echo self::get_term_list_html($post_id, $taxonomy, $before, $sep, $after);
    }

    public static function the_category($post_id = '', $before = '', $sep = ', ', $after = '') {
        $post_id = absint($post_id);
        if(1 > $post_id) {
            $post_id = get_the_ID();
        }
        echo self::get_term_list_html($post_id, 'category', $before, $sep, $after);
    }

    public static function the_term_name($post_id, $taxonomy, $args = array()) {
        $args['link'] = false;
        self::the_term_link($post_id, $taxonomy, $args);
    }

    public static function the_term_link($post_id, $taxonomy, $args = array()) {
        $separator = isset($args['separator']) ? $args['separator'] : ', ';
        $number = isset($args['number']) ? $args['number'] : -1;
        $link = isset($args['link']) ? $args['link'] : true;
        $top_level = isset($args['top_level']) ? $args['top_level'] : false;
        $terms = self::get_terms($post_id, $taxonomy);
        $result = '';
        $count = 0;
        foreach($terms as $term) {
            if($top_level && $term->parent > 0) {
                continue;
            }
            if($link) {
                $result .= sprintf('<a href="%1$s">%2$s</a>', get_term_link($term), $term->name);
            } else {
                $result .= $term->name;
            }
            $result .= $separator;
            $count++;
            if($number > 0 && $count >= $number) {
                break;
            }
        }
        $result = trim($result, $separator);
        if(empty($result)) {
            $term = array_shift($terms);
            if(!SB_Core::is_error($term)) {
                $result = $term->name;
            }
        }
        echo $result;
    }
}