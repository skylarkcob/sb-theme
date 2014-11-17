<?php
class SB_Coupon {
    public static function get_code($post_id) {
        return SB_Post::get_meta($post_id, 'wpcf-coupon-code');
    }

    public static function get_type_value($post_id) {
        return SB_Post::get_meta($post_id, 'wpcf-coupon-type-value');
    }

    public static function get_type_label($post_id) {
        return SB_Post::get_meta($post_id, 'wpcf-coupon-type-label');
    }

    public static function get_type($post_id) {
        return SB_Post::get_meta($post_id, 'wpcf-coupon-type');
    }

    public static function get_filter($post_id) {
        return SB_Post::get_terms($post_id, 'filters');
    }

    public static function is_sale($post_id) {
        $filters = self::get_filter($post_id);
        foreach($filters as $filter) {
            if($filter->slug == 'sale') {
                return true;
            }
        }
        return false;
    }

    public static function is_printable($post_id) {
        $filters = self::get_filter($post_id);
        foreach($filters as $filter) {
            if($filter->slug == 'printable') {
                return true;
            }
        }
        return false;
    }

    public static function get_store_coupon($store_id, $args = array()) {
        $defaults = array(
            'meta_query' => array(
                array(
                    'key' => '_wpcf_belongs_stores_id',
                    'value' => $store_id
                )
            )
        );
        if(isset($args['meta_query'])) {
            $args['relation'] = 'AND';
            array_push($args['meta_query'], $defaults['meta_query'][0]);
        } else {
            $args = wp_parse_args($args, $defaults);
        }
        return self::get($args);
    }

    public static function get_hint_code($post_id) {
        $code = self::get_code($post_id);
        $len = strlen($code);
        if($len > 3) {
            $len = intval($len/2);
        }
        if($len < 3) {
            $len = 3;
        }
        if($len > 10) {
            $len = 10;
        }
        $len = -$len;
        $code = substr($code, $len);
        return $code;
    }

    public static function get($args = array()) {
        $defaults = array(
            'post_type' => 'coupon'
        );
        $args = wp_parse_args($args, $defaults);
        return new WP_Query($args);
    }

    public static function get_popular($args = array()) {
        $defaults = array(
            'meta_key' => 'count-saver',
            'orderby' => 'meta_value_num'
        );
        $new_args = wp_parse_args($args, $defaults);
        $coupons = self::get($new_args);
        $paged = isset($args['paged']) ? $args['paged'] : 1;
        if($paged > 1) {
            return $coupons;
        }
        $posts_per_page = SB_Query::get_posts_per_page();
        if($coupons->post_count < ($posts_per_page/2)) {
            $posts_per_page -= $coupons->post_count;
        }
        $excludes = array();
        foreach($coupons->posts as $post) {
            array_push($excludes, $post->ID);
        }
        $args['post__not_in'] = $excludes;
        $new_coupons = self::get($args);
        $coupons->posts = array_merge($coupons->posts, $new_coupons->posts);
        $coupons->post_count = $coupons->post_count + $new_coupons->post_count;
        return $coupons;
    }

    public static function get_featured_store($args = array()) {
        $defaults = array(
            'post_type' => 'stores',
            'meta_key' => 'wpcf-featured-store',
            'meta_value' => '1'
        );
        $args = wp_parse_args($args, $defaults);
        return new WP_Query($args);
    }

    public static function get_featured_guide($args = array()) {
        $defaults = array(
            'post_type' => 'guides',
            'meta_key' => 'wpcf-featured-guide',
            'meta_value' => '1'
        );
        $args = wp_parse_args($args, $defaults);
        return new WP_Query($args);
    }

    public static function get_coupon_store($post_id) {
        $store_id = SB_Post::get_meta($post_id, '_wpcf_belongs_stores_id');
        if(intval($store_id) < 1) {
            return '';
        }
        return get_post($store_id);
    }

    public static function get_expired($post_id) {
        $timestamp = SB_Post::get_meta($post_id, 'wpcf-expiration-date');
        if(empty($timestamp)) {
            return '';
        }
        $date = date(SB_DATE_TIME_FORMAT, $timestamp);
        return $date;
    }

    public static function get_expired_day($post_id) {
        return SB_PHP::count_next_day(SB_Core::get_current_date_time(SB_DATE_TIME_FORMAT), self::get_expired($post_id));
    }

    public static function get_expired_human_diff($post_id) {
        $now = SB_Core::get_current_date_time(SB_DATE_TIME_FORMAT);
        $date = self::get_expired($post_id);
        return SB_PHP::get_next_time_diff(array('from' => $now, 'to' => $date, 'text_before' => __('in', 'sb-theme')));
    }

