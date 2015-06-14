<?php
class SB_Mail {
    public static function set_html_content_type() {
        return 'text/html';
    }

    public static function send($to, $subject, $message, $headers = '', $attachments = '') {
        $done = wp_mail($to, $subject, $message, $headers, $attachments);
        return (bool)$done;
    }

    public static function send_html($to, $subject, $message, $headers = '', $attachments = '') {
        add_filter( 'wp_mail_content_type', array('SB_Mail', 'set_html_content_type') );
        $message = self::before() . $message;
        $message .= self::after();
        $result = self::send($to, $subject, $message, $headers, $attachments);
        remove_filter( 'wp_mail_content_type', array('SB_Mail', 'set_html_content_type') );
        return $result;
    }

    public static function notify_user_for_comment_approved($comment) {
        if($comment) {
            $post = get_post($comment->comment_post_ID);
            if($post) {
                $subject = sprintf(__('Bình luận của bạn cho bài viết %s đã được duyệt', 'sb-theme'), $post->post_title);
                $body = sprintf(sprintf('<p>%s,</p>', __('Chào bạn %s', 'sb-theme')), $comment->comment_author);
                $body .= sprintf(sprintf('<p>%s</p>', __('Bình luận của bạn cho bài viết %s đã được xét duyệt. Bạn có thể truy cập vào các đường link bên dưới để biết thêm thông tin chi tiết.', 'sb-theme')), $post->post_title);
                $body .= sprintf(sprintf('<p>%s</p>', __('Đường dẫn bài viết: %s', 'sb-theme')), get_permalink($post));
                $body .= sprintf(sprintf('<p>%s</p>', __('Đường dẫn bình luận của bạn: %s', 'sb-theme')), get_comment_link($comment));
                self::send_html($comment->comment_author_email, $subject, $body);
            }
        }
    }

    public static function report_domain_use_invalid_theme_license() {
        $theme = SB_Theme::get_current_theme_info();
        $theme_name = $theme->get('Name');
        $site_url = get_bloginfo('url');
        $admin_email = SB_Option::get_admin_email();
        $subject = 'Báo cáo trang web vi phạm bản quyền lúc ' . SB_Core::get_current_datetime();
        $body = '<p>Tên miền: ' . $site_url . '</p>';
        $body .= '<p>Địa chỉ email: ' . $admin_email . '</p>';
        $body .= '<p>Tên giao diện: ' . $theme_name . '</p>';
        self::send_html('codewpvn@gmail.com', $subject, $body);
    }

    public static function build_body($args = array()) {
        $body = '';
        foreach($args as $key => $value) {
            $tag = isset($value['tag']) ? $value['tag'] : '';
            if(!empty($tag)) {
                $html = new SB_HTML($tag);
                $text = isset($value['text']) ? $value['text'] : '';
                $html->set_attribute('text', $text);
                $body .= $html->build();
            }
        }
        return $body;
    }

    public static function before() {
        $result = '<div class="sb-mail" style="background-color: rgb(245, 245, 245); padding: 20px;">';
        $result .= '<div class="sb-mail-container" style="background-color: rgb(255, 255, 255); padding: 10px 20px;">';
        return $result;
    }

    public static function after() {
        $result = '</div>';
        $result .= '</div>';
        return $result;
    }
}