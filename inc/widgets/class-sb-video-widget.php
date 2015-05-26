<?php
class SB_Video_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct('sb_video_widget', 'SB Video',
            array(
                'classname' => 'widget_sb_video sb-video-widget',
                'description' => __('Hiển thị video trên sidebar.', 'sb-theme' ),
            ),
            array(
                'width' => 400
            )
        );
    }

    public function widget($args, $instance) {
        $title  = apply_filters('widget_title', SB_PHP::get_single_line_value($instance['title']));
        $posts_per_page = isset($instance['posts_per_page']) ? $instance['posts_per_page'] : 5;

        $query_args = array(
            'post_type' => 'video',
            'posts_per_page' => $posts_per_page
        );
        $query = SB_Query::get($query_args);
        echo $args['before_widget'];

        if(!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        ?>
        <div class="sb-video-inner">
            <ul class="list-videos list-posts">
                <?php $count = 0; ?>
                <?php if($query->have_posts()) : while($query->have_posts()) : $query->the_post(); ?>
                    <li>
                        <?php $id_video = SB_Video::get_youtube_video_id(get_the_ID()); ?>
                        <?php if(0 == $count) : ?>
                            <?php
                            SB_Video::youtube_player(array('id' => $id_video, 'width' => 285, 'height' => 200, 'autoplay' => 0));
                            SB_Post::the_title(array('headline' => 'h2'));
                            ?>
                        <?php else : ?>
                            <div class="">
                                <?php SB_Post::the_title(array('headline' => 'h2')); ?>
                            </div>
                        <?php endif; ?>
                    </li>
                    <?php $count++; ?>
                <?php endwhile; wp_reset_postdata(); endif; ?>
            </ul>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        if($instance) {
            $title = isset($instance['title']) ? $instance['title'] : '';
            $posts_per_page = isset($instance['posts_per_page']) ? $instance['posts_per_page'] : 5;
        } else {
            $title = '';
            $posts_per_page = 5;
        }
        SB_Widget_Field::before('sb-theme');

        if(post_type_exists('video')) {
            SB_Widget_Field::title($this->get_field_id('title'), $this->get_field_name('title'), $title);

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
            echo '<p>Hiện không có post type video trên trang của bạn.</p>';
        }

        SB_Widget_Field::after();
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title'] = isset($new_instance['title']) ? $new_instance['title'] : '';
        $instance['posts_per_page'] = isset($new_instance['posts_per_page']) ? $new_instance['posts_per_page'] : 5;

        return $instance;
    }
}
?>