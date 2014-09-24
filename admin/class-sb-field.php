<?php
if(!class_exists("SB_Field")) {
    class SB_Field {

        private static function image_thumbnail($class = "", $src = "") {
            $class .= " media image thumbnail sbtheme";
            $class = trim($class);
            if(!empty($src)) {
                $class .= " uploaded";
            }
            echo '<div class="'.$class.'">';
            if(!empty($src)) {
                echo '<img src="'.$src.'">';
            }
            echo '</div>';
        }

        public static function media_image($id, $name, $value, $description) {
            echo '<div class="sbtheme-media-image">';
            self::image_thumbnail($name, $value);
            self::media_upload($id, $name, $value, $description);
            echo '</div>';
        }

        private static function media_upload($id, $name, $value, $description) {
            $button_title = __("Insert image", "sbteam");
            $value = trim($value);
            ?>
            <div class="sbtheme-upload media">
                <input type="text" id="<?php echo $id; ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo $value; ?>">
                <a title="<?php echo $button_title; ?>" data-editor="sb-content" class="sb-button button sb-insert-media sb-add_media" href="javascript:void(0);"><?php _e("Upload", "sbteam"); ?></a>
            </div>
            <p class="description"><?php echo $description; ?></p>
            <?php
        }

        public static function switch_button($id, $name, $value, $description) {
            $enable = (bool) $value;
            $class = "switch-button";
            $class_on = $class . ' on';
            $class_off = $class . ' off';
            if($enable) {
                $class_on .= " active";
            } else {
                $class_off .= " active";
            }
            ?>
            <fieldset class="sbtheme-switch">
                <div class="switch-options">
                    <label data-switch="on" class="<?php echo $class_on; ?> left"><span><?php _e("On", "sbteam"); ?></span></label>
                    <label data-switch="off" class="<?php echo $class_off; ?> right"><span><?php _e("Off", "sbteam"); ?></span></label>
                    <input type="hidden" value="<?php echo $value; ?>" name="<?php echo esc_attr($name); ?>" id="<?php echo $id; ?>" class="checkbox checkbox-input">
                    <p class="description"><?php echo $description; ?></p>
                </div>
            </fieldset>
            <?php
        }
    }
}