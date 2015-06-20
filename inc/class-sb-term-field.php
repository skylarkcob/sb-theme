<?php
class SB_Term_Field {
    public static function url($args = array()) {
        $args['type'] = 'url';
        self::text($args);
    }

    public static function text($args = array()) {
        if(!isset($args['before'])) {
            $args['before'] = '';
        }
        $id = isset($args['id']) ? $args['id'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
        self::before($id, $label);
        $args['label'] = '';
        SB_Field::text($args);
        self::after();
    }

    public static function image_upload($args = array()) {
        $id = isset($args['id']) ? $args['id'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
        self::before($id, $label);
        $args['container_class'] = isset($args['container_class']) ? $args['container_class'] . ' small' : 'small';
        $args['label'] = '';
        SB_Field::media_upload_with_remove_and_preview($args);
        self::after();
    }

    public static function color_picker($args = array()) {
        $id = isset($args['id']) ? $args['id'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
        self::before($id, $label);
        //$args['container_class'] = isset($args['container_class']) ? $args['container_class'] . ' small' : 'small';
        $args['label'] = '';
        SB_Field::color_picker($args);
        self::after();
    }

    public static function is_edit_page() {
        if(isset($GLOBALS['pagenow']) && 'edit-tags.php' == $GLOBALS['pagenow']) {
            $action = isset($_GET['action']) ? $_GET['action'] : '';
            if('edit' == $action) {
                return true;
            }
        }
        return false;
    }

    public static function term_select($args = array()) {
        $id = isset($args['id']) ? $args['id'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
        if(self::is_edit_page()) {
            $args['label'] = '';
            self::before($id, $label);
        }
        $args['container_class'] = isset($args['container_class']) ? $args['container_class'] . ' small' : 'small';
        SB_Field::select_term($args);
        if(self::is_edit_page()) {
            self::after();
        }
    }

    public static function select_term($args = array()) {
        self::term_select($args);
    }

    public static function select($args = array()) {
        $id = isset($args['id']) ? $args['id'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
        if(self::is_edit_page()) {
            $args['label'] = '';
            self::before($id, $label);
        }
        $args['container_class'] = isset($args['container_class']) ? $args['container_class'] . ' small' : 'small';
        SB_Field::select($args);
        if(self::is_edit_page()) {
            self::after();
        }
    }

    private static function before($id, $label) { ?>
        <tr class="form-field">
            <th scope="row"><?php SB_Field::label(array('for' => $id, 'text' => $label)); ?></th>
            <td> <?php
    }

    private static function after($description = '') {
                SB_Field::the_description($description); ?>
            </td>
        </tr> <?php
    }
}