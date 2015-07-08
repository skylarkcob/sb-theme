<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Menu_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct('sb_menu_widget', 'SB Menu',
            array(
                'classname' => 'widget_sb_menu sb-menu-widget',
                'description' => __('Hiển thị menu trên sidebar.', 'sb-theme' ),
            ),
            array(
                'width' => 400
            )
        );
    }

    public function widget($args, $instance) {
        $menu_id = isset($instance['nav_menu']) ? absint($instance['nav_menu']) : 0;

        if($menu_id < 1) {
            return;
        }

        $instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        echo $args['before_widget'];

        if ( !empty($instance['title']) ) {
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
        }

        $walker = new SB_Menu_Walker();

        wp_nav_menu(array('menu' => $menu_id, 'walker' => $walker));

        echo $args['after_widget'];
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        if ( ! empty( $new_instance['title'] ) ) {
            $instance['title'] = strip_tags( stripslashes($new_instance['title']) );
        }
        if ( ! empty( $new_instance['nav_menu'] ) ) {
            $instance['nav_menu'] = (int) $new_instance['nav_menu'];
        }
        return $instance;
    }

    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : '';
        $nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

        // Get menus
        $menus = wp_get_nav_menus();

        // If no menus exists, direct the user to go and create some.
        if ( !$menus ) {
            echo '<p>'. sprintf( __('Chưa có menu nào được tạo. Bạn có thể tạo menu mới <a href="%s">tại đây</a>.'), admin_url('nav-menus.php') ) .'</p>';
            return;
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
            <select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
                <option value="0"><?php _e( '&mdash; Select &mdash;' ) ?></option>
                <?php
                foreach ( $menus as $menu ) {
                    echo '<option value="' . $menu->term_id . '"'
                        . selected( $nav_menu, $menu->term_id, false )
                        . '>'. esc_html( $menu->name ) . '</option>';
                }
                ?>
            </select>
        </p>
    <?php
    }
}