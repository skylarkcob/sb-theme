<?php
class SB_Coupon {
    public static function get_coupon_type_slug() {
        return apply_filters('sb_theme_coupon_type_slug', 'ct');
    }

    public static function get_coupon_season_slug() {
        return apply_filters('sb_theme_coupon_season_slug', 'cs');
    }

    public static function install_post_type_and_taxonomy() {
        $args = array(
            'name' => __('Stores', 'sb-theme'),
            'singular_name' => __('Store', 'sb-theme'),
            'slug' => 'store',
            'taxonomies' => array('category'),
            'supports' => array('thumbnail', 'excerpt', 'editor', 'comments')
        );
        SB_Core::register_post_type($args);

        $args = array(
            'name' => __('Coupons', 'sb-theme'),
            'singular_name' => __('Coupon', 'sb-theme'),
            'slug' => 'coupon',
            'taxonomies' => array('category', 'post_tag', self::get_coupon_type_slug()),
            'supports' => array('thumbnail', 'excerpt', 'editor', 'comments')
        );
        SB_Core::register_post_type($args);

        $args = array(
            'name' => __('Types', 'sb-theme'),
            'singular_name' => __('Type', 'sb-theme'),
            'slug' => self::get_coupon_type_slug(),
            'post_types' => array('coupon')
        );
        SB_Core::register_taxonomy($args);

        $args = array(
            'name' => __('Seasons', 'sb-theme'),
            'singular_name' => __('Season', 'sb-theme'),
            'slug' => self::get_coupon_season_slug(),
            'post_types' => array('coupon')
        );
        SB_Core::register_taxonomy($args);
    }

    public static function get_featured_coupons($args = array()) {
        $args['meta_key'] = sb_build_meta_name('featured');
        $args['post_type'] = 'coupon';
        $args['meta_value'] = 1;
        return SB_Query::get($args);
    }

    public static function get_coupon_store_id($post_id) {
        return SB_Post::get_sb_meta($post_id, 'store');
    }

    public static function get_just_for_you_page() {
        $page = SB_Post::get_by_slug('justforyou', 'page');
        if(SB_Core::is_error($page) || !$page) {
            $page = SB_Post::get_by_slug('just-for-you', 'page');
        }
        return $page;
    }

    public static function get_season() {
        $term_id = SB_Option::get_theme_option_single_key('season');
        $term_id = intval($term_id);
        return SB_Term::get_by('id', $term_id, self::get_coupon_season_slug());
    }

    public static function get_coupon_season($coupon_id) {
        $terms = SB_Post::get_terms($coupon_id, self::get_coupon_season_slug());
        $result = array_shift($terms);
        return $result;
    }

    public static function get_current_event() {
        return self::get_season();
    }

    public static function get_events( $args = array() ) {
        return SB_Term::get( self::get_coupon_season_slug(), $args );
    }

    public static function get_types($args = array()) {
        return SB_Term::get(self::get_coupon_type_slug(), $args);
    }

    public static function get_favorites_page() {
        return SB_Post::get_by_slug('favorites', 'page');
    }

    public static function get_saved_page() {
        return SB_Post::get_by_slug('saved', 'page');
    }

    public static function get_blog_page() {
        return SB_Core::get_blog_page();
    }

    public static function get_code($post_id) {
        $code = SB_Post::get_sb_meta($post_id, 'code');
        if(empty($code)) {
            $code = SB_Post::get_meta($post_id, 'wpcf-coupon-code');
        }
        return trim($code);
    }

    public static function get_type_value($post_id) {
        return SB_Post::get_meta($post_id, 'wpcf-coupon-type-value');
    }

    public static function get_type_label($post_id) {
        return SB_Post::get_meta($post_id, 'wpcf-coupon-type-label');
    }

    public static function get_type($post_id) {
        $type = SB_Post::get_first_term($post_id, self::get_coupon_type_slug());
        if((empty($type) || SB_Core::is_error($type)) && SB_Core::is_wpcf_installed()) {
            $type = SB_Post::get_meta($post_id, 'wpcf-coupon-type');
        }
        return $type;
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
        return self::get_coupon_from_stores($store_id, $args);
    }

