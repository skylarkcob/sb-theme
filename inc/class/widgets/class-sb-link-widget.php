<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<?php
class SB_Link_Widget extends WP_Widget {
    private $link_type = array(
        "link"      => "WP Links",
        "menu"      => "WP Menu"
    );
    public function __construct() {
        parent::__construct( 'sb_link_widget', __( 'SB Link', SB_DOMAIN ), array(
            'classname'   => 'widget_sb_link',
            'description' => __( SB_PHP::add_dotted(SB_WP::phrase("link_widget_description")), SB_DOMAIN ),
        ));
    }

    public function widget($args, $instance) {
        if($instance) {
            $title  = apply_filters('widget_title', SB_PHP::get_single_line_value($instance['title']));
            $link_type = SB_PHP::get_single_line_value($instance['link_type']);
            $link_category = SB_PHP::get_single_line_value($instance['link_category']);
            $menu = SB_PHP::get_single_line_value($instance['menu']);
            $title_icon = SB_PHP::get_single_line_value($instance['title_icon']);
        } else {
            $title = '';
            $link_type = '';
            $link_category = '';
            $menu = '';
            $title_icon = '';
        }

        if(empty($title)) {
            switch($link_type) {
                case "link":
                    $title = get_term($link_category, "link_category")->name;
                    break;
                case "menu":
                    $title = get_term($menu, "nav_menu")->name;
                    break;
            }
        }
        echo $args['before_widget'];
        $title_icon = str_replace("fa-", "", $title_icon);
        echo $args['before_title'] .'<i class="fa fa-'.$title_icon.'"></i> <span class="text">'. $title .'</span>'. $args['after_title'];

        ?>
        <div class="widget-content">
            <ul class="sb-link-list list-unstyled">
                <?php
                if("link" == $link_type) {
                    wp_list_bookmarks(array("category" => $link_category, "show_name" => 1, "categorize" => 0, "title_li" => ""));
                } elseif("menu" == $link_type) {
                    $menu_items = wp_get_nav_menu_items($menu);
                    foreach($menu_items as $item) {
                        echo '<li><i class="fa fa-angle-right"></i> <a href="' . $item->url . '">' . $item->title . '</a></li>';
                    }
                }
                ?>
            </ul>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        if($instance) {
            $title = SB_PHP::get_single_line_value($instance['title']);
            $link_type = SB_PHP::get_single_line_value($instance['link_type']);
            $menu = SB_PHP::get_single_line_value($instance['menu']);
            $title_icon = SB_PHP::get_single_line_value($instance['title_icon']);
        } else {
            $title = '';
            $link_type = '';
            $menu = '';
            $title_icon = "";
        }
        ?>
        <div class="sb-link-widget sb-widget">
            <?php
            $args = array(
                "id"            => $this->get_field_id( 'title' ),
                "name"          => $this->get_field_name( 'title' ),
                "value"         => $title,
                "label_text"    => SB_WP::phrase("title"),
                "description"   => ""
            );
            SB_Theme::widget_field_text($args);

            $args = array(
                "id"            => $this->get_field_id( 'title_icon' ),
                "name"          => $this->get_field_name( 'title_icon' ),
                "value"         => $title_icon,
                "label_text"    => SB_WP::phrase("title_icon"),
                "description"   => SB_WP::phrase("widget_title_icon_description")
            );
            SB_Theme::widget_field_text($args);

            $args = array(
                "id"            => $this->get_field_id( 'link_type' ),
                "name"          => $this->get_field_name( 'link_type' ),
                "value"         => $link_type,
                "label_text"    => SB_WP::phrase("choose_link_type"),
                "list_options"  => $this->link_type,
                "field_class"   => "widefat"
            );
            SB_Theme::widget_field_select($args);

            $terms = get_terms("link_category");
            $link_cats = array();
            foreach($terms as $link) {
                $link_cats[$link->term_id] = $link->name;
            }

            $args = array(
                "id"            => $this->get_field_id( 'link_category' ),
                "name"          => $this->get_field_name( 'link_category' ),
                "value"         => $link_type,
                "label_text"    => SB_WP::phrase("category"),
                "list_options"  => $link_cats,
                "field_class"   => "widefat"
            );
            SB_Theme::widget_field_select($args);

            $terms = get_terms("nav_menu");
            $list_options = array();
            foreach($terms as $term) {
                $list_options[$term->term_id] = $term->name;
            }

            $args = array(
                "id"            => $this->get_field_id( 'menu' ),
                "name"          => $this->get_field_name( 'menu' ),
                "value"         => $menu,
                "label_text"    => SB_WP::phrase("menu"),
                "list_options"  => $list_options,
                "field_class"   => "widefat"
            );
            SB_Theme::widget_field_select($args);

            ?>
        </div>
    <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance["title"] = SB_PHP::get_single_line_value($new_instance['title']);
        $title_icon = SB_PHP::get_single_line_value($new_instance['title_icon']);
        $title_icon = str_replace("fa-", "", $title_icon);
        $instance["title_icon"] = $title_icon;
        $instance["link_type"] = SB_PHP::get_single_line_value($new_instance['link_type']);
        $instance["link_category"] = SB_PHP::get_single_line_value($new_instance['link_category']);
        $instance["menu"] = SB_PHP::get_single_line_value($new_instance['menu']);
        return $instance;
    }
}
?>