    public static function get_guides($args = array()) {
        $args['post_type'] = 'guides';
        return new WP_Query($args);
    }

    public static function get_stores($args = array()) {
        $args['post_type'] = 'stores';
        return new WP_Query($args);
    }

    public static function get_top_stores($args = array()) {
        $args['post_type'] = 'stores';
        $args['meta_key'] = 'followers';
        $args['orderby'] = 'meta_value_num';
        $stores = new WP_Query($args);
        if(!$stores->have_posts()) {
            unset($args['meta_key']);
            unset($args['orderby']);
            $stores = new WP_Query($args);
        }
        return $stores;
    }

    public static function get_category($args = array()) {
        return SB_Term::get('coupon-cat', $args);
    }

    public static function get_category_by_id($cat_id) {
        return get_term_by('id', $cat_id, 'coupon-cat');
    }

    public static function get_guide_banner($post_id) {
        $banner = SB_Post::get_meta($post_id, 'wpcf-guide-banner');
        return SB_PHP::get_first_image($banner);
    }

    public static function get_store_banner($post_id) {
        $banner = SB_Post::get_meta($post_id, 'wpcf-store-banner');
        $banner = SB_PHP::get_first_image($banner);
        if(empty($banner)) {
            $banner = SB_THEME_CUSTOM_URL . '/images/store-bg-1.png';
        }
        return $banner;
    }

    public static function get_current_store() {
        global $sb_current_store;
        return $sb_current_store;
    }

    public static function set_current_store($store) {
        global $sb_current_store;
        $sb_current_store = $store;
    }

    public static function update_store_follower($store_id, $user_id, $remove = false) {
        if($store_id > 0 && $user_id > 0) {
            $users = self::get_store_follower_array($store_id);
            if($remove) {
                $key = array_search($user_id, $users);
                unset($users[$key]);
                $users = implode(',', $users);
            } else {
                if(!in_array($user_id, $users)) {
                    $users = implode(',', $users);
                    $users .= ',' . $user_id;
                    $users = trim($users, ',');
                }
            }
            SB_Post::update_meta($store_id, 'followers', $users);
        }
    }

    public static function remove_store_follower($store_id, $user_id) {
        self::update_store_follower($store_id, $user_id, true);
    }

    public static function get_store_follower_array($store_id) {
        $users = SB_Post::get_meta($store_id, 'followers');
        $users = explode(',', $users);
        $users = array_filter($users);
        return $users;
    }

    public static function count_saver($post_id) {
        $count = SB_Post::get_meta($post_id, 'count-saver');
        if(empty($count) || !is_numeric($count) || $count < 0) {
            $count = 0;
        }
        return $count;
    }

    public static function update_saver($coupon_id, $user_id, $remove = false) {
        if($coupon_id > 0 && $user_id > 0) {
            $users = self::get_saver_array($coupon_id);
            $count = self::count_saver($coupon_id);
            if($remove) {
                $key = array_search($user_id, $users);
                unset($users[$key]);
                $users = implode(',', $users);
                $count--;
            } else {
                if(!in_array($user_id, $users)) {
                    $users = implode(',', $users);
                    $users .= ',' . $user_id;
                    $users = trim($users, ',');
                    $count++;
                }
            }
            SB_Post::update_meta($coupon_id, 'savers', $users);
            SB_Post::update_meta($coupon_id, 'count-saver', $count);
        }
    }

    public static function remove_saver($coupon_id, $user_id) {
        self::update_saver($coupon_id, $user_id, true);
    }

    public static function count_store_follower($store_id) {
        $users = self::get_store_follower_array($store_id);
        return count($users);
    }

    public static function get_saver_array($coupon_id) {
        $users = SB_Post::get_meta($coupon_id, 'savers');
        $users = explode(',', $users);
        $users = array_filter($users);
        return $users;
    }

    public static function count_store_coupon($store_id) {
        $result = 0;
        if($store_id > 0) {
            global $wpdb;
            $result = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_value = %d AND meta_key = %s", $store_id, '_wpcf_belongs_stores_id'));
        }
        return $result;
    }

    public static function count_guide_store($guide_id) {
        $result = 0;
        if($guide_id > 0) {
            global $wpdb;
            $result = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_value = %d AND meta_key = %s", $guide_id, '_wpcf_belongs_guides_id'));
        }
        return $result;
    }

    public static function get_store_of_guide($guide_id) {
        $result = array();
        if($guide_id > 0) {
            global $wpdb;
            $result = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %d AND meta_key = %s", $guide_id, '_wpcf_belongs_guides_id'));
            $tmp = array();
            foreach($result as $value) {
                array_push($tmp, $value->post_id);
            }
            $result = $tmp;
        }
        return $result;
    }

