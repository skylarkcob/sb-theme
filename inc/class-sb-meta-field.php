<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Meta_Field {
    public static function get_name($args) {
        $name = isset($args['name']) ? trim($args['name']) : '';
        if(empty($name)) {
            return $name;
        }
        $sbmb = substr($name, 0, 4);
        if('sbmb' != $sbmb) {
            $name = sb_build_meta_name($name);
        }
        return $name;
    }

    public static function text($args = array()) {
        $name = self::get_name($args);
        $value = isset($args['value']) ? trim($args['value']) : '';
        $field_class = isset($args['field_class']) ? trim($args['field_class']) : '';
        $label = isset($args['label']) ? $args['label'] : '';
        $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($name); ?>"><?php echo $label; ?>:</label>
            <input type="text" id="<?php echo esc_attr($name); ?>" placeholder="<?php echo $placeholder; ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo $value; ?>" class="<?php echo $field_class; ?>">
        </p>
        <?php
    }

    public static function datetime($args = array()) {
        $name = self::get_name($args);
        $value = isset($args['value']) ? $args['value'] : '';
        $field_class = isset($args['field_class']) ? trim($args['field_class']) : '';
        $label = isset($args['label']) ? $args['label'] : '';
        $date_format = isset($args['date_format']) ? $args['date_format'] : SB_Option::get_date_format();
        if(!empty($value)) {
            $value = date($date_format, $value);
        }
        if($value == 0) {
            $value = '';
        }
        $field_class = SB_PHP::add_string_with_space_before($field_class, 'sb-datetime');
        $current = isset($args['current']) ? $args['current'] : false;
        if($current && empty($value)) {
            $value = date($date_format);
        }
        ?>
        <p>
            <label for="<?php echo esc_attr($name); ?>"><?php echo $label; ?>:</label>
            <input type="text" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo $value; ?>" class="<?php echo $field_class; ?>" placeholder="<?php echo $date_format; ?>" autocomplete="off">
        </p>
    <?php
    }

    public static function select_post($args = array()) {
        $name = self::get_name($args);
        $value = isset($args['value']) ? trim($args['value']) : '';
        $field_class = isset($args['field_class']) ? trim($args['field_class']) : '';
        $label = isset($args['label']) ? $args['label'] : '';
        $post_type = isset($args['post_type']) ? $args['post_type'] : 'post';
        $query_args = array(
            'post_type' => $post_type,
            'posts_per_page' => -1
        );
        global $post;
        $tmp = $post;
        $query = SB_Query::get($query_args);
        if($query->have_posts()) :
        ?>
        <p>
            <label for="<?php echo esc_attr($name); ?>"><?php echo $label; ?>:</label>
            <select class="<?php echo $field_class; ?>" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>">
                <option value="0" <?php selected(0, get_the_ID()); ?>>--<?php _e('Choose', 'sb-theme'); ?>&nbsp;<?php echo SB_PHP::uppercase_first_char($post_type); ?>--</option>
                <?php while($query->have_posts()) : $query->the_post(); ?>
                    <option value="<?php the_ID(); ?>" <?php selected($value, get_the_ID()); ?>><?php the_title(); ?></option>
                <?php endwhile; wp_reset_postdata(); ?>
            </select>
        </p>
        <?php endif;
        $post = $tmp;
    }

    public static function checkbox($args = array()) {
        $name = self::get_name($args);
        $value = isset($args['value']) ? intval($args['value']) : 0;
        $field_class = isset($args['field_class']) ? trim($args['field_class']) : '';
        $label = isset($args['label']) ? $args['label'] : '';
        ?>
        <p>
            <input type="checkbox" id="<?php echo esc_attr($name); ?>" autocomplete="off" name="<?php echo esc_attr($name); ?>" class="<?php echo $field_class; ?>" <?php checked(1, $value); ?>>&nbsp;<?php echo $label; ?>
        </p>
    <?php
    }

    public static function image_upload($args = array()) {
        $name = self::get_name($args);
        $container_class = isset($args['container_class']) ? trim($args['container_class']) : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'sb-media-upload');
        $preview = isset($args['preview']) ? (bool)$args['preview'] : false;
        $label = isset($args['label']) ? $args['label'] : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'sb-post-meta-row');
        $tag = 'p';
        if($preview) {
            $tag = 'div';
        }
        $value = isset($args['value']) ? $args['value'] : '';
        $image_preview_class = 'image-preview';
        if(!empty($value)) {
            $image_preview_class = SB_PHP::add_string_with_space_before($image_preview_class, 'has-image');
        }
        ?>
        <<?php echo $tag; ?> class="<?php echo $container_class; ?>">
            <label for="<?php echo esc_attr($name); ?>" class="display-block"><?php echo $label; ?>:</label>
            <?php SB_Field::media_upload_group($args); ?>
            <?php if($preview) : ?>
                <div class="<?php echo $image_preview_class; ?>">
                    <?php if(!empty($value)) : ?>
                        <img src="<?php echo $value; ?>" alt="">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </<?php echo $tag; ?>>
    <?php
    }

    public static function editor($args = array()) {
        $name = self::get_name($args);
        $value = isset($args['value']) ? $args['value'] : '';
        $id = isset($args['id']) ? $args['id'] : '';
        if(empty($id)) {
            $id = $name;
        }
        $label = isset($args['label']) ? $args['label'] : '';
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'sb-post-meta-editor');
        echo '<div class="' . $container_class . '">';
        echo '<label for="' . $id . '">' . $label . ':</label>';
        $args['textarea_name'] = $name;
        $row = isset($args['row']) ? $args['row'] : 5;
        $args['textarea_rows'] = $row;
        wp_editor($value, $id, $args);
        echo '</div>';
    }

    public static function number($args = array()) {
        $name = self::get_name($args);
        $value = isset($args['value']) ? $args['value'] : '';
        $id = isset($args['id']) ? $args['id'] : '';
        if(empty($id)) {
            $id = $name;
        }
        $label = isset($args['label']) ? $args['label'] : '';
        $field_class = isset($args['field_class']) ? trim($args['field_class']) : '';
        echo '<p>';
        echo '<label for="' . $id . '">' . $label . ':</label>';
        $input = new SB_HTML('input');
        $input->set_attribute('type', 'number');
        $input->set_attribute('class', $field_class);
        $input->set_attribute('value', $value);
        $input->set_attribute('name', $name);
        $input->set_attribute('id', $id);
        $input->output();
        echo '</p>';
    }

    public static function menu_item_icon($item) {
        $item_id = $item->ID;

        $base_name = 'icon';
        $field_data = SB_Field::build_menu_item_field_data($base_name, $item_id);
        $description = __('Icon hiển thị cho menu.', 'sb-theme');
        $args = array(
            'id' => $field_data['id'],
            'name' => $field_data['name'],
            'field_class' => $field_data['class'],
            'value' => $item->icon
        );
        SB_Field::menu_item_before($base_name, $item_id, __('Icon', 'sb-theme'));
        SB_Field::media_upload_with_remove_and_preview($args);
        SB_Field::menu_item_after($description);

        $base_name = 'icon_hover';
        $field_data = SB_Field::build_menu_item_field_data($base_name, $item_id);
        $description = __('Icon hiển thị cho menu khi di chuyển chuột ngang qua.', 'sb-theme');
        $args = array(
            'id' => $field_data['id'],
            'name' => $field_data['name'],
            'field_class' => $field_data['class'],
            'value' => $item->icon_hover
        );
        SB_Field::menu_item_before($base_name, $item_id, __('Icon hover', 'sb-theme'));
        SB_Field::media_upload_with_remove_and_preview($args);
        SB_Field::menu_item_after($description);
    }

    public static function menu_item_color($item) {
        $item_id = $item->ID;

        $base_name = 'color';
        $field_data = SB_Field::build_menu_item_field_data($base_name, $item_id);
        $description = __('Màu viền trên của menu.', 'sb-theme');
        $args = array(
            'id' => $field_data['id'],
            'name' => $field_data['name'],
            'field_class' => $field_data['class'],
            'value' => $item->color
        );
        SB_Field::menu_item_before($base_name, $item_id, __('Màu sắc', 'sb-theme'));
        SB_Field::color_picker($args);
        SB_Field::menu_item_after($description);
    }
}