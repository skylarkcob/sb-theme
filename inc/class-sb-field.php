<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Field {

    private static function image_thumbnail($args = array()) {
        self::uploaded_image_preview($args);
    }

    private static function uploaded_image_preview($args = array()) {
        $value = isset($args['value']) ? $args['value'] : '';
        $preview = isset($args['preview']) ? $args['preview'] : true;
        $image_preview = '';
        $image_preview_class = 'image-preview';
        if(!empty($value)) {
            $image_preview = sprintf('<img alt="" src="%s">', $value);
            $image_preview_class .= ' has-image';
        }
        if($preview) {
            echo '<div class="' . $image_preview_class . '">' . $image_preview . '</div>';
        }
    }

    public static function media_image($args = array()) {
        self::media_upload_with_remove_and_preview($args);
    }

    public static function media_upload_no_preview($args = array()) {
        $args['preview'] = false;
        self::media_upload_with_remove_and_preview($args);
    }

    private static function media_upload($args = array()) {
        self::media_image($args);
    }

    public static function media_image_with_url($args = array()) {
        self::media_upload_with_url($args);
    }

    private static function media_upload_with_url($args = array()) {
        $new_args = $args;
        $id = isset($args['id']) ? $args['id'] : '';
        $name = isset($args['name']) ? $args['name'] : '';
        $names = explode(']', $name);
        $keys = array();
        foreach($names as $name_item) {
            $item = str_replace('sb_options[', '', $name_item);
            $item = str_replace('[', '', $item);
            if(empty($item)) {
                continue;
            }
            array_push($keys, $item);
        }
        $image_keys = $keys;
        array_push($image_keys, 'image');
        if(!empty($id)) {
            $new_args['id'] = $id . '_image';
        }
        $new_args['name'] = $name . '[image]';
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'margin-bottom');
        $value = SB_Option::get_by_key(array('keys' => $image_keys));
        $new_args['container_class'] = $container_class;
        $new_args['value'] = $value;
        self::media_upload($new_args);
        if(!empty($id)) {
            $args['id'] = $id . '_url';
        }
        array_push($keys, 'url');
        $value = SB_Option::get_by_key(array('keys' => $keys));
        $description = __('Enter url for the image above.', 'sb-theme');
        $args['before'] = '<div class="margin-top">';
        $args['name'] = $name . '[url]';
        $args['value'] = $value;
        $args['description'] = $description;
        self::text_field($args);
    }

    public static function select_gender($args = array()) {
        $value = isset($args['value']) ? absint($args['value']) : 0;
        $all_option = '<option value="0" ' . selected(0, $value, false) . '>' . __('Male', 'sb-theme') . '</option>';
        $all_option .= '<option value="1" ' . selected(1, $value, false) . '>' . __('Female', 'sb-theme') . '</option>';
        $args['all_option'] = $all_option;
        $field_class = isset($args['field_class']) ? $args['field_class'] : '';
        $field_class = SB_PHP::add_string_with_space_before($field_class, 'select-gender');
        $args['field_class'] = $field_class;
        self::select($args);
    }

    public static function get_option($args = array()) {
        $value = isset($args['value']) ? $args['value'] : '';
        $text = isset($args['text']) ? $args['text'] : '';
        $selected = isset($args['selected']) ? $args['selected'] : '';
        $option = new SB_HTML('option');
        $option->set_attribute('value', $value);
        $option->set_text($text);
        $attributes = isset($args['attributes']) ? $args['attributes'] : array();
        foreach($attributes as $data_name => $att_value) {
            $option->set_attribute($data_name, $att_value);
        }
        if($selected == $value) {
            $option->set_attribute('selected', 'selected');
        }
        return $option->build();
    }

    public static function option($args = array()) {
        echo self::get_option($args);
    }

    public static function datetime_picker($args = array()) {
        $name = isset($args['name']) ? $args['name'] : '';
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
        $field_class = SB_PHP::add_string_with_space_before($field_class, 'sb-datetime width-small');
        $display_block = isset($args['display_block']) ? $args['display_block'] : true;
        if($display_block) {
            $field_class = SB_PHP::add_string_with_space_before($field_class, 'display-block');
        }
        $current = isset($args['current']) ? $args['current'] : false;
        if($current && empty($value)) {
            $value = date($date_format);
        }
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'sb-field-datetime-picker');
        $before = isset($args['before']) ? $args['before'] : '<div class="' . $container_class . '">';
        $after = isset($args['after']) ? $args['after'] : '</div>';
        $description = isset($args['description']) ? $args['description'] : '';
        $id = isset($args['id']) ? $args['id'] : '';
        $date_format_jquery = SB_PHP::convert_datetime_format_to_jquery($date_format);
        echo $before;
        self::label(array('for' => $id, 'text' => $label));
        $args['value'] = $value;
        $args['only'] = true;
        $args['field_class'] = $field_class;
        $args['placeholder'] = $date_format_jquery;
        $args['autocomplete'] = false;
        $attributes = isset($args['attributes']) ? (array)$args['attributes'] : array();
        $min_date = isset($args['min_date']) ? $args['min_date'] : '';
        $max_date = isset($args['max_date']) ? $args['max_date'] : '';
        if(empty($min_date) && !is_numeric($min_date)) {
            $min_date_by_value = isset($args['min_date_by_value']) ? $args['min_date_by_value'] : false;
            if($min_date_by_value) {
                $min_date = $value;
            }
        }
        $atts = array(
            'data-datetime-format' => $date_format_jquery
        );
        if(!empty($min_date) || is_numeric($min_date)) {
            $atts['data-min-date'] = $min_date;
        }
        if(!empty($max_date)) {
            $atts['data-max-date'] = $max_date;
        }
        $attributes = wp_parse_args($attributes, $atts);
        $args['attributes'] = $attributes;
        self::text($args);
        self::the_description($description);
        self::the_after($before, $after);
    }

    public static function select_birthday($args = array()) {
        $lang = isset($args['language']) ? $args['language'] : 'en';
        $birthday = isset($args['value']) ? $args['value'] : strtotime(SB_Core::get_current_datetime());
        $birth_day = intval(date('d', $birthday));
        $birth_month = intval(date('m', $birthday));
        $birth_year = intval(date('Y', $birthday));
        $sep = isset($args['sep']) ? $args['sep'] : '<span class="sep">/</span>';
        $year_max = intval(date('Y')) - 13;
        $year_min = $year_max - 150;
        $all_option_day = '<option value="0">' . __('Choose day', 'sb-theme') . '</option>';
        for($i = 1; $i <= 31; $i++) {
            $all_option_day .= self::get_option(array('text' => sprintf('%02d', $i), 'value' => $i, 'selected' => $birth_day));
        }
        $all_option_month = '<option value="0">' . __('Choose month', 'sb-theme') . '</option>';
        for($i = 1; $i <= 12; $i++) {
            $all_option_month .= self::get_option(array('text' => sprintf('%02d', $i), 'value' => $i, 'selected' => $birth_month));
        }
        $all_option_year = '<option value="0">' . __('Choose year', 'sb-theme') . '</option>';
        for($i = $year_max; $i >= $year_min; $i--) {
            $all_option_year .= self::get_option(array('text' => sprintf('%02d', $i), 'value' => $i, 'selected' => $birth_year));
        }
        if($birth_year < $year_min || $birth_year > $year_max) {
            $all_option_year .= self::get_option(array('text' => $birth_year, 'value' => $birth_year, 'selected' => $birth_year));
        }
        $container_class = isset($args['container_class']) ? $args['container_class'] : 'sb-birthday-field';
        $before = isset($args['before']) ? $args['before'] : '<p class="' . $container_class . '">';
        $after = isset($args['after']) ? $args['after'] : '</p>';
        $args['before'] = '';
        $field_class = isset($args['field_class']) ? $args['field_class'] : '';
        $name = isset($args['name']) ? $args['name'] : '';
        $id = isset($args['id']) ? $args['id'] : '';
        echo $before;
        if('vi' == $lang) {
            $args['all_option'] = $all_option_day;
            $args['field_class'] = SB_PHP::add_string_with_space_before($field_class, 'select-birth-day');
            $args['name'] = (empty($name)) ? 'birth_day' : $name . '_day';
            $args['id'] = (empty($id)) ? 'birth_day' : $id . '_day';
            self::select($args);
            echo $sep;
            $args['all_option'] = $all_option_month;
            $args['field_class'] = SB_PHP::add_string_with_space_before($field_class, 'select-birth-month');
            $args['name'] = (empty($name)) ? 'birth_month' : $name . '_month';
            $args['id'] = (empty($id)) ? 'birth_month' : $id . '_month';
            self::select($args);
            echo $sep;
            $args['all_option'] = $all_option_year;
            $args['field_class'] = SB_PHP::add_string_with_space_before($field_class, 'select-birth-year');
            $args['name'] = (empty($name)) ? 'birth_year' : $name . '_year';
            $args['id'] = (empty($id)) ? 'birth_year' : $id . '_year';
            self::select($args);
        } else {
            $args['all_option'] = $all_option_year;
            $args['field_class'] = SB_PHP::add_string_with_space_before($field_class, 'select-birth-year');
            $args['name'] = (empty($name)) ? 'birth_year' : $name . '_year';
            $args['id'] = (empty($id)) ? 'birth_year' : $id . '_year';
            self::select($args);
            echo $sep;
            $args['all_option'] = $all_option_month;
            $args['field_class'] = SB_PHP::add_string_with_space_before($field_class, 'select-birth-month');
            $args['name'] = (empty($name)) ? 'birth_month' : $name . '_month';
            $args['id'] = (empty($id)) ? 'birth_month' : $id . '_month';
            self::select($args);
            echo $sep;
            $args['all_option'] = $all_option_day;
            $args['field_class'] = SB_PHP::add_string_with_space_before($field_class, 'select-birth-day');
            $args['name'] = (empty($name)) ? 'birth_day' : $name . '_day';
            $args['id'] = (empty($id)) ? 'birth_day' : $id . '_day';
            self::select($args);
        }
        echo $after;
    }

    public static function build_menu_item_field_id($field_name, $item_id) {
        $id = str_replace('_', '-', $field_name);
        return 'edit-menu-item-' . $id . '-' . $item_id;
    }

    public static function build_menu_item_field_name($field_name, $item_id) {
        $name = str_replace('_', '-', $field_name);
        return 'menu-item-' . $name . '[' . $item_id . ']';
    }

    public static function build_menu_item_field_class($field_name) {
        $field_name = str_replace('_', '-', $field_name);
        return 'edit-menu-item-' . $field_name;
    }

    public static function build_menu_item_field_data($field_name, $item_id) {
        $result = array(
            'id' => self::build_menu_item_field_id($field_name, $item_id),
            'name' => self::build_menu_item_field_name($field_name, $item_id),
            'class' => self::build_menu_item_field_class($field_name)
        );
        return $result;
    }

    public static function menu_item_before($field_name, $item_id, $label = '', $container_class = '') {
        if(empty($container_class)) {
            $container_class = 'description-wide';
        }
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'margin-bottom-10 sbt-menu-field description field-' . $field_name);
        ?>
        <div class="<?php echo $container_class; ?>">
        <label for="edit-menu-item-<?php echo $field_name; ?>-<?php echo $item_id; ?>">
        <?php
        if(!empty($label)) {
            echo $label;
        }
        ?><br />
        <?php
    }

    public static function menu_item_after($description = '') {
        if(!empty($description)) {
            echo '<span class="description">' . $description . '</span>';
        }
        ?>
        </label>
        </div>
    <?php
    }

    public static function media_upload_group($args = array()) {
        $name = isset($args['name']) ? trim($args['name']) : '';
        if(empty($name)) {
            return;
        }
        $name = sb_build_meta_name($name);
        $value = isset($args['value']) ? trim($args['value']) : '';
        $field_class = isset($args['field_class']) ? trim($args['field_class']) : '';
        $field_class = SB_PHP::add_string_with_space_before($field_class, 'image-url image-upload-url');
        $upload_button_class = isset($args['upload_button_class']) ? trim($args['upload_button_class']) : '';
        $remove_button_class = isset($args['remove_button_class']) ? trim($args['remove_button_class']) : '';
        $upload_button_class = SB_PHP::add_string_with_space_before($upload_button_class, 'sb-button button sb-insert-media sb-add_media');
        $remove_button_class = SB_PHP::add_string_with_space_before($remove_button_class, 'sb-button button sb-remove-media sb-remove-image');
        if(!isset($args['before'])) {
            $args['before'] = '';
        }
        self::media_upload_no_preview($args);
    }

    public static function fieldset($args = array()) {
        $label = isset($args['label']) ? $args['label'] : '';
        $callback = isset($args['callback']) ? $args['callback'] : '';
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        ?>
        <fieldset class="<?php echo $container_class; ?>">
            <legend><?php echo $label; ?></legend>
            <?php call_user_func($callback); ?>
        </fieldset>
    <?php
    }

    public static function size($args = array()) {
        $field_class = isset($args['field_class']) ? $args['field_class'] : '';
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $description = isset($args['description']) ? $args['description'] : '';
        $id = isset($args['id']) ? $args['id'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
        $id_width = isset($args['id_width']) ? $args['id_width'] : '';
        $id_height = isset($args['id_height']) ? $args['id_height'] : '';
        $name_width = isset($args['name_width']) ? $args['name_width'] : '';
        $name_height = isset($args['name_height']) ? $args['name_height'] : '';
        $value = isset($args['value']) ? $args['value'] : array();
        $field_class = SB_PHP::add_string_with_space_before($field_class, 'sb-number image-size');
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'sb-size-field');
        $before = isset($args['before']) ? $args['before'] : '<p class="' . esc_attr($container_class) . '">';
        $after = isset($args['after']) ? $args['after'] : '';
        $sep = isset($args['sep']) ? $args['sep'] : '<span>x</span>';
        echo $before;
        self::label(array('for' => $id, 'text' => $label));
        $input_args = array(
            'type' => 'number',
            'id' => $id_width,
            'field_class' => $field_class,
            'name' => $name_width,
            'autocomplete' => false,
            'value' => $value[0],
            'only' => true,
        );
        self::text($input_args);
        echo $sep;
        $input_args['id'] = $id_height;
        $input_args['name'] = $name_height;
        $input_args['value'] = $value[1];
        self::text($input_args);
        self::the_description($description);
        echo $after;
    }

    public static function large_image_by_thumb($args = array()) {
        $name = isset($args['name']) ? $args['name'] : '';
        if(empty($name)) {
            return;
        }
        $id = isset($args['id']) ? $args['id'] : 'sb_theme_large_image_by_thumb';
        $id = SB_PHP::esc_id($id);
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'pair-images');

        $key = 'small';
        $thumb_name = $name . '[' . $key . ']';
        $thumb_id = $id . $key;
        $thumb_value = isset($args[$key . '_value']) ? $args[$key . '_value'] : '';
        $thumb_args = array(
            'id' => $thumb_id,
            'name' => $thumb_name,
            'value' => $thumb_value,
            'container_class' => $key . ' margin-bottom'
        );
        self::media_upload_with_remove_and_preview($thumb_args);

        $key = 'large';
        $full_name = $name . '[' . $key . ']';
        $full_id = $id . $key;
        $full_value = isset($args[$key . '_value']) ? $args[$key . '_value'] : '';
        $full_args = array(
            'id' => $full_id,
            'name' => $full_name,
            'value' => $full_value
        );
        self::media_upload_with_remove_and_preview($full_args);
    }

    public static function media_upload_with_remove_and_preview($args = array()) {
        $lang = SB_Core::get_language();
        $name = isset($args['name']) ? $args['name'] : '';
        $value = isset($args['value']) ? $args['value'] : '';
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $preview = isset($args['preview']) ? $args['preview'] : true;
        $id = isset($args['id']) ? $args['id'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
        $upload_button_class = isset($args['upload_button_class']) ? $args['upload_button_class'] : '';
        $remove_button_class = isset($args['remove_button_class']) ? $args['remove_button_class'] : '';
        $description = isset($args['description']) ? $args['description'] : '';
        if(empty($id) || empty($name)) {
            return;
        }
        $image_preview = '';
        $media_detail = SB_Option::get_media_detail($value);
        $value_id = $media_detail['id'];
        $value_url = $media_detail['url'];
        $image_preview_class = 'image-preview';
        if(!empty($value_url)) {
            $image_preview = sprintf('<img alt="" src="%s">', $value_url);
            $image_preview_class .= ' has-image';
        }
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'sb-media-upload');
        $upload_button_class = SB_PHP::add_string_with_space_before($upload_button_class, 'sb-button button sb-insert-media sb-add_media');
        $remove_button_class = SB_PHP::add_string_with_space_before($remove_button_class, 'sb-button button sb-remove-media sb-remove-image');
        $image_preview_div = '<div class="' . $image_preview_class . '">' . $image_preview . '</div>';
        $before = isset($args['before']) ? $args['before'] : '<div class="' . $container_class . '"><div class="image-upload-container">';
        $after = isset($args['after']) ? $args['after'] : '</div></div>';
        echo $before;
        if($preview) {
            echo $image_preview_div;
        }
        $field_class = isset($args['field_class']) ? $args['field_class'] : '';
        $args['name'] = $name . '[url]';
        $args['before'] = '';
        $args['description'] = '';
        $args['value'] = $value_url;
        $args['field_class'] = SB_PHP::add_string_with_space_before($field_class, 'image-url');
        $args['autocomplete'] = false;
        self::text($args);

        $args['label'] = '';
        $args['name'] = $name . '[id]';
        $args['field_class'] = SB_PHP::add_string_with_space_before($field_class, 'media-id');
        $args['type'] = 'hidden';
        $args['value'] = $value_id;
        self::text($args);
        $html = new SB_HTML('a');
        $atts = array(
            'href' => 'javascript:;',
            'class' => $upload_button_class,
            'title' => ('vi' == $lang) ? 'Thêm hình ảnh' : __('Add new image', 'sb-theme'),
            'text' => ('vi' == $lang) ? 'Tải lên' : __('Upload', 'sb-theme')
        );
        $html->set_attribute_array($atts);
        echo $html->build();
        $atts = array(
            'href' => 'javascript:;',
            'class' => $remove_button_class,
            'title' => ('vi' == $lang) ? 'Xóa hình ảnh' : __('Remove image', 'sb-theme'),
            'text' => ('vi' == $lang) ? 'Xóa' : __('Remove', 'sb-theme')
        );
        $html->set_attribute_array($atts);
        echo $html->build();
        if(!empty($description)) {
            echo '<p class="description">' . $description . '</p>';
        }
        self::the_after($before, $after);
    }

    public static function widget_area($args = array()) {
        $lang = SB_Core::get_language();
        $id = isset($args['id']) ? $args['id'] : '';
        $name = isset($args['name']) ? $args['name'] : '';
        $list_sidebars = isset($args['list_sidebars']) ? $args['list_sidebars'] : array();
        $description = isset($args['description']) ? $args['description'] : '';
        $default_sidebars = isset($args['default_sidebars']) ? $args['default_sidebars'] : array();
        ?>
        <div id="<?php echo $id; ?>" class="sb-theme-sidebar">
            <div class="sb-sidebar-group">
                <ul id="sb-sortable-sidebar" class="sb-sortable-list" data-icon-drag="<?php echo SB_CORE_URL . '/images/icon-drag-16.png'; ?>" data-icon-delete="<?php echo SB_CORE_URL . '/images/icon-delete-16.png'; ?>" data-sidebar="<?php echo count($list_sidebars); ?>" data-message-confirm="<?php _e('Bạn có muốn xóa hay không?', 'sb-theme'); ?>" data-name="<?php echo $name; ?>">
                    <li class="ui-state-disabled sb-default-sidebar">
                        <div class="sb-sidebar-line">
                            <input type="text" name="sidebar_default_0_name" value="<?php echo ('vi' == $lang) ? 'Tên sidebar' : __('Sidebar name', 'sb-theme'); ?>" autocomplete="off" disabled>
                            <input type="text" name="sidebar_default_0_description" value="<?php echo ('vi' == $lang) ? 'Mô tả cho sidebar' : __('Sidebar description', 'sb-theme'); ?>" autocomplete="off" disabled>
                            <input type="text" name="sidebar_default_0_id" value="<?php _e('Sidebar id', 'sb-theme'); ?>" autocomplete="off" disabled>
                        </div>
                        <img alt="" class="sb-icon-drag" src="<?php echo SB_CORE_URL . '/images/icon-drag-16.png'; ?>">
                        <img alt="" class="sb-icon-delete" src="<?php echo SB_CORE_URL . '/images/icon-delete-16.png'; ?>">
                    </li>
                    <?php $count = 1; foreach($default_sidebars as $value) : ?>
                        <li class="ui-state-disabled sb-default-sidebar">
                            <div class="sb-sidebar-line">
                                <input type="text" name="sidebar_default_<?php echo $count; ?>_name" value="<?php echo $value['name']; ?>" autocomplete="off" disabled>
                                <input type="text" name="sidebar_default_<?php echo $count; ?>_description" value="<?php echo $value['description']; ?>" autocomplete="off" disabled>
                                <input type="text" name="sidebar_default_<?php echo $count; ?>_id" value="<?php echo $value['id']; ?>" autocomplete="off" disabled>
                            </div>
                            <img alt="" class="sb-icon-drag" src="<?php echo SB_CORE_URL . '/images/icon-drag-16.png'; ?>">
                            <img alt="" class="sb-icon-delete" src="<?php echo SB_CORE_URL . '/images/icon-delete-16.png'; ?>">
                        </li>
                    <?php $count++; endforeach; ?>
                    <?php $count = 1; foreach($list_sidebars as $sidebar) : ?>
                        <li class="ui-state-default sb-user-sidebar" data-sidebar="<?php echo $count; ?>">
                            <div class="sb-sidebar-line">
                                <input type="text" name="<?php echo $name . '[' . $count . '][name]'; ?>" value="<?php echo $sidebar['name']; ?>" autocomplete="off">
                                <input type="text" name="<?php echo $name . '[' . $count . '][description]'; ?>" value="<?php echo $sidebar['description']; ?>" autocomplete="off">
                                <input type="text" name="<?php echo $name . '[' . $count . '][id]'; ?>" value="<?php echo $sidebar['id']; ?>" autocomplete="off">
                            </div>
                            <img alt="" class="sb-icon-drag" src="<?php echo SB_CORE_URL . '/images/icon-drag-16.png'; ?>">
                            <img alt="" class="sb-icon-delete" src="<?php echo SB_CORE_URL . '/images/icon-delete-16.png'; ?>">
                        </li>
                    <?php $count++; endforeach; ?>
                </ul>
                <input type="hidden" name="<?php echo $name; ?>[count]" value="<?php echo count($list_sidebars); ?>" class="sb-sidebar-count">
            </div>
            <button class="button sb-add-sidebar"><?php echo ('vi' == $lang) ? 'Thêm sidebar mới' : __('Add new sidebar', 'sb-theme'); ?></button>
        </div>
        <?php
    }
    
    public static function sortable_ui_connect($args = array()) {
        $option_name = isset($args['option_name']) ? $args['option_name'] : '';
        if(empty($option_name)) {
            $option_name = isset($args['name']) ? $args['name'] : '';
        }
        $sortable_class = isset($args['sortable_class']) ? $args['sortable_class'] : '';
        $sortable_active_class = isset($args['sortable_active_class']) ? $args['sortable_active_class'] : '';
        $sortable_callback = isset($args['sortable_callback']) ? $args['sortable_callback'] : '';
        $sortable_active_callback = isset($args['sortable_active_callback']) ? $args['sortable_active_callback'] : '';
        if(empty($option_name) || empty($sortable_callback) || empty($sortable_active_callback)) {
            return;
        }
        $column = isset($args['column']) ? (bool)$args['column'] : true;
        $sortable_class = SB_PHP::add_string_with_space_before($sortable_class, 'connected-sortable sb-sortable-list sortable-source');
        $sortable_active_class = SB_PHP::add_string_with_space_before($sortable_active_class, 'connected-sortable active-sortable sb-sortable-list');
        if($column) {
            $sortable_class = SB_PHP::add_string_with_space_before($sortable_class, 'left min-height');
            $sortable_active_class = SB_PHP::add_string_with_space_before($sortable_active_class, 'min-height right');
        } else {
            $sortable_class = SB_PHP::add_string_with_space_before($sortable_class, 'sortable-row');
            $sortable_active_class = SB_PHP::add_string_with_space_before($sortable_active_class, 'sortable-row');
        }
        $click_to_connect = isset($args['click_to_connect']) ? (bool)$args['click_to_connect'] : false;
        if($click_to_connect) {
            $sortable_class = SB_PHP::add_string_with_space_before($sortable_class, 'click-to-connect');
            $sortable_active_class = SB_PHP::add_string_with_space_before($sortable_active_class, 'click-to-connect');
        }
        $field_class = isset($args['field_class']) ? $args['field_class'] : '';
        $sortable_class = SB_PHP::add_string_with_space_before($sortable_class, $field_class);
        $sortable_active_class = SB_PHP::add_string_with_space_before($sortable_active_class, $field_class);
        $bottom_description = isset($args['bottom_description']) ? (bool)$args['bottom_description'] : true;
        $value = isset($args['value']) ? $args['value'] : '';
        $before = isset($args['before']) ? $args['before'] : '<div class="sb-sortable"><div class="sb-sortable-container">';
        $after = isset($args['after']) ? $args['after'] : '</div></div>';
        $sortable_description = isset($args['sortable_description']) ? $args['sortable_description'] : '';
        $sortable_active_description = isset($args['sortable_active_description']) ? $args['sortable_active_description'] : '';
        $sortable_label = isset($args['sortable_label']) ? $args['sortable_label'] : '';
        $sortable_active_label = isset($args['sortable_active_label']) ? $args['sortable_active_label'] : '';
        echo $before;
        if(!empty($sortable_label)) {
            $lb_args = array(
                'text' => $sortable_label,
                'for' => $option_name . '_sortable'
            );
            self::label($lb_args);
        }
        ?>
        <ul class="<?php echo $sortable_class; ?>">
            <?php call_user_func($sortable_callback); ?>
        </ul>
        <?php
        self::the_description($sortable_description);
        if(!empty($sortable_active_label)) {
            $lb_args = array(
                'text' => $sortable_active_label,
                'for' => $option_name . '_sortable_active'
            );
            self::label($lb_args);
        }
        ?>
        <ul class="<?php echo $sortable_active_class; ?>">
            <?php call_user_func($sortable_active_callback); ?>
        </ul>
        <?php self::the_description($sortable_active_description); ?>
        <input type="hidden" class="active-sortable-value" name="<?php echo $option_name; ?>" value="<?php echo $value; ?>" autocomplete="off">
        <?php self::the_after($before, $after); ?>
        <?php if($bottom_description) : ?>
            <div style="clear: both"></div>
            <?php
            self::the_description(__('Drag and drop the widget into right box to active it.', 'sb-theme'));
        endif;
    }

    public static function sortable_ui_connect_row($args = array()) {
        $args['column'] = false;
        $args['bottom_description'] = false;
        self::sortable_ui_connect($args);
    }

    public static function sortable_term($args = array()) {
        $option_name = isset($args['option_name']) ? $args['option_name'] : '';
        if(empty($option_name)) {
            $option_name = isset($args['name']) ? $args['name'] : '';
        }
        $sortable_class = isset($args['sortable_class']) ? $args['sortable_class'] : '';
        $sortable_active_class = isset($args['sortable_active_class']) ? $args['sortable_active_class'] : '';
        $term_args = isset($args['term_args']) ? $args['term_args'] : array();
        $taxonomy = isset($args['taxonomy']) ? $args['taxonomy'] : '';
        if(empty($option_name) || empty($taxonomy)) {
            return;
        }
        $sortable_class = SB_PHP::add_string_with_space_before($sortable_class, 'connected-sortable sb-sortable-list left min-height sortable-source');
        $sortable_active_class = SB_PHP::add_string_with_space_before($sortable_active_class, 'connected-sortable active-sortable sb-sortable-list min-height right');
        $active_terms = SB_Option::get_theme_option_single_key($option_name);
        $term_args['exclude'] = $active_terms;
        $terms = SB_Term::get($taxonomy, $term_args);
        $base_key = isset($args['base_key']) ? $args['base_key'] : 'theme';
        ?>
        <div class="sb-sortable">
            <div class="sb-sortable-container">
                <ul class="<?php echo $sortable_class; ?>">
                    <?php foreach($terms as $term) : ?>
                        <li data-term="<?php echo $term->term_id; ?>" class="ui-state-default"><?php echo $term->name; ?></li>
                    <?php endforeach; ?>
                </ul>
                <ul class="<?php echo $sortable_active_class; ?>">
                    <?php $terms = $active_terms; $active_terms = explode(',', $active_terms); ?>
                    <?php foreach($active_terms as $term_id) : if($term_id < 1) continue; $term = get_term($term_id, $taxonomy); ?>
                        <?php if(!$term) continue; ?>
                        <li data-term="<?php echo $term->term_id; ?>" class="ui-state-default"><?php echo $term->name; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <input type="hidden" class="active-sortable-value" name="sb_options[<?php echo $base_key; ?>][<?php echo $option_name; ?>]" value="<?php echo $terms; ?>">
        </div>
        <div style="clear: both"></div>
        <?php
        $desc = ('vi' == SB_Core::get_language()) ? 'Kéo và thả đối tượng sang ô bên phải để kích hoạt.' : __('Drag and drop item into right box to make it active.', 'sb-theme');
        self::the_description($desc);
    }

    public static function rss_feed($args = array()) {
        $id = isset($args['id']) ? $args['id'] : '';
        $name = isset($args['name']) ? $args['name'] : '';
        $list_feeds = isset($args['list_feeds']) ? $args['list_feeds'] : array();
        $description = isset($args['description']) ? $args['description'] : '';
        $order = isset($args['order']) ? $args['order'] : '';
        $count = SB_Option::get_theme_option(array('keys' => array('rss_feed', 'count')));
        if($count > count($list_feeds)) {
            $count = count($list_feeds);
        }
        $real_count = $count;
        $next_id = 1;
        $out_remove = isset($args['out_remove']) ? (bool)$args['out_remove'] : true;
        $field_class = isset($args['field_class']) ? $args['field_class'] : '';
        $field_class = SB_PHP::add_string_with_space_before($field_class, 'sb-sortable-list single-sortable');
        if($out_remove) {
            $field_class = SB_PHP::add_string_with_space_before($field_class, 'out-remove');
        }
        ?>
        <div id="<?php echo esc_attr($id); ?>" class="sb-addable rss-feed min-height relative gray-bg border padding-10 sb-ui-panel">
            <div class="item-group">
                <ul class="<?php echo $field_class; ?>" data-message-confirm="<?php echo SB_Message::get_confirm_delete_text(); ?>">
                    <?php
                    if(0 == $count) {
                        $count++;
                        SB_Admin_Custom::set_current_rss_feed_item(array('name' => $name, 'count' => $count));
                        sb_core_get_loop('loop-admin-rss-feed');
                        $real_count = $count;
                        $order = $count;
                        $next_id++;
                    }
                    if(0 < $count) {
                        $new_count = 1;
                        foreach($list_feeds as $feed) {
                            $feed_id = isset($feed['id']) ? $feed['id'] : 0;
                            if($feed_id >= $next_id) {
                                $next_id = $feed_id + 1;
                                SB_Admin_Custom::set_current_rss_feed_item(array('feed' => $feed, 'count' => $new_count, 'name' => $name));
                                sb_core_get_loop('loop-admin-rss-feed');
                                $new_count++;
                            }
                        }
                    }
                    ?>
                </ul>
                <input type="hidden" name="<?php echo $name; ?>[order]" value="<?php echo $order; ?>" class="ui-item-order item-order" autocomplete="off">
                <input type="hidden" name="<?php echo $name; ?>[count]" value="<?php echo $real_count; ?>" class="ui-item-count item-count" autocomplete="off">
            </div>
            <button class="button add-item ui-add-item absolute" data-type="rss_feed" data-name="<?php echo $name; ?>" data-count="<?php echo $count; ?>" data-next-id="<?php echo $next_id; ?>"><?php _e('Add new', 'sb-theme'); ?></button>
            <button class="button reset-item ui-reset-item absolute reset" data-type="rss_feed"><?php _e('Reset', 'sb-theme'); ?> <img alt="" src="<?php echo SB_CORE_URL; ?>/images/ajax-loader.gif"></button>
        </div>
        <?php
        self::the_description($description);
    }

    public static function text_field($args = array()) {
        self::text($args);
    }

    public static function set_attributes($html, $attributes) {
        foreach($attributes as $key => $att) {
            $html->set_attribute($key, $att);
        }
        return $html;
    }

    public static function captcha($args = array()) {
        SB_Core::the_captcha($args);
        $placeholder_text = ('vi' == SB_Core::get_language()) ? 'Nhập mã bảo mật' : __('Enter captcha code', 'sb-theme');
        $placeholder = isset($args['placeholder']) ? $args['placeholder'] : $placeholder_text;
        $args['placeholder'] = $placeholder;
        $args['before'] = '';
        $field_class = isset($args['field_class']) ? $args['field_class'] : '';
        $field_class = SB_PHP::add_string_with_space_before($field_class, 'captcha-code');
        $args['field_class'] = $field_class;
        self::text($args);
    }

    public static function convert_rate($args = array()) {
        self::text($args);
    }

	public static function iframe($args = array()) {
		$src = isset($args['src']) ? $args['src'] : '';
		if(empty($src)) {
			return;
		}
		$class = isset($args['class']) ? $args['class'] : '';
		$class = SB_PHP::add_string_with_space_before($class, 'sb-iframe');
		$iframe = new SB_HTML('iframe');
		$iframe->set_attribute('src', $src);
		$iframe->set_class($class);
		echo $iframe->build();
	}

    public static function input($args = array()) {
        self::text($args);
    }

    public static function input_hidden($args = array()) {
        $args['type'] = 'hidden';
        $args['container'] = false;
        self::input($args);
    }

    public static function text($args = array()) {
        $with_button = isset($args['button']) ? $args['button'] : false;
        $type = isset($args['type']) ? $args['type'] : 'text';
        $id = isset($args['id']) ? $args['id'] : '';
        $name = isset($args['name']) ? $args['name'] : '';
        if(empty($name)) {
            $id = $name;
        }
        $value = isset($args['value']) ? $args['value'] : '';
        $description = isset($args['description']) ? $args['description'] : '';
        $field_class = isset($args['field_class']) ? $args['field_class'] : '';
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        if('checkbox' != $type && 'radio' != $type) {
            $value = trim($value);
        }
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'sb-' . $type . '-field');
        $widefat = isset($args['widefat']) ? $args['widefat'] : true;
        if($widefat) {
            $field_class = SB_PHP::add_string_with_space_before($field_class, 'widefat');
        }
        $label = isset($args['label']) ? $args['label'] : '';

        $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
        $autocomplete = isset($args['autocomplete']) ? $args['autocomplete'] : true;
        if($with_button) {
            $container_class .= ' has-button';
        }
        $before = isset($args['before']) ? $args['before'] : '<div class="' . esc_attr($container_class) . '">';
        $after = isset($args['after']) ? $args['after'] : '</div>';
        $only = isset($args['only']) ? $args['only'] : false;
        $container = isset($args['container']) ? (bool)$args['container'] : true;
        if(!$only && !$container) {
            $only = true;
        }
        $html = new SB_HTML('input');

        $atts = array(
            'type' => esc_attr($type),
            'id' => esc_attr($id),
            'name' => esc_attr($name),
            'value' => esc_attr($value),
            'autocomplete' => (bool)$autocomplete ? '' : 'off',
            'class' => esc_attr($field_class)
        );
        if(!empty($placeholder)) {
            $atts['placeholder'] = $placeholder;
        }
        if('checkbox' == $type || 'radio' == $type) {
            $option_value = isset($args['option_value']) ? $args['option_value'] : '';
            $field_value = 1;
            if(!empty($option_value)) {
                $field_value = $option_value;
            }
            $checked = checked($field_value, $value, false);
            if(!empty($checked)) {
                $atts['checked'] = 'checked';
            }
            $atts['value'] = $field_value;
            if(!isset($args['autocomplete'])) {
                $atts['autocomplete'] = 'off';
            }
        }
        $html->set_attribute_array($atts);
        $attributes = isset($args['attributes']) ? $args['attributes'] : array();
        $readonly = isset($args['readonly']) ? $args['readonly'] : false;
        if($readonly) {
            $attributes['readonly'] = 'readonly';
        }
        $html->set_attribute_array($attributes);
        if($only) {
            echo $html->build();
        } else {
            echo $before;
            $label_class = isset($args['label_class']) ? $args['label_class'] : '';
            if(!empty($label) && 'checkbox' != $type && 'radio' != $type) {
                self::label(array('text' => $label, 'for' => $id, 'class' => $label_class));
            }
            echo $html->build();
            if('checkbox' == $type || 'radio' == $type) {
                $label_class = SB_PHP::add_string_with_space_before($label_class, $type . '-label');
                self::label(array('text' => $label, 'for' => $id, 'attributes' => array('class' => $label_class)));
            }

            if($with_button) {
                $args = isset($args['button_args']) ? $args['button_args'] : array();
                $field_class = isset($args['field_class']) ? $args['field_class'] : '';
                $field_class = SB_PHP::add_string_with_space_before($field_class, 'button button-primary');
                $args['field_class'] = $field_class;
                self::button($args);
            }
            self::the_description($description);
            self::the_after($before, $after);
        }
    }

    public static function textarea($args = array()) {
        $id = isset($args['id']) ? $args['id'] : '';
        $name = isset($args['name']) ? $args['name'] : '';
        $value = isset($args['value']) ? $args['value'] : '';
        $description = isset($args['description']) ? $args['description'] : '';
        $field_class = isset($args['field_class']) ? $args['field_class'] : '';
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $value = trim($value);
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'sb-textarea-field');
        $field_class = SB_PHP::add_string_with_space_before($field_class, 'widefat');
        $after = isset($args['after']) ? $args['after'] : '</div>';
        $type = isset($args['type']) ? $args['type'] : 'text';
        $autocomplete = isset($args['autocomplete']) ? $args['autocomplete'] : true;
        $before = isset($args['before']) ? $args['before'] : '<div class="' . esc_attr($container_class) . '">';
        $row = isset($args['row']) ? $args['row'] : 4;
        if(isset($args['textarea_rows'])) {
            $row = $args['textarea_rows'];
        }
        echo $before;
        $html = new SB_HTML('textarea');
        $atts = array(
            'type' => esc_attr($type),
            'id' => esc_attr($id),
            'name' => esc_attr($name),
            'text' => esc_attr($value),
            'autocomplete' => (bool)$autocomplete ? 'off' : '',
            'class' => esc_attr($field_class),
            'rows' => esc_attr($row)
        );
        $html->set_attribute_array($atts);
        self::label(array('for' => $id, 'text' => isset($args['label']) ? $args['label'] : ''));
        echo $html->build();
        self::the_description($description);
        self::the_after($before, $after);
    }

    public static function number_field($args = array()){
        self::number($args);
    }

    public static function row_user_profile_field($args = array()) {
        $name = isset($args['name']) ? $args['name'] : '';
        if(empty($name)) {
            $name = isset($args['id']) ? $args['id'] : '';
        }
        $label = isset($args['label']) ? $args['label'] : '';
        $field_type = isset($args['field_type']) ? $args['field_type'] : 'text';
        ?>
        <tr>
            <th><label for="<?php echo $name; ?>"><?php echo $label; ?></label></th>
            <td>
                <?php
                $args['label'] = '';
                $args['name'] = $name;
                $args['id'] = $name;
                switch($field_type) {
                    case 'text':
                        self::text($args);
                        break;
                    case 'select_gender':
                        self::select_gender($args);
                        break;
                    case 'select_birthday':
                        self::select_birthday($args);
                        break;
                }
                ?>
            </td>
        </tr>
        <?php
    }

    public static function number($args = array()) {
        $args['type'] = 'number';
        self::text($args);
    }

    public static function number_double($args = array()) {
        $atts = isset($args['attributes']) ? $args['attributes'] : array();
        $step = isset($args['step']) ? $args['step'] : '0.01';
        $atts['step'] = $step;
        $args['attributes'] = $atts;
        self::number($args);
    }

    public static function icon_delete($args = array()) {
        $confirm_message = isset($args['confirm_message']) ? $args['confirm_message'] : SB_Message::get_confirm_delete_text();
        $img = new SB_HTML('img');
        $img->set_class('icon-delete');
        $img->set_attribute('src', SB_Theme::get_image_url('icon-delete-16.png'));
        $img->set_attribute('data-message', $confirm_message);
        echo $img->build();
    }

    public static function checkbox($args = array()) {
        $args['type'] = 'checkbox';
        $args['autocomplete'] = false;
        self::text($args);
    }

    public static function build_publish_box_class($args = array()) {
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'misc-pub-section');
        $args['container_class'] = $container_class;
        return $args;
    }

    public static function radio($args = array()) {
        $args['type'] = 'radio';
        $args['autocomplete'] = false;
        self::text($args);
    }

    public static function switch_button($args = array()) {
        $lang = SB_Core::get_language();
        $id = isset($args['id']) ? $args['id'] : '';
        $name = isset($args['name']) ? $args['name'] : '';
        $value = isset($args['value']) ? $args['value'] : 0;
        $description = isset($args['description']) ? $args['description'] : '';

        $enable = (bool) $value;
        $class = 'switch-button';
        $class_on = $class . ' on';
        $class_off = $class . ' off';
        if($enable) {
            $class_on .= ' active';
        } else {
            $class_off .= ' active';
        }
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $container_class .= ' sbtheme-switch';
        $before = isset($args['before']) ? $args['before'] : '<fieldset class="' . esc_attr($container_class) . '"><div class="switch-options">';
        $after = isset($args['after']) ? $args['after'] : '</div></fieldset>';
        echo $before;
        $attributes = array(
            'data-switch' => 'on',
            'class' => $class_on . ' left'
        );
        $button_text = ('vi' == $lang) ? 'Bật' : __('On', 'sb-theme');
        self::label(array('text' => '<span>' . $button_text . '</span>', 'attributes' => $attributes));
        $attributes = array(
            'data-switch' => 'off',
            'class' => $class_off . ' right'
        );
        $button_text = ('vi' == $lang) ? 'Tắt' : __('Off', 'sb-theme');
        self::label(array('text' => '<span>' . $button_text . '</span>', 'attributes' => $attributes));
        $args['type'] = 'hidden';
        $args['only'] = true;
        $args['field_class'] = 'checkbox checkbox-input';
        $args['value'] = $value;
        $args['autocomplete'] = false;
        self::text($args);
        self::the_description($description);
        echo $after;
    }

    public static function button($args = array()) {
        $text = isset($args['text']) ? $args['text'] : '';
        if(empty($text)) {
            return;
        }
        $class = isset($args['field_class']) ? $args['field_class'] : '';
        $class = SB_PHP::add_string_with_space_before($class, 'sb-button');
        $description = isset($args['description']) ? $args['description'] : '';
        $atts = isset($args['attributes']) ? $args['attributes'] : array();
        $button = new SB_HTML('button');
        $button->set_class($class);
        $button->set_text($text);
        $button->set_attribute_array($atts);
        echo $button->build();
        self::the_description($description);
    }

    public static function the_description($text) {
        if(!empty($text)) {
            echo '<p class="description">' . $text . '</p>';
        }
    }

    public static function select($args = array()) {
        $id = isset($args['id']) ? $args['id'] : '';
        $name = isset($args['name']) ? $args['name'] : '';
        $list_options = isset($args['list_options']) ? $args['list_options'] : array();
        $options = isset($args['options']) ? $args['options'] : array();
        $description = isset($args['description']) ? $args['description'] : '';
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'sb-field-select');
        $load_item = isset($args['load_item']) ? $args['load_item'] : true;
        $value = isset($args['value']) ? $args['value'] : '';
        $field_class = isset($args['field_class']) ? $args['field_class'] : 'widefat';
        if(!is_array($options) || count($options) < 1) {
            $options = $list_options;
        }
        $all_option = isset($args['all_option']) ? $args['all_option'] : '';
        $before = isset($args['before']) ? $args['before'] : '<div class="' . $container_class . '">';
        $after = isset($args['after']) ? $args['after'] : '</div>';
        echo $before;
        $autocomplete = isset($args['autocomplete']) ? $args['autocomplete'] : false;
        if(!$autocomplete) {
            $autocomplete = 'off';
        }
        $select_option = isset($args['default_option']) ? $args['default_option'] : '';
        if($load_item && empty($all_option)) {
            foreach($options as $key => $text) {
                $select_option .= self::get_option(array('value' => $key, 'text' => $text, 'selected' => $value));
            }
        } else {
            $select_option .= $all_option;
        }
        if(!$load_item) {
            $custom_options = isset($args['custom_options']) ? $args['custom_options'] : '';
            $select_option .= $custom_options;
        }
        $html = new SB_HTML('select');
        $attributes = isset($args['attributes']) ? SB_PHP::to_array($args['attributes']) : array();
        $atts = array(
            'id' => esc_attr($id),
            'name' => $name,
            'class' => $field_class,
            'autocomplete' => $autocomplete,
            'text' => $select_option
        );
        $html->set_attribute_array($atts);
        foreach($attributes as $key => $value) {
            $html->set_attribute($key, $value);
        }
        $args = array(
            'text' => isset($args['label']) ? $args['label'] : '',
            'for' => $name
        );
        self::label($args);
        echo $html->build();
        self::the_description($description);
        self::the_after($before, $after);
    }

    public static function select_page($args = array()) {
        $pages = SB_Post::get_all('page');
        $choose_text = 'Chọn trang';
        if('vi' != SB_Core::get_language()) {
            $choose_text = __('Choose page', 'sb-theme');
        }
        $all_option = '<option value="0">-- ' . $choose_text . ' --</option>';
        $value = isset($args['value']) ? $args['value'] : '';
        while($pages->have_posts()) {
            $pages->the_post();
            $post_id = get_the_ID();
            $all_option .= '<option value="' . esc_attr($post_id) . '" ' . selected($value, $post_id, false) . '>' . get_the_title() . '</option>';
        }
        wp_reset_postdata();
        $args['all_option'] = $all_option;
        self::select($args);
    }

    public static function select_country($args = array()) {
        $class = isset($args['class']) ? $args['class'] : '';
        if(empty($class)) {
            $class = isset($args['field_class']) ? $args['field_class'] : '';
        }
        $class = SB_PHP::add_string_with_space_before($class, 'sb-select-country');
        $args['field_class'] = $class;
        $select_none = isset($args['select_none']) ? $args['select_none'] : '<option value=""></option>';
        $countries = SB_PHP::get_countries();
        $all_option = $select_none;
        foreach($countries as $code => $country) {
            $option = self::get_option(array('value' => $code, 'text' => $country['name']));
            $all_option .= $option;
        }
        $args['all_option'] = $all_option;
        self::select($args);
    }

    public static function select_post($args = array()) {
        $post_type = isset($args['post_type']) ? $args['post_type'] : 'post';
        $type = SB_Core::get_post_type_info($post_type);
        $all_option = isset($args['all_option']) ? $args['all_option'] : '';
        $value = isset($args['value']) ? $args['value'] : '';
        if(empty($all_option)) {
            $option_default = '';
            if(isset($args['option_default'])) {
                $option_default = $args['option_default'];
            } else {
                $default_text = isset($args['default_text']) ? $args['default_text'] : sprintf(__('-- Chọn %s --', 'sb-theme'), SB_PHP::lowercase($type->labels->singular_name));
                $option_default = '<option value="0" data-post-type="">' . $default_text . '</option>';
            }
            $all_option = $option_default;
            $load_item = isset($args['load_item']) ? $args['load_item'] : true;
            if($load_item) {
                $query_args = isset($args['query_args']) ? $args['query_args'] : array();
                if(!isset($query_args['post_type'])) {
                    $query_args['post_type'] = $post_type;
                }
                $query = SB_Query::get($query_args);
                global $post;
                $save_post = $post;
                if($query->have_posts()) {
                    while($query->have_posts()) {
                        $query->the_post();
                        $all_option .= self::get_option(array('value' => get_the_ID(), 'attributes' => array('data-post-type' => $type->name), 'selected' => $value, 'text' => get_the_title()));
                    }
                    wp_reset_postdata();
                }
                $post = $save_post;
            }
        }
        $args['all_option'] = $all_option;
        if(!isset($args['attributes']['data-post-type'])) {
            $args['attributes']['data-post-type'] = $post_type;
        }
        $post_type_slug = isset($type->rewrite['slug']) ? $type->rewrite['slug'] : '';
        if(!empty($post_type_slug)) {
            $post_type_slug .= '-container';
        }
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, $post_type_slug);
        $args['container_class'] = $container_class;
        self::select($args);
    }

    public static function select_term_field($args = array()) {
        self::select_term($args);
    }

    public static function label($args = array()) {
        $text = isset($args['text']) ? $args['text'] : '';
        if(empty($text)) {
            return;
        }
        $html = new SB_HTML('label');
        $atts = array(
            'for' => isset($args['for']) ? $args['for'] : '',
            'text' => isset($args['text']) ? $args['text'] : '',
            'class' => isset($args['class']) ? $args['class'] : ''
        );
        $html->set_attribute_array($atts);
        $attributes = isset($args['attributes']) ? $args['attributes'] : array();
        $html = self::set_attributes($html, $attributes);
        echo $html->build();
    }

    public static function select_term($args = array()) {
        $taxonomy = isset($args['taxonomy']) ? $args['taxonomy'] : '';
        $options = isset($args['options']) ? $args['options'] : array();
        $force_empty = isset($args['force_empty']) ? (bool)$args['force_empty'] : false;
        $tax = get_taxonomy($taxonomy);
        if(!$force_empty) {
            if(empty($taxonomy) && (!is_array($options) || count($options) < 1)) {
                return;
            }
            if(!is_object($tax) && (!is_array($options) || count($options) < 1)) {
                return;
            }
        }
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'sb-term-field');

        $id = isset($args['id']) ? $args['id'] : '';
        $name = isset($args['name']) ? $args['name'] : '';
        $field_class = isset($args['field_class']) ? $args['field_class'] : '';
        if(is_object($tax)) {
            $field_class = SB_PHP::add_string_with_space_before($field_class, 'select-' . $tax->rewrite['slug'] . '-terms');
        }
        $args['field_class'] = SB_PHP::add_string_with_space_before($field_class, 'select-term');
        $label = isset($args['label']) ? $args['label'] : '';

        $value = isset($args['value']) ? $args['value'] : '';
        $description = isset($args['description']) ? $args['description'] : '';

        $taxonomy_id = isset($args['taxonomy_id']) ? $args['taxonomy_id'] : '';
        $taxonomy_name = isset($args['taxonomy_name']) ? $args['taxonomy_name'] : '';
        $show_count = isset($args['show_count']) ? $args['show_count'] : true;
        $before = isset($args['before']) ? $args['before'] : '<p class="' . esc_attr($container_class) . '">';
        $after = isset($args['after']) ? $args['after'] : '</p>';
        echo $before;
        self::label(array('for' => $id, 'text' => $label));

        $load_item = isset($args['load_item']) ? (bool)$args['load_item'] : true;

        $option_default = '';

        if(isset($args['option_default'])) {
            $option_default = $args['option_default'];
        } else {
            $default_text = isset($args['default_text']) ? $args['default_text'] : sprintf(__('-- Chọn %s --', 'sb-theme'), SB_PHP::lowercase($tax->labels->singular_name));
            $option_default = '<option value="0" data-taxonomy="">' . $default_text . '</option>';
        }

        $all_option = $option_default;
        $args['before'] = '';
        if($load_item && count($options) > 0) {
            if(count($options) > 1) {
                foreach($options as $tax) {
                    $terms = get_terms($tax->name);
                    if(!SB_Core::is_error($terms) && count($terms) > 0) {
                        $tmp = '<optgroup label="' . $tax->labels->singular_name . '">';
                        foreach($terms as $cat) {
                            if(!is_object($cat)) {
                                continue;
                            }
                            $option_text = $cat->name . (($show_count) ? ' (' . $cat->count . ')' : '');
                            $tmp .= self::get_option(array('value' => $cat->term_id, 'attributes' => array('data-taxonomy' => $tax->name), 'selected' => $value, 'text' => $option_text));
                        }
                        $tmp .= '</optgroup>';
                        $all_option .= $tmp;
                    }
                }
            } else {
                $tax = array_shift($options);
                $terms = get_terms($tax->name);
                if(!SB_Core::is_error($terms) && count($terms) > 0) {
                    foreach($terms as $cat) {
                        if(!is_object($cat)) {
                            continue;
                        }
                        $option_text = $cat->name . (($show_count) ? ' (' . $cat->count . ')' : '');
                        $all_option .= self::get_option(array('value' => $cat->term_id, 'attributes' => array('data-taxonomy' => $tax->name), 'selected' => $value, 'text' => $option_text));
                    }
                }
            }
        } else {
            if($load_item) {
                $terms = SB_Term::get($taxonomy);
                if(!SB_Core::is_error($terms) && count($terms) > 0) {
                    foreach($terms as $cat) {
                        if(!is_object($cat)) {
                            continue;
                        }
                        $all_option .= self::get_option(array('value' => $cat->term_id, 'attributes' => array('data-taxonomy' => $taxonomy), 'selected' => $value, 'text' => $cat->name));
                    }
                }
            }
        }

        $args['all_option'] = $all_option;
        $args['label'] = '';
        if(!isset($args['attributes']['data-taxonomy'])) {
            $args['attributes']['data-taxonomy'] = $taxonomy;
        }
        $args['attributes']['data-show-count'] = absint($show_count);
        self::select($args);
        if(!empty($taxonomy_name)) {
            $args['id'] = $taxonomy_id;
            $args['name'] = $taxonomy_name;
            $args['value'] = $taxonomy;
            $args['field_class'] = 'widefat taxonomy';
            $args['type'] = 'hidden';
            self::text($args);
        }
        echo $after;
    }

    public static function social_field($args = array()) {
        self::social($args);
    }

    public static function social($args = array()) {
        foreach($args as $field) {
            $id = isset($field['id']) ? $field['id'] : '';
            $name = isset($field['name']) ? $field['name'] : '';
            $value = isset($field['value']) ? $field['value'] : '';
            if(empty($name)) {
                continue;
            }
            $description = isset($field['description']) ? $field['description'] : '';
            $new_args = array(
                'before' => '<div class="margin-bottom">',
                'id' => $id,
                'name' => $name,
                'value' => $value,
                'description' => $description
            );
            self::text_field($new_args);
        }
    }

    public static function rich_editor_field($args = array()) {
        self::rich_editor($args);
    }

    public static function rich_editor($args = array()) {
        $id = isset($args['id']) ? $args['id'] : '';
        $name = isset($args['name']) ? $args['name'] : '';
        $value = isset($args['value']) ? $args['value'] : '';
        $description = isset($args['description']) ? $args['description'] : '';
        $textarea_row = isset($args['textarea_row']) ? $args['textarea_row'] : 0;
        if(empty($textarea_row) || $textarea_row < 1) {
            $textarea_row = isset($args['row']) ? $args['row'] : 5;
        }
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'sb-rich-editor editor-field');
        $before = isset($args['before']) ? $args['before'] : '<div id="' . esc_attr($id) . '_editor" class="' . $container_class . '">';
        $after = isset($args['after']) ? $args['after'] : '</div>';
        $editor_args = isset($args['editor_args']) ? $args['editor_args'] : array();
        $label = isset($args['label']) ? $args['label'] : '';
        $args = array(
            'textarea_name' => $name,
            'textarea_rows' => $textarea_row,
            'editor_class' => isset($args['field_class']) ? $args['field_class'] : ''
        );
        $args = wp_parse_args($args, $editor_args);
        echo $before;
        self::label(array('for' => $id, 'text' => $label));
        wp_editor($value, $id, $args);
        self::the_description($description);
        self::the_after($before, $after);
    }

    public static function color_picker($args) {
        $id = isset($args['id']) ? $args['id'] : '';
        $default = isset($args['default']) ? $args['default'] : '';
        $value = isset($args['value']) ? $args['value'] : '';
        $field_class = isset($args['field_class']) ? $args['field_class'] : '';
        $field_class = SB_PHP::add_string_with_space_before($field_class, 'sb-color-picker');
        $description = isset($args['description']) ? $args['description'] : '';
        $colors = isset($args['colors']) ? (array)$args['colors'] : array();
        $colors = array_filter($colors);
        $before = isset($args['before']) ? $args['before'] : '<div id="' . esc_attr($id) . '" class="sb-color-options">';
        $after = isset($args['after']) ? $args['after'] : '</div>';
        $name = isset($args['name']) ? $args['name'] : '';
        echo $before;
        if(count($colors) > 0) {
            foreach($colors as $color) {
                $color_name = isset($color['name']) ? $name . '[' . $color['name'] . ']' : '';
                $color_value = isset($color['color']) ? $color['color'] : '';
                $color_default = isset($color['default']) ? $color['default'] : '';
                $color_description = isset($color['description']) ? $color['description'] : '';
                $args = array(
                    'before' => '<div class="color-area">',
                    'name' => $color_name,
                    'value' => $color_value,
                    'default' => $color_default,
                    'field_class' => $field_class,
                    'description' => $color_description
                );
                self::color_picker($args);
            }

        } else {
            $atts = array(
                'data-default-color' => $default
            );
            $args['attributes'] = $atts;
            $args['field_class'] = $field_class;
            self::text($args);
        }
        self::the_after($before, $after);
    }

    public static function the_after($before, $after) {
        if(!empty($before)) {
            echo $after;
        }
    }
}