    public static function get_guide_description($guide_id) {
        return SB_Post::get_meta($guide_id, 'wpcf-guide-description');
    }

    public static function get_current() {
        global $sb_current_coupon;
        return $sb_current_coupon;
    }

    public static function set_current($coupon) {
        global $sb_current_coupon;
        $sb_current_coupon = $coupon;
    }

    public static function get_saving_coupon_of_user($user_id) {
        $coupon_ids = SB_User::get_saving_coupons_array($user_id);
        if(count($coupon_ids) == 0) {
            array_push($coupon_ids, 0);
        }
        return self::get(array('post__in' => $coupon_ids));
    }

    public static function get_following_store_of_user($user_id) {
        $store_ids = SB_User::get_following_stores_array($user_id);
        if(count($store_ids) == 0) {
            array_push($store_ids, 0);
        }
        return self::get_stores(array('post__in' => $store_ids));
    }

    public static function get_coupon_of_following_store($user_id) {
        $store_ids = SB_User::get_following_stores_array($user_id);
        $args = array(
            'meta_query' => array(
                array(
                    'key' => '_wpcf_belongs_stores_id',
                    'value' => $store_ids
                )
            )
        );
        return self::get($args);
    }

    public static function get_store_similar($store_id) {
        $terms = SB_Post::get_term_ids($store_id, 'coupon-cat');
        $args = array(
            'posts_per_page' => 10,
            'post__not_in' => array($store_id)
        );
        if(count($terms) > 0) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'coupon-cat',
                    'field' => 'id',
                    'terms' => $terms
                )
            );
        } else {
            $guide = self::get_guide_of_store($store_id);
            if($guide) {
                $args['meta_query'] = array(
                    array(
                        'key' => '_wpcf_belongs_guides_id',
                        'value' => $guide->ID
                    )
                );
            }
        }
        $stores = self::get_stores($args);
        return $stores;
    }

    public static function get_guide_of_store($store_id) {
        $guide_id = SB_Post::get_meta($store_id, '_wpcf_belongs_guides_id');
        return get_post($guide_id);
    }

    public static function get_store_promo_code($store_id, $args = array()) {
        $defaults = array(
            'meta_query' => array(
                array(
                    'key' => 'wpcf-coupon-type',
                    'value' => 'promo-code'
                )
            )
        );
        if(isset($args['meta_query'])) {
            array_push($args['meta_query'], $defaults['meta_query'][0]);
        } else {
            $args = wp_parse_args($args, $defaults);
        }
        $coupons = self::get_coupon_of_store($store_id, $args);
        return $coupons;
    }

    public static function get_store_online_deal($store_id, $args = array()) {
        $defaults = array(
            'meta_query' => array(
                array(
                    'key' => 'wpcf-coupon-type',
                    'value' => 'online-deal'
                )
            )
        );
        if(isset($args['meta_query'])) {
            array_push($args['meta_query'], $defaults['meta_query'][0]);
        } else {
            $args = wp_parse_args($args, $defaults);
        }
        return self::get_coupon_of_store($store_id, $args);
    }

    public static function get_store_printable_coupon($store_id, $args = array()) {
        $defaults = array(
            'meta_query' => array(
                array(
                    'key' => 'wpcf-coupon-type',
                    'value' => 'printable'
                )
            )
        );
        if(isset($args['meta_query'])) {
            array_push($args['meta_query'], $defaults['meta_query'][0]);
        } else {
            $args = wp_parse_args($args, $defaults);
        }
        $args = wp_parse_args($args, $defaults);
        $coupons = self::get_coupon_of_store($store_id, $args);
        return $coupons;
    }

    public static function get_coupon_of_store($store_id, $args = array()) {
        return self::get_store_coupon($store_id, $args);
    }

    public static function is_valid($args = array()) {
        $code = '';
        $type = '';
        $store = self::get_current_store();
        extract($args, EXTR_OVERWRITE);
        $result = true;
        if(!$store) {
            $result = false;
        }
        return $result;
    }

    public static function get_printable_code($post_id) {
        $image = SB_Post::get_meta($post_id, 'wpcf-printable-code');
        return SB_PHP::get_first_image($image);
    }

    public static function get_store_url($post_id) {
        return SB_Post::get_meta($post_id, 'wpcf-store-url');
    }

    public static function get_coupon_url($post_id) {
        $url = SB_Post::get_meta($post_id, 'wpcf-coupon-url');
        if(empty($url)) {
            $store = self::get_coupon_store($post_id);
            $url = self::get_store_url($store->ID);
        }
        return $url;
    }
}