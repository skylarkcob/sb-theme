<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Video_Widget extends WP_Widget {
    private $servers = array();
    private $lang;
    public function __construct() {
        $this->lang = SB_Core::get_language();
        parent::__construct('sb_video_widget', 'SB Video',
            array(
                'classname' => 'widget_sb_video sb-video-widget',
                'description' => ('vi' == $this->lang) ? 'Hiển thị video trên sidebar.' : __('Display video on sidebar.', 'sb-theme' ),
            ),
            array(
                'width' => 400
            )
        );
        $this->init_server();
    }

    public function init_server() {
        $this->servers['youtube'] = 'Youtube';
        $this->servers['vimeo'] = 'Vimeo';
    }

    public function widget($args, $instance) {
        $title  = apply_filters('widget_title', SB_PHP::get_single_line_value($instance['title']));
        $posts_per_page = isset($instance['posts_per_page']) ? $instance['posts_per_page'] : 5;
        $server = isset($instance['server']) ? $instance['server'] : '';
        $id = isset($instance['id']) ? $instance['id'] : '';
        $width = isset($instance['width']) ? $instance['width'] : '';
        $height = isset($instance['height']) ? $instance['height'] : '';
        $show_title = isset($instance['show_title']) ? $instance['show_title'] : 1;
        $show_title = (bool)$show_title;
        $show_thumbnail = isset($instance['show_thumbnail']) ? $instance['show_thumbnail'] : 1;
        $show_thumbnail = (bool)$show_thumbnail;
        $query = new WP_Query();
        if(post_type_exists('video')) {
            $query_args = array(
                'post_type' => 'video',
                'posts_per_page' => $posts_per_page
            );
            $query = SB_Query::get($query_args);
        }
        echo $args['before_widget'];

        if(!empty($title) && $show_title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        ?>
        <div class="sb-video-inner">
            <?php if(post_type_exists('video')) : ?>
                <ul class="list-videos list-posts">
                    <?php
                    $count = 0;
                    if($query->have_posts()) : while($query->have_posts()) : $query->the_post(); ?>
                        <li>
                            <?php
                            $id_video = SB_Video::get_youtube_video_id(get_the_ID());
                            if(0 == $count) :
                                SB_Video::youtube_player(array('id' => $id_video, 'width' => 285, 'height' => 200, 'autoplay' => 0));
                                SB_Post::the_title(array('headline' => 'h2'));
                            else : ?>
                                <div class="">
                                    <?php SB_Post::the_title(array('headline' => 'h2')); ?>
                                </div>
                            <?php endif; ?>
                        </li>
                        <?php
                        $count++;
                    endwhile; wp_reset_postdata(); endif; ?>
                </ul>
            <?php else :
                $video_args = array(
                    'id' => $id,
                    'thumbnail' => $show_thumbnail,
                    'width' => $width,
                    'height' => $height
                );
                switch($server) {
                    case 'youtube':
                        break;
                    case 'vimeo':
                        SB_Video::vimeo_player($video_args);
                        break;
                }
            endif; ?>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        if($instance) {
            $title = isset($instance['title']) ? $instance['title'] : '';
            $posts_per_page = isset($instance['posts_per_page']) ? $instance['posts_per_page'] : 5;
            $server = isset($instance['server']) ? $instance['server'] : '';
            $id = isset($instance['id']) ? $instance['id'] : '';
            $width = isset($instance['width']) ? $instance['width'] : '';
            $height = isset($instance['height']) ? $instance['height'] : '';
            $show_title = isset($instance['show_title']) ? $instance['show_title'] : 1;
            $show_thumbnail = isset($instance['show_thumbnail']) ? $instance['show_thumbnail'] : 1;
        } else {
            $title = '';
            $posts_per_page = 5;
            $server = '';
            $id = '';
            $width = '';
            $height = '';
            $show_title = 1;
            $show_thumbnail = 1;
        }
        SB_Widget_Field::before('sb-theme');
        SB_Widget_Field::title($this->get_field_id('title'), $this->get_field_name('title'), $title);
        if(post_type_exists('video')) {
            $args = array(
                'id' => $this->get_field_id('posts_per_page'),
                'name' => $this->get_field_name('posts_per_page'),
                'value'	=> $posts_per_page,
                'label' => __('Số video:', 'sb-theme'),
                'description' => __('Số lượng video cần lấy để hiển thị.', 'sb-theme'),
                'container_class' => 'post-number',
                'field_class' => 'widefat'
            );
            SB_Widget_Field::number($args);
        } else {
            $select_none = ('vi' == SB_Core::get_language()) ? '-- Chọn server --' : __('-- Choose server --', 'sb-theme');
            $list_option = '<option value="">' . $select_none . '</option>';
            foreach($this->servers as $key => $value) {
                $list_option .= SB_Field::get_option(array('value' => $key, 'text' => $value, 'selected' => $server));
            }
            $args = array(
                'all_option' => $list_option,
                'id' => $this->get_field_id('server'),
                'name' => $this->get_field_name('server'),
                'value' => $server,
                'label' => ('vi' == SB_Core::get_language()) ? 'Server:' : __('Server:', 'sb-theme')
            );
            SB_Widget_Field::select($args);

            $args = array(
                'id' => $this->get_field_id('id'),
                'name' => $this->get_field_name('id'),
                'value' => $id,
                'label' => ('vi' == SB_Core::get_language()) ? 'ID:' : __('ID:', 'sb-theme'),
                'description' => ('vi' == SB_Core::get_language()) ? 'Ví dụ như: 99269226, 8QEXEcjaHZs,...' : __('E.g: 99269226, 8QEXEcjaHZs,...', 'sb-theme')
            );
            SB_Widget_Field::text($args);
            $args = array(
                'all_option' => $list_option,
                'id_width' => $this->get_field_id('width'),
                'name_width' => $this->get_field_name('width'),
                'id_height' => $this->get_field_id('height'),
                'name_height' => $this->get_field_name('height'),
                'value' => array($width, $height),
                'label' => ('vi' == SB_Core::get_language()) ? 'Kích thước:' : __('Size:', 'sb-theme')
            );
            SB_Widget_Field::size($args);
            $args = array(
                'id' => $this->get_field_id('show_thumbnail'),
                'name' => $this->get_field_name('show_thumbnail'),
                'value' => $show_thumbnail,
                'label' => ('vi' == SB_Core::get_language()) ? 'Hiển thị thumbnail?' : __('Show video thumbnail?', 'sb-theme')
            );
            SB_Widget_Field::checkbox($args);
        }
        SB_Widget_Field::show_title($this->get_field_id('show_title'), $this->get_field_name('show_title'), $show_title);
        SB_Widget_Field::after();
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title'] = isset($new_instance['title']) ? $new_instance['title'] : '';
        $instance['posts_per_page'] = isset($new_instance['posts_per_page']) ? $new_instance['posts_per_page'] : 5;
        $instance['server'] = isset($new_instance['server']) ? $new_instance['server'] : '';
        $instance['id'] = isset($new_instance['id']) ? $new_instance['id'] : '';
        $instance['width'] = isset($new_instance['width']) ? $new_instance['width'] : '';
        $instance['height'] = isset($new_instance['height']) ? $new_instance['height'] : '';
        $instance['show_title'] = isset($new_instance['show_title']) ? 1 : 0;
        $instance['show_thumbnail'] = isset($new_instance['show_thumbnail']) ? 1 : 0;

        return $instance;
    }
}