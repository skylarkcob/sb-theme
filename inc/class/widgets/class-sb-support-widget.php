<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<?php
class SB_Support_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct( 'sb_support_widget', __( 'SB Support', SB_DOMAIN ), array(
            'classname'   => 'widget_sb_support',
            'description' => __( SB_PHP::add_dotted(SB_WP::phrase("support_widget_description")), SB_DOMAIN ),
        ));
    }

    public function widget($args, $instance) {
        if($instance) {
            $title  = apply_filters('widget_title', SB_PHP::get_single_line_value($instance['title']));
            $hotline = SB_PHP::get_single_line_value($instance['hotline']);
            $email = SB_PHP::get_single_line_value($instance['email']);
            $list_yahoo = SB_PHP::get_single_line_value($instance['list_yahoo']);
            $skype = SB_PHP::get_single_line_value($instance['skype']);
        } else {
            $title = '';
            $hotline = '';
            $email = '';
            $list_yahoo = '';
            $skype = '';
        }

        echo $args['before_widget'];
        if(!empty($title)) {
            echo $args['before_title'] .'<i class="fa fa-support"></i> <span class="text">'. $title .'</span>'. $args['after_title'];
        }
        ?>
        <div class="widget-content">
            <ul class="sb-support-list list-unstyled">
                <?php if(!empty($hotline)) : ?>
                    <?php printf('<li class="hotline"><i class="fa fa-phone"></i> <span class="call-text">%1$s</span> <span class="call-line">%2$s</span></li>', SB_PHP::add_colon(__(SB_WP::phrase("hotline"))), $hotline); ?>
                <?php endif; ?>
                <?php if(!empty($email) && is_email($email)) : ?>
                    <?php printf('<li class="email"><i class="fa fa-envelope"></i> <span class="email-text">%1$s</span> <span class="email"><a href="mailto:%2$s" target="_top">%3$s</a></span></li>', SB_PHP::add_colon(__(SB_WP::phrase("email"))), $email, $email); ?>
                <?php endif; ?>
                <?php if(!empty($list_yahoo)) : ?>
                    <?php
                    $yahoo = "";
                    $y_accounts = explode(',', $list_yahoo);
                    foreach($y_accounts as $acc) {
                        $acc = trim($acc);
                        $yahoo .= sprintf('<a href="ymsgr:SendIM?%1$s"><img align="middle" border="0" alt="Yahoo Messenger" src="http://opi.yahoo.com/online?u=%2$s&amp;m=g&amp;t=1&amp;l=us"></a> ', $acc, $acc);
                    }
                    $yahoo = trim($yahoo);
                    ?>
                    <?php printf('<li class="yahoo"><i class="fa fa-comments-o"></i> %s</li>', $yahoo); ?>
                <?php endif; ?>
                <?php if(!empty($skype)) : ?>
                    <?php printf('<li class="skype"><i class="fa fa-skype"></i> <a href="skype:%1$s?call"><img width="150" height="60" alt="My status" style="border: none;" src="http://mystatus.skype.com/balloon/%2$s"></a></li>', $skype, $skype); ?>
                <?php endif; ?>
            </ul>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        if($instance) {
            $title = SB_PHP::get_single_line_value($instance['title']);
            $hotline = SB_PHP::get_single_line_value($instance['hotline']);
            $email = SB_PHP::get_single_line_value($instance['email']);
            $list_yahoo = SB_PHP::get_single_line_value($instance['list_yahoo']);
            $skype = SB_PHP::get_single_line_value($instance['skype']);
        } else {
            $title = '';
            $hotline = '';
            $email = '';
            $list_yahoo = '';
            $skype = '';
        }
        ?>
        <div class="sb-support-widget sb-widget">
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( SB_PHP::add_colon(SB_WP::phrase("title")), SB_DOMAIN ); ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>
            <?php
            $args = array(
                "id"            => $this->get_field_id( 'hotline' ),
                "name"          => $this->get_field_name( 'hotline' ),
                "value"         => $hotline,
                "label_text"    => "Hotline",
                "description"   => SB_PHP::add_dotted(SB_WP::phrase("hotline_description"))
            );
            SB_Theme::widget_field_text($args);

            $args = array(
                "id"            => $this->get_field_id( 'email' ),
                "name"          => $this->get_field_name( 'email' ),
                "value"         => $email,
                "label_text"    => "Email",
                "description"   => SB_PHP::add_dotted(SB_WP::phrase("email_description"))
            );
            SB_Theme::widget_field_text($args);

            $args = array(
                "id"            => $this->get_field_id( 'list_yahoo' ),
                "name"          => $this->get_field_name( 'list_yahoo' ),
                "value"         => $list_yahoo,
                "label_text"    => "Yahoo",
                "description"   => SB_PHP::add_dotted(SB_WP::phrase("list_yahoo_widget_description"))
            );
            SB_Theme::widget_field_textarea($args);

            $args = array(
                "id"            => $this->get_field_id( 'skype' ),
                "name"          => $this->get_field_name( 'skype' ),
                "value"         => $skype,
                "label_text"    => "Skype",
                "description"   => SB_PHP::add_dotted(SB_WP::phrase("skype_widget_description"))
            );
            SB_Theme::widget_field_text($args);
            ?>
        </div>
    <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $title = SB_PHP::get_single_line_value($new_instance['title']);
        $instance["title"] = $title;
        $instance["email"] = SB_PHP::get_single_line_value($new_instance['email']);
        $instance["skype"] = SB_PHP::get_single_line_value($new_instance['skype']);
        $instance["hotline"] = SB_PHP::get_single_line_value($new_instance['hotline']);
        $instance["list_yahoo"] = SB_PHP::get_single_line_value($new_instance['list_yahoo']);
        return $instance;
    }
}
?>