    public static function get_coupon_from_category($term_id, $args = array()) {
        $args['cat'] = $term_id;
        return self::get($args);
    }

    public static function get_coupon_from_stores($store_ids, $args = array()) {
        $store_ids = (array)$store_ids;
        $defaults = array(
            'meta_query' => array(
                'relation' => 'OR'
            )
        );
        foreach($store_ids as $store_id) {
            $meta_item = array(
                'key' => sb_build_meta_name('store'),
                'value' => $store_id
            );
            array_push($defaults['meta_query'], $meta_item);
            $meta_item = array(
                'key' => '_wpcf_belongs_stores_id',
                'value' => $store_id
            );
            array_push($defaults['meta_query'], $meta_item);
        }
        if(isset($args['meta_query'])) {
            array_push($args['meta_query'], $defaults['meta_query']);
            $args['relation'] = isset($args['meta_query']['relation']) ? $args['meta_query']['relation'] : $defaults['meta_query']['relation'];
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
            'post_type' => 'coupon',
            'post_status' => 'publish'
        );
        $args = wp_parse_args($args, $defaults);
        return SB_Query::get($args);
    }

    public static function get_popular($args = array()) {
        $defaults = array(
            'meta_key' => 'count_saver',
            'orderby' => 'meta_value_num'
        );
        $new_args = wp_parse_args($args, $defaults);
        $coupons = self::get($new_args);
        if(!$coupons->have_posts()) {
            $defaults = array(
                'meta_key' => 'count-saver',
                'orderby' => 'meta_value_num'
            );
            $new_args = wp_parse_args($args, $defaults);
            $coupons = self::get($new_args);
        }
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

    public static function get_featured_guide($args = array()) {
        $defaults = array(
            'post_type' => 'guides',
            'meta_key' => 'wpcf-featured-guide',
            'meta_value' => '1'
        );
        $args = wp_parse_args($args, $defaults);
        return SB_Query::get($args);
    }

    public static function get_coupon_store($post_id) {
        $store_id = self::get_coupon_store_id($post_id);
        if(absint($store_id) < 1) {
            $store_id = SB_Post::get_meta($post_id, '_wpcf_belongs_stores_id');
        }
        return get_post(absint($store_id));
    }

    public static function get_short_title($post) {
        if(!SB_Post::is($post)) {
            return '';
        }
        $title = SB_Post::get_sb_meta($post->ID, 'short_title');
        if(empty($title)) {
            $title = $post->post_title;
        }
        return $title;
    }

    public static function get_type_text($post_id) {
        $type_text = __('Coupon Code', 'sb-theme');
        $term = SB_Post::get_first_term($post_id, self::get_coupon_type_slug());
        if(!SB_Core::is_error($term)) {
            $type_text = $term->name;
        }
        return $type_text;
    }

    public static function get_first_type($post_id) {
        $term = SB_Post::get_first_term($post_id, self::get_coupon_type_slug());
        if(!SB_Core::is_error($term)) {
            return $term;
        }
        $term = SB_Term::get_by('slug', 'coupon-codes', self::get_coupon_type_slug());
        return $term;
    }

    public static function get_type_short_text($post_id) {
        $type = __('Coupon code', 'sb-theme');
        $term = self::get_first_type($post_id);
        if(!SB_Core::is_error($term)) {
            $slug = $term->slug;
            switch($slug) {
                case 'printable-coupons':
                    $type = __('Printable', 'sb-theme');
                    break;
                case 'product-deals':
                    $type = __('Sale', 'sb-theme');
                    break;
            }
        }
        return $type;
    }

    public static function get_type_code_text($post_id) {
        $type = 'code';
        $term = self::get_first_type($post_id);
        if(!SB_Core::is_error($term)) {
            $slug = $term->slug;
            switch($slug) {
                case 'printable-coupons':
                    $type = 'printable';
                    break;
                case 'product-deals':
                    $type = 'sale';
                    break;
                case 'free-shipping':
                    $type = 'free';
                    break;
            }
        }
        return $type;
    }

    public static function get_action_button_text($post_id) {
        $args = array(
            'code' => __('Get code', 'sb-theme'),
            'printable' => __('Show coupon', 'sb-theme'),
            'sale' => __('Shop sale', 'sb-theme'),
            'free' => __('Shop free', 'sb-theme')
        );
        $args = apply_filters('sb_coupon_action_button_text', $args);
        $type = self::get_type_code_text($post_id);
        if(isset($args[$type])) {
            return $args[$type];
        }
    }

    public static function get_expire_timestamp($post_id) {
        $timestamp = SB_Post::get_sb_meta($post_id, 'expire');
        if(empty($timestamp)) {
            $timestamp = SB_Post::get_meta($post_id, 'wpcf-expiration-date');
        }
        return $timestamp;
    }

    public static function get_expired($post_id, $format = SB_DATE_TIME_FORMAT) {
        $timestamp = self::get_expire_timestamp($post_id);
        if(empty($timestamp)) {
            return '';
        }
        $date = date($format, $timestamp);
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
        return SB_Query::get($args);
    }

    public static function get_vote_up($post_id) {
        $result = absint(SB_Post::get_meta($post_id, 'vote_up'));
        return $result;
    }

    public static function get_vote_down($post_id) {
        $result = absint(SB_Post::get_meta($post_id, 'vote_down'));
        return $result;
    }

    public static function get_success_percentage($post_id) {
        $vote_up = self::get_vote_up($post_id);
        $vote_down = self::get_vote_down($post_id);
        $vote_total = $vote_up + $vote_down;
        if($vote_total < 1) {
            return 0;
        } else {
            $result = ($vote_up - $vote_down) / $vote_total;
            $result *= 100;
            return $result;
        }
    }

    public static function update_vote($post_id, $vote_up = true) {
        if($vote_up) {
            $vote = self::get_vote_up($post_id);
            $vote++;
            SB_Post::update_meta($post_id, 'vote_up', $vote);
        } else {
            $vote = self::get_vote_down($post_id);
            $vote++;
            SB_Post::update_meta($post_id, 'vote_down', $vote);
        }
    }

    public static function get_stores($args = array()) {
        $args['post_type'] = 'store';
        $query = SB_Query::get($args);
        if(!$query->have_posts()) {
            $args['post_type'] = 'stores';
            $query = SB_Query::get($args);
        }
        return $query;
    }

    public static function get_top_stores($args = array()) {
        $args['meta_key'] = 'followers';
        $args['orderby'] = 'meta_value_num';
        $stores = self::get_stores($args);
        if(!$stores->have_posts()) {
            unset($args['meta_key']);
            unset($args['orderby']);
            $stores = self::get_stores($args);
        }
        return $stores;
    }

    public static function get_top_stores_by_category($term_id, $args = array()) {
        $args['cat'] = $term_id;
        return self::get_top_stores($args);
    }

    public static function get_category($args = array()) {
        $terms = SB_Term::get('category', $args);
        if(!SB_PHP::is_array_has_value($terms)) {
            $terms = SB_Term::get('coupon-cat', $args);
        }
        return $terms;
    }

    public static function get_category_by_id($cat_id) {
        $term = SB_Term::get_by('id', $cat_id, 'category');
        if(SB_Core::is_error($term)) {
            $term = SB_Term::get_by('id', $cat_id, 'coupon-cat');
        }
        return $term;
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
            } else {
                if(!in_array($user_id, $users)) {
                    if(SB_Core::is_wpcf_installed()) {
                        $users = implode(',', $users);
                        $users .= ',' . $user_id;
                        $users = trim($users, ',');
                    } else {
                        array_push($users, $user_id);
                    }
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
        if(SB_Core::is_wpcf_installed()) {
            $users = explode(',', $users);
        }
        if(!is_array($users) && SB_PHP::is_string_contain($users, '[')) {
            $users = SB_PHP::json_string_to_array($users);
        }
        if(is_array($users)) {
            $users = array_filter($users);
        }
        return (array)$users;
    }

    public static function count_saver($post_id) {
        $count = SB_Post::get_meta($post_id, 'count_saver');
        if(empty($count) || $count == 0) {
            $count = SB_Post::get_meta($post_id, 'count-saver');
        }
        $count = absint($count);
        return $count;
    }

    public static function update_coupon_saver($coupon_id, $user_id, $remove = false) {
        self::update_saver($coupon_id, $user_id, $remove);
    }

    public static function update_saver($coupon_id, $user_id, $remove = false) {
        if($coupon_id > 0 && $user_id > 0) {
            $users = self::get_saver_array($coupon_id);
            $count = self::count_saver($coupon_id);
            if($remove) {
                $key = array_search($user_id, $users);
                unset($users[$key]);
            } else {
                if(!in_array($user_id, $users)) {
                    array_push($users, $user_id);
                    $count++;
                }
            }
            SB_Post::update_meta($coupon_id, 'savers', $users);
            SB_Post::update_meta($coupon_id, 'count_saver', $count);
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
        if(!is_array($users) && SB_Core::is_wpcf_installed()) {
            $users = explode(',', $users);
        }
        if(is_array($users)) {
            $users = array_filter($users);
        }
        return (array)$users;
    }

    public static function count_store_coupon($store_id) {
        $result = 0;
        if($store_id > 0) {
            global $wpdb;
            $result = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_value = %d AND (meta_key = %s OR meta_key = %s)", $store_id, '_wpcf_belongs_stores_id', sb_build_meta_name('store')));
        }
        return absint($result);
    }

    public static function count_guide_store($guide_id) {
        $result = 0;
        if($guide_id > 0) {
            global $wpdb;
            $result = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_value = %d AND (meta_key = %s OR meta_key = %s)", $guide_id, '_wpcf_belongs_guides_id', sb_build_meta_name('guide')));
        }
        return absint($result);
    }

    public static function get_store_of_guide($guide_id) {
        $result = array();
        if($guide_id > 0) {
            global $wpdb;
            $result = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %d AND (meta_key = %s OR meta_key = %s)", $guide_id, '_wpcf_belongs_guides_id', sb_build_meta_name('guide')));
            $tmp = array();
            foreach($result as $value) {
                array_push($tmp, $value->post_id);
            }
            $result = $tmp;
        }
        return $result;
    }

    public static function get_guide_description($guide_id) {
        $result = SB_Post::get_sb_meta($guide_id, 'description');
        if(empty($result)) {
            $result = SB_Post::get_meta($guide_id, 'wpcf-guide-description');
        }
        return $result;
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

    public static function get_coupon_of_following_store($user_id = 0, $args = array()) {
        $store_ids = self::get_user_favorite_stores();
        $query = self::get_coupon_from_stores($store_ids, $args);
        if(!$query->have_posts()) {
            $args = array(
                'meta_query' => array(
                    array(
                        'key' => '_wpcf_belongs_stores_id',
                        'value' => $store_ids
                    )
                )
            );
            $query = self::get($args);
        }
        return $query;
    }

    public static function get_coupon_guest_viewed() {
        $value = isset($_SESSION['coupon_guest_viewed']) ? $_SESSION['coupon_guest_viewed'] : '';
        if(!empty($value)) {
            $value = SB_PHP::json_string_to_array($value);
            $value = array_filter($value);
        } else {
            $value = array();
        }
        return $value;
    }

    public static function set_coupon_guest_viewed($post_id, $remove = false) {
        $result = self::get_coupon_guest_viewed();
        if($remove) {
            $key = array_search($post_id, $result);
            unset($result[$key]);
        } else {
            if(!in_array($post_id, $result)) {
                array_push($result, $post_id);
            }
        }
        $_SESSION['coupon_guest_viewed'] = json_encode($result);
        return $result;
    }

    public static function get_store_guest_favorite() {
        $value = isset($_SESSION['store_guest_favorite']) ? $_SESSION['store_guest_favorite'] : '';
        if(!empty($value)) {
            $value = SB_PHP::json_string_to_array($value);
            $value = array_filter($value);
        } else {
            $value = array();
        }
        return $value;
    }

    public static function get_user_favorite_stores() {
        if(SB_User::is_logged_in()) {
            $user = SB_User::get_current();
            $result = SB_User::get_favorite_stores($user->ID);
            if(!SB_PHP::is_array_has_value($result)) {
                $result = self::get_store_guest_favorite();
            }
            return (array)$result;
        }
        return self::get_store_guest_favorite();
    }

    public static function get_user_saved_coupons() {
        if(SB_User::is_logged_in()) {
            $user = SB_User::get_current();
            $result = SB_User::get_saving_coupons_array($user->ID);
            if(!SB_PHP::is_array_has_value($result)) {
                $result = self::get_coupon_guest_saved();
            }
        } else {
            $result = self::get_coupon_guest_saved();
        }
        $result = (array)$result;
        $result = array_filter($result);
        return $result;
    }

    public static function set_store_guest_favorite($post_id, $remove = false) {
        $result = self::get_store_guest_favorite();
        if($remove) {
            $key = array_search($post_id, $result);
            unset($result[$key]);
        } else {
            if(!in_array($post_id, $result)) {
                array_push($result, $post_id);
            }
        }
        $_SESSION['store_guest_favorite'] = json_encode($result);
        return $result;
    }

    public static function get_coupon_guest_saved() {
        $value = isset($_SESSION['coupon_guest_saved']) ? $_SESSION['coupon_guest_saved'] : '';
        if(!empty($value)) {
            $value = SB_PHP::json_string_to_array($value);
            $value = array_filter($value);
        } else {
            $value = array();
        }
        return $value;
    }

    public static function set_coupon_guest_saved($post_id, $remove = false) {
        $result = self::get_coupon_guest_saved();
        if($remove) {
            $key = array_search($post_id, $result);
            unset($result[$key]);
        } else {
            if(!in_array($post_id, $result)) {
                array_push($result, $post_id);
            }
        }
        $_SESSION['coupon_guest_saved'] = json_encode($result);
        return $result;
    }

    public static function get_featured_store($args = array()) {
        $query = self::get_featured_stores($args);
        if(!$query->have_posts()) {
            $defaults = array(
                'post_type' => 'stores',
                'meta_key' => 'wpcf-featured-store',
                'meta_value' => '1'
            );
            $args = wp_parse_args($args, $defaults);
            $query = SB_Query::get($args);
        }
        return $query;
    }

    public static function get_featured_stores($args = array()) {
        $args['meta_query'] = array(
            array(
                'key' => sb_build_meta_name('featured'),
                'value' => 1,
                'type' => 'numeric',
                'compare' => '='
            )
        );
        return self::get_stores($args);
    }

    public static function get_most_viewed_stores($args = array()) {
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = 'views';
        return self::get_stores($args);
    }

    public static function get_pupular_stores($args = array(), $exact_number = false) {
        $query = self::get_top_stores($args);
        if($exact_number) {
            $post_numbers = $query->post_count;
            $posts_per_page = isset($args['posts_per_page']) ? $args['posts_per_page'] : SB_Query::get_posts_per_page();
            if($post_numbers < $posts_per_page) {
                $need_more_numbers = $posts_per_page - $post_numbers;
                $post_ids = array();
                foreach($query->posts as $my_post) {
                    array_push($post_ids, $my_post->ID);
                }
                $args['posts_per_page'] = $need_more_numbers;
                $args['post__not_in'] = $post_ids;
                $tmp_query = self::get_featured_stores($args);
                foreach($tmp_query->posts as $my_post) {
                    array_push($post_ids, $my_post->ID);
                }
                $need_more_numbers = $posts_per_page - count($post_ids);
                if($need_more_numbers > 0) {
                    $args['posts_per_page'] = $need_more_numbers;
                    $args['post__not_in'] = $post_ids;
                    $tmp_query = self::get_most_viewed_stores($args);
                    foreach($tmp_query->posts as $my_post) {
                        array_push($post_ids, $my_post->ID);
                    }
                    $need_more_numbers = $posts_per_page - count($post_ids);
                    if($need_more_numbers > 0) {
                        $args['posts_per_page'] = $need_more_numbers;
                        $args['post__not_in'] = $post_ids;
                        $tmp_query = self::get_stores($args);
                        foreach($tmp_query->posts as $my_post) {
                            array_push($post_ids, $my_post->ID);
                        }
                        $need_more_numbers = $posts_per_page - count($post_ids);
                    }
                }
                unset($args['post__not_in']);
                $args['posts_per_page'] = $posts_per_page;
                $args['post__in'] = $post_ids;
                $query = self::get_stores($args);
            }
        }
        return $query;
    }

    public static function is_store_favorited($store_id) {
        $result = false;
        $favorite_ids = self::get_user_favorite_stores();
        if(is_array($favorite_ids) && in_array($store_id, $favorite_ids)) {
            $result = true;
        }
        return $result;
    }

    public static function get_store_similar($store_id) {
        $terms = SB_Post::get_term_ids($store_id, 'category');
        if(!SB_PHP::is_array_has_value($terms)) {
            $terms = SB_Post::get_term_ids($store_id, 'coupon-cat');
        }
        $args = array(
            'posts_per_page' => 10,
            'post__not_in' => array($store_id)
        );
        if(count($terms) > 0) {
            $args['tax_query'] = array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'coupon-cat',
                    'field' => 'id',
                    'terms' => $terms
                ),
                array(
                    'taxonomy' => 'category',
                    'field' => 'id',
                    'terms' => $terms
                )
            );
        } else {
            $guide = self::get_guide_of_store($store_id);
            if($guide) {
                $args['meta_query'] = array(
                    'relation' => 'OR',
                    array(
                        'key' => '_wpcf_belongs_guides_id',
                        'value' => $guide->ID
                    ),
                    array(
                        'key' => sb_build_meta_name('guide'),
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
        $image_url = SB_Post::get_sb_meta($post_id, 'printable');
        $image = '';
        if(!empty($image_url)) {
            $image = '<img src="' . $image_url . '">';
        }
        if(empty($image)) {
            $image = SB_Post::get_meta($post_id, 'wpcf-printable-code');
            $image = SB_PHP::get_first_image($image);
        }
        return $image;
    }

    public static function get_print_coupon_url($post_id) {
        $result = '';
        $page = SB_Post::get_by_slug('print-coupon', 'page');
        if(!SB_Core::is_error($page)) {
            $result = get_permalink($page);
            $result = add_query_arg(array('ci' => $post_id), $result);
        }
        return $result;
    }

    public static function get_store_url($post_id) {
        return SB_Post::get_meta($post_id, 'wpcf-store-url');
    }

    public static function get_coupon_url($post_id) {
        $url = SB_Post::get_sb_meta($post_id, 'url');
        if(empty($url)) {
            $url = SB_Post::get_meta($post_id, 'wpcf-coupon-url');
        }
        if(empty($url)) {
            $store = self::get_coupon_store($post_id);
            $url = self::get_store_url($store->ID);
            if(empty($url)) {
                $url = SB_Post::get_sb_meta($store->ID, 'destination_url');
            }
        }
        return $url;
    }

    public static function get_store_aff_url( $store_id ) {
        $url = SB_Post::get_sb_meta( $store_id, 'destination_url' );
        if ( empty( $url ) ) {
            $url = SB_Post::get_sb_meta( $store_id, 'url' );
        }
        return $url;
    }

    public static function get_store_website_url( $store_id ) {
        $url = SB_Post::get_sb_meta( $store_id, 'url' );
        return $url;
    }
}