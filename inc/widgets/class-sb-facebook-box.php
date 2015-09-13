<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Facebook_Box_Widget extends WP_Widget {
    private $lang;
    public function __construct() {
        add_filter('sb_theme_use_facebook_javascript_sdk', '__return_true');
        $this->lang = SB_Core::get_language();
        parent::__construct('sb_facebook_box_widget', 'SB Facebook Box',
            array(
                'classname' => 'widget_sb_facebook_box widget-sb-facebook-box sb-facebook-box-widget',
                'description' => ('vi' == $this->lang) ? 'Widget tạo box Facebook fanpage.' : __('Facebook fanpage box widget.', 'sb-theme' ),
            ),
            array(
                'width' => 400
            )
        );
    }

    public function widget($args, $instance) {
        $title  = apply_filters('widget_title', SB_PHP::get_single_line_value($instance['title']));
        $page_name = isset($instance['page_name']) ? $instance['page_name'] : '';
        $href = isset($instance['href']) ? $instance['href'] : '';
        $width = isset($instance['width']) ? $instance['width'] : 340;
        $height = isset($instance['height']) ? $instance['height'] : 500;
        $hide_cover = (bool)(isset($instance['hide_cover']) ? $instance['hide_cover'] : false);
        $show_facepile = (bool)(isset($instance['show_facepile']) ? $instance['show_facepile'] : true);
        $show_posts = (bool)(isset($instance['show_posts']) ? $instance['show_posts'] : false);
        $hide_cta = (bool)(isset($instance['hide_cta']) ? $instance['hide_cta'] : false);
        $small_header = (bool)(isset($instance['small_header']) ? $instance['small_header'] : false);
        $adapt_container_width = (bool)(isset($instance['adapt_container_width']) ? $instance['adapt_container_width'] : true);
        $show_title = (bool)(isset($instance['show_title']) ? $instance['show_title'] : 0);
        echo $args['before_widget'];
        if($show_title && !empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        $fanpage_args = array(
            'page_name' => $page_name,
            'href' => $href,
            'width' => $width,
            'height' => $height,
            'hide_cover' => $hide_cover,
            'show_facepile' => $show_facepile,
            'show_posts' => $show_posts,
            'hide_cta' => $hide_cta,
            'small_header' => $small_header,
            'adapt_container_width' => $adapt_container_width
        );
        SB_Theme::the_facebook_fanpage_box($fanpage_args);
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : '';
        $page_name = isset($instance['page_name']) ? $instance['page_name'] : '';
        $href = isset($instance['href']) ? $instance['href'] : '';
        $width = isset($instance['width']) ? $instance['width'] : 340;
        $height = isset($instance['height']) ? $instance['height'] : 500;
        $hide_cover = (bool)(isset($instance['hide_cover']) ? $instance['hide_cover'] : false);
        $show_facepile = (bool)(isset($instance['show_facepile']) ? $instance['show_facepile'] : true);
        $show_posts = (bool)(isset($instance['show_posts']) ? $instance['show_posts'] : false);
        $hide_cta = (bool)(isset($instance['hide_cta']) ? $instance['hide_cta'] : false);
        $small_header = (bool)(isset($instance['small_header']) ? $instance['small_header'] : false);
        $adapt_container_width = (bool)(isset($instance['adapt_container_width']) ? $instance['adapt_container_width'] : true);
        $show_title = (bool)(isset($instance['show_title']) ? $instance['show_title'] : 0);
        SB_Widget_Field::before('sb-theme');
        SB_Widget_Field::title($this->get_field_id('title'), $this->get_field_name('title'), $title);

        $args = array(
            'id' => $this->get_field_id('page_name'),
            'name' => $this->get_field_name('page_name'),
            'value' => $page_name,
            'label' => ('vi' == $this->lang) ? 'Tên trang:' : __('Page name:', 'sb-theme')
        );
        SB_Widget_Field::text($args);

        $args = array(
            'id' => $this->get_field_id('href'),
            'name' => $this->get_field_name('href'),
            'value' => $href,
            'label' => ('vi' == $this->lang) ? 'Đường dẫn:' : __('Page url:', 'sb-theme')
        );
        SB_Widget_Field::text($args);

        $args = array(
            'id_width' => $this->get_field_id('width'),
            'name_width' => $this->get_field_name('width'),
            'id_height' => $this->get_field_id('height'),
            'name_height' => $this->get_field_name('height'),
            'value' => array($width, $height),
            'label' => ('vi' == SB_Core::get_language()) ? 'Kích thước:' : __('Size:', 'sb-theme')
        );
        SB_Widget_Field::size($args);

        $args = array(
            'id' => $this->get_field_id('hide_cover'),
            'name' => $this->get_field_name('hide_cover'),
            'value' => $hide_cover,
            'label' => ('vi' == SB_Core::get_language()) ? 'Ẩn ảnh banner?' : __('Hide cover photo in the header?', 'sb-theme')
        );
        SB_Widget_Field::checkbox($args);

        $args = array(
            'id' => $this->get_field_id('show_facepile'),
            'name' => $this->get_field_name('show_facepile'),
            'value' => $show_facepile,
            'label' => ('vi' == SB_Core::get_language()) ? 'Hiển thị avatar người dùng?' : __('Show profile photos when friends like this?', 'sb-theme')
        );
        SB_Widget_Field::checkbox($args);

        $args = array(
            'id' => $this->get_field_id('show_posts'),
            'name' => $this->get_field_name('show_posts'),
            'value' => $show_posts,
            'label' => ('vi' == SB_Core::get_language()) ? 'Hiển thị bài đăng?' : __('Show posts from the Page\'s timeline?', 'sb-theme')
        );
        SB_Widget_Field::checkbox($args);

        $args = array(
            'id' => $this->get_field_id('hide_cta'),
            'name' => $this->get_field_name('hide_cta'),
            'value' => $hide_cta,
            'label' => ('vi' == SB_Core::get_language()) ? 'Ẩn nút liên hệ?' : __('Hide the custom call to action button?', 'sb-theme')
        );
        SB_Widget_Field::checkbox($args);

        $args = array(
            'id' => $this->get_field_id('small_header'),
            'name' => $this->get_field_name('small_header'),
            'value' => $small_header,
            'label' => ('vi' == SB_Core::get_language()) ? 'Header thu gọn?' : __('Use the small header instead?', 'sb-theme')
        );
        SB_Widget_Field::checkbox($args);

        $args = array(
            'id' => $this->get_field_id('adapt_container_width'),
            'name' => $this->get_field_name('adapt_container_width'),
            'value' => $adapt_container_width,
            'label' => ('vi' == SB_Core::get_language()) ? 'Tự chỉnh sửa kích thước?' : __('Try to fit inside the container width?', 'sb-theme')
        );
        SB_Widget_Field::checkbox($args);

        SB_Widget_Field::show_title($this->get_field_id('show_title'), $this->get_field_name('show_title'), $show_title);
        SB_Widget_Field::after();
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = isset($new_instance['title']) ? $new_instance['title'] : '';
        $instance['page_name'] = isset($new_instance['page_name']) ? $new_instance['page_name'] : '';
        $instance['href'] = isset($new_instance['href']) ? $new_instance['href'] : '';
        $instance['width'] = isset($new_instance['width']) ? $new_instance['width'] : 340;
        $instance['height'] = isset($new_instance['height']) ? $new_instance['height'] : 500;
        $instance['hide_cover'] = isset($new_instance['hide_cover']) ? 1 : 0;
        $instance['show_facepile'] = isset($new_instance['show_facepile']) ? 1 : 0;
        $instance['show_posts'] = isset($new_instance['show_posts']) ? 1 : 0;
        $instance['hide_cta'] = isset($new_instance['hide_cta']) ? 1 : 0;
        $instance['small_header'] = isset($new_instance['small_header']) ? 1 : 0;
        $instance['adapt_container_width'] = isset($new_instance['adapt_container_width']) ? 1 : 0;
        $instance['show_title'] = isset($new_instance['show_title']) ? 1 : 0;
        return $instance;
    }
}