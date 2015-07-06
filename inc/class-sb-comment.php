<?php
class SB_Comment {
    public static function allow_author_post_shortcode_on_comment() {
        if(current_user_can('edit_posts')) {
            add_filter( 'comment_text', 'do_shortcode' );
        }
    }

    public static function enable_spam_check() {
        if(is_user_logged_in()) {
            return false;
        }
        $options = SB_Option::get();
        $check_spam = isset($options['comment']['spam_check']) ? $options['comment']['spam_check'] : 1;
        return (bool)$check_spam;
    }

    public static function enable_notify_comment_approved() {
        $options = SB_Option::get();
        $result = isset($options['comment']['notify_user']) ? $options['comment']['notify_user'] : 1;
        return (bool)$result;
    }

    public static function disable_website_url() {
        $options = SB_Option::get();
        $result = isset($options['comment']['website_url']) ? $options['comment']['website_url'] : 0;
        return (bool)$result;
    }

    public static function get_default_avatar_url() {
        if(!self::use_default_avatar()) {
            return '';
        }
        $options = SB_Option::get();
        $value = isset($options['comment']['default_avatar_url']) ? $options['comment']['default_avatar_url'] : '';
	    $value = SB_Option::get_media_detail($value);
        $result = $value['url'];
	    return $result;
    }

    public static function use_default_avatar() {
        return SB_Tool::use_default_avatar();
    }

    public static function enable_comment_tools() {
        $options = SB_Option::get();
        $result = isset($options['comment']['tools']) ? $options['comment']['tools'] : 1;
        return (bool)$result;
    }

    public static function enable_auto_empty_spam() {
        $options = SB_Option::get();
        $result = isset($options['comment']['auto_empty_spam']) ? $options['comment']['auto_empty_spam'] : 1;
        return (bool)$result;
    }

    public static function get_trashed($args = array()) {
        $args['status'] = 'trash';
        return self::get($args);
    }

    public static function get_spam($args = array()) {
        $args['status'] = 'spam';
        return self::get($args);
    }

    public static function delete_spam($args = array()) {
        $comments = self::get_spam($args);
        foreach($comments as $comment) {
            self::delete($comment->comment_ID);
        }
    }

    public static function delete_all() {
        $comments = self::get();
        foreach($comments as $comment) {
            self::delete($comment->comment_ID);
        }
        $comments = self::get_trashed();
        foreach($comments as $comment) {
            self::delete($comment->comment_ID);
        }
    }

    public static function approve($comment) {
        $commentarr = array();
        $commentarr['comment_ID'] = $comment->comment_ID;
        $commentarr['comment_approved'] = 1;
        wp_update_comment( $commentarr );
    }

    public static function get($args = array()) {
        $args['number'] = '';
        return get_comments($args);
    }

    public static function get_approved($args = array()) {
        $args['status'] = 'approve';
        return self::get($args);
    }

    public static function delete($comment_id) {
        return wp_delete_comment($comment_id, true);
    }

    public static function is_author_email_valid($comment_data) {
        $comment_author_email = SB_PHP::get_value_by_key($comment_data, 'comment_author_email');
        $author_email_domain_name = SB_PHP::get_domain_from_email($comment_author_email);
        if(!empty($author_email_domain_name) && !SB_PHP::is_domain_alive($author_email_domain_name)) {
            return false;
        }
        return true;
    }

    public static function is_author_url_valid($comment_data) {
        $comment_author_url = SB_PHP::get_value_by_key($comment_data, 'comment_author_url');
        if(!empty($comment_author_url)) {
            $author_url_length = SB_PHP::strlen($comment_author_url);
            if($author_url_length > self::get_spam_url_len() || self::is_spam($comment_author_url)) {
                return false;
            }
            $author_domain_name = SB_PHP::get_domain_name($comment_author_url);
            if(!empty($author_domain_name) && !SB_PHP::is_domain_alive($author_domain_name)) {
                return false;
            }
        }
        return true;
    }

    public static function get_spam_url_len() {
        $len = 50;
        if(defined('SB_SPAM_URL_LEN')) {
            $len = SB_SPAM_URL_LEN;
        }
        return apply_filters('sb_spam_url_len', $len);
    }

    public static function get_spam_link_count() {
        $count = 5;
        if(defined('SB_SPAM_LINK_COUNT')) {
            $count = SB_SPAM_LINK_COUNT;
        }
        return apply_filters('sb_spam_link_count', $count);
    }

    public static function is_spam($comment_data) {
        $comment_user_id = self::get_user_id($comment_data);
        if(is_numeric($comment_user_id) && $comment_user_id > 0) {
            return false;
        }
        if(!self::is_content_valid($comment_data) || !self::is_author_url_valid($comment_data) || !self::is_author_email_valid($comment_data)) {
            return true;
        }
        return false;
    }

    public static function set_spam_session($value) {
        $_SESSION['sb_comment_spam'] = $value;
    }

    public static function get_spam_session() {
        self::set_spam_session(0);
        return isset($_SESSION['sb_comment_spam']) ? $_SESSION['sb_comment_spam'] : 0;
    }

    public static function is_spam_session() {
        $spam = self::get_spam_session();
        if((bool)$spam) {
            return true;
        }
        return false;
    }

    public static function is_content_valid($comment_data) {
        $spam_link_number = self::get_spam_link_count();
        $content = SB_PHP::get_value_by_key($comment_data, 'comment_content');
        if(!empty($content) && (SB_PHP::count_html_tag($content, 'a') > $spam_link_number || SB_Spam::check($content))) {
            return false;
        }
        $count_url = mb_substr_count($content, '[url');
        if($count_url > $spam_link_number) {
            return false;
        }
        return true;
    }

    public static function get_user_id($comment_data) {
        $user_id = isset($comment_data['user_ID']) ? $comment_data['user_ID'] : 0;
        if(empty($user_id)) {
            $user_id = isset($comment_data['user_id']) ? $comment_data['user_id'] : 0;
        }
        return $user_id;
    }

    public static function delete_spam_with_check($args = array()) {
        $comments = self::get_spam($args);
        foreach($comments as $comment) {
            if(self::is_spam(get_object_vars($comment))) {
                self::delete($comment->comment_ID);
            }
        }
    }

    public static function get_id($comment) {
        $result = 0;
        if(!SB_Core::is_error($comment)) {
            $result = $comment->comment_ID;
        }
        return $result;
    }

    public static function get_meta($comment_id, $key) {
        return get_comment_meta($comment_id, $key, true);
    }

    public static function update_meta($comment_id, $key, $value) {
        update_comment_meta($comment_id, $key, $value);
    }

    public static function get_likes($comment_id) {
        $likes = self::get_meta($comment_id, 'likes');
        $likes = intval($likes);
        return $likes;
    }

    public static function update_likes($comment_id) {
        $likes = self::get_likes($comment_id);
        $likes++;
        self::update_meta($comment_id, 'likes', $likes);
    }

    public static function get_human_time_diff($comment_id) {
        $comment_date = get_comment_date('G', $comment_id);
        return SB_Core::date_time_ago($comment_date);
    }

    public static function the_human_time_diff($comment_id) {
        echo self::get_human_time_diff($comment_id);
    }
}