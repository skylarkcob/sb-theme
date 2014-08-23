<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SB_Theme {
	public static function header() {
		include SB_TEMPLATE_PATH . "/template-theme-header.php";
	}
	
	public static function load_template($name) {
		$name = basename( str_replace( 'template-', '', $name ), '.php' );
		include SB_TEMPLATE_PATH . '/template-'.$name.'.php';
	}
	
	public static function comment() {
		self::load_template( 'theme-comment' );
	}
	
	public static function footer() {
		include SB_TEMPLATE_PATH . "/template-theme-footer.php";
	}
	
	public static function logo() {
		include SB_TEMPLATE_PATH . "/template-theme-logo.php";
	}
	
	public static function template($name) {
		$name = str_replace(".php", "", $name);
		$template = SB_TEMPLATE_PATH . "/" . $name . ".php";
		if(file_exists($template)) {
			include $template;
		}
	}

    public static function the_footer_text() {
        printf('<div class="sb-copyright-text">%s</div>', SB_WP::get_footer_text());
    }

    public static function content_before() {
        echo '<div class="sb-content sb-site-content">';
        self::open_center();
        do_action("sb_content_before");
    }

    public static function content_after() {
        do_action("sb_content_after");
        self::close_center();
        echo '</div>';
    }
	
	public static function sidebar($slug = "") {
		if(empty($slug)) {
			if(SB_WP::theme_file_exists("sidebar.php")) {
				get_sidebar();
			}
		} else {
			if(SB_WP::theme_file_exists("sidebar-".$slug.".php")) {
				get_sidebar($slug);
			}
		}
	}
	
	public static function menu_social() {
		self::template('template-theme-social');
	}
	
	public static function menu_login() {
		if(is_user_logged_in()) : ?>
			<?php $user = wp_get_current_user(); ?>
			<ul>
				<li><?php echo SB_PHP::add_colon(SB_WP::phrase('you_are_login_as')); ?> <a href="<?php echo admin_url('profile.php'); ?>"><?php echo $user->user_login; ?></a></li>
				<?php if(current_user_can('publish_posts')) : ?>
				<li><a href="<?php echo admin_url('post-new.php'); ?>"><?php echo SB_WP::phrase('create_post'); ?></a></li>
				<?php endif; ?>
				<li><a href="<?php SB_WP::logout_uri(); ?>"><?php echo SB_WP::phrase('logout'); ?></a></li>
			</ul>
		<?php else : ?>
			<form method="post" action="<?php SB_WP::login_uri(); ?>" id="loginform" name="loginform">
			
			<p class="login-username">
				<label for="user_login"></label>
				<input type="text" size="20" value="" class="input" id="user_login" name="log" placeholder="<?php echo SB_WP::phrase('username'); ?>">
			</p>
			<p class="login-password">
				<label for="user_pass"></label>
				<input type="password" size="20" value="" class="input" id="user_pass" name="pwd" placeholder="<?php echo SB_WP::phrase('password'); ?>">
			</p>
			
			<p class="login-remember"><label><input type="checkbox" value="forever" id="rememberme" name="rememberme" checked="checked"> <?php echo SB_PHP::add_punctuation(SB_WP::phrase('remember_me'), '?'); ?></label></p>
			<p class="login-submit">
				<input type="submit" value="<?php echo SB_WP::phrase('login'); ?>" class="button-primary" id="wp-submit" name="wp-submit">
			</p>
			<p class="signup">
				<a class="button-primary" href="<?php SB_WP::signup_url(); ?>"><?php echo SB_WP::phrase('register'); ?></a>
			</p>
		</form>
		<?php endif;
	}

    public static function the_headline() {
        $headline = SB_WP::get_headline_option();
        if(!empty($headline)) {
            printf('<h2>%s</h2>', $headline);
        }
    }

	public static function title() {
		if(is_home()) {
			echo get_bloginfo('name') . ' - ' . get_bloginfo('description');
		} elseif(is_post_type_archive('product')) {
			echo 'Danh sách sản phẩm';
		} elseif(is_post_type_archive('forum')) {
			echo SB_WP::phrase('forums').' '.get_bloginfo('name');
		} elseif(is_singular('forum')) {
			echo get_the_title().' - '.get_bloginfo('name');
		} elseif(is_singular('topic') || is_single() || is_page()) {
			echo get_the_title();
		} elseif(SB_WP::is_user_page()) {
			echo SB_WP::phrase('account_information');
		} elseif(is_tax()) {
			single_term_title();
		} else {
			wp_title('');
		}
	}
	
	public static function logo_ads() {
		?>
		<div class="logo-ads sb-logo-ads">
			<div class="site-branding">
				<?php SB_Theme::logo(); ?>
			</div>
			<?php SB_Theme::leaderboard_ads(); ?>
		</div>
		<?php
	}
	
	public static function float_ads_left() {
		?>
		<div id="sbFloatAdsRight" class="sb-float-ads sb-float-ads-right sb-ads">
			<div class="float-ads-inner">
				<?php
				if(is_active_sidebar('float-ads-right')) {
					dynamic_sidebar('float-ads-right');
				}
				?>
			</div>
		</div>
		<?php
	}
	
	public static function not_found() {
		self::template("template-theme-404");
	}
	
	public static function site_tree() {
		self::template('template-theme-site-tree');
	}
	
	public static function float_ads_right() {
		?>
		<div id="sbFloatAdsLeft" class="sb-float-ads sb-float-ads-left sb-ads">
			<div class="float-ads-inner">
				<?php
				if(is_active_sidebar('float-ads-left')) {
					dynamic_sidebar('float-ads-left');
				}
				?>
			</div>
		</div>
		<?php
	}
	
	public static function leaderboard_ads() {
		?>
		<div id="sbLeaderboardAds" class="sb-ads leaderboard">
			<div class="sb-ads-inner">
				<?php
				if(is_active_sidebar('leaderboard-ads')) {
					dynamic_sidebar('leaderboard-ads');
				}
				?>
			</div>
		</div>
		<?php
	}
	
	public static function float_ads($location) {
		switch($location) {
			case 'left':
				self::float_ads_left();
				break;
			case 'right':
				self::float_ads_right();
				break;
		}
	}

    public static function widget_area($sidebar_id) {
        if(is_active_sidebar($sidebar_id)) {
            $class = "sb-widget-area";
            $class .= ' '.$sidebar_id;
            echo '<div class="'.$class.'">';
            dynamic_sidebar($sidebar_id);
            echo '</div>';
        }
    }

    public static function widget_field_select($args = array()) {
        $paragraph_class = "";
        $id = "";
        $name = "";
        $field_class = "";
        $label_text = "";
        $list_options = array();
        $value = "";
        $description = "";

        $defaults = array(
            "id"                => "",
            "name"              => "",
            "label_text"        => "",
            "value"             => "",
            "paragraph_class"   => "",
            "field_class"       => "",
            "list_options"      => array()
        );

        $args = wp_parse_args($args, $defaults);

        extract($args, EXTR_OVERWRITE);
        ?>
        <p class="<?php echo $paragraph_class; ?>">
            <label for="<?php echo esc_attr( $id ); ?>"><?php _e( SB_PHP::add_colon($label_text), SB_DOMAIN ); ?></label>
            <select id="<?php echo esc_attr( $id ); ?>" class="<?php echo $field_class; ?>" name="<?php echo esc_attr( $name ); ?>">
                <?php foreach ( $list_options as $key => $option ) : ?>
                    <option value="<?php echo esc_attr( $key ); ?>"<?php selected( $value, $key ); ?>><?php echo $option; ?></option>
                <?php endforeach; ?>
            </select>
            <?php if(!empty($description)) : ?>
                <em><?php _e($description, SB_DOMAIN); ?></em>
            <?php endif; ?>
        </p>
        <?php
    }

    public static function widget_field_textarea($args = array()) {
        $paragraph_class = "";
        $input_class = "";
        $id = "";
        $name = "";
        $value = "";
        $description = "";
        $label_text = "";
        $textarea_rows = 3;

        $defaults = array(
            "input_class"   => "widefat",
            "textarea_rows" => 3
        );

        $args = wp_parse_args($args, $defaults);

        extract($args, EXTR_OVERWRITE);
        ?>
        <p class="<?php echo $paragraph_class; ?>">
            <label for="<?php echo esc_attr( $id ); ?>"><?php _e( SB_PHP::add_colon($label_text), SB_DOMAIN ); ?></label>
            <textarea id="<?php echo esc_attr( $id ); ?>" class="<?php echo $input_class; ?>" name="<?php echo esc_attr( $name ); ?>" rows="<?php echo $textarea_rows; ?>"><?php echo esc_attr( $value ); ?></textarea>
            <?php if(!empty($description)) : ?>
                <em><?php _e($description, SB_DOMAIN); ?></em>
            <?php endif; ?>
        </p>
    <?php
    }

    public static function widget_field_text($args = array()) {
        $paragraph_class = "";
        $input_class = "";
        $id = "";
        $name = "";
        $value = "";
        $description = "";
        $label_text = "";

        $defaults = array(
            "input_class"   => "widefat"
        );

        $args = wp_parse_args($args, $defaults);

        extract($args, EXTR_OVERWRITE);
        ?>
        <p class="<?php echo $paragraph_class; ?>">
            <label for="<?php echo esc_attr( $id ); ?>"><?php _e( SB_PHP::add_colon($label_text), SB_DOMAIN ); ?></label>
            <input id="<?php echo esc_attr( $id ); ?>" class="<?php echo $input_class; ?>" name="<?php echo esc_attr( $name ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>">
            <?php if(!empty($description)) : ?>
                <em><?php _e($description, SB_DOMAIN); ?></em>
            <?php endif; ?>
        </p>
    <?php
    }

	public static function widget_field_checkbox($args = array()) {
        $input_class = '';
        $paragraph_class = '';
        $id = '';
        $description = '';
        $name = '';
        $value = '';
		$defaults = array(
			'id'				=> '',
			'name'				=> '',
			'value'				=> '',
			'description'		=> '',
			'paragraph_id'		=> '',
			'display'			=> true,
			'input_class'		=> '',
			'paragraph_class'	=> ''
		);
		$args = wp_parse_args($args, $defaults);
		extract($args, EXTR_OVERWRITE);
		$input_class = trim($input_class." sb-checkbox");
		?>
		<p class="<?php echo $paragraph_class; ?>">
			<input id="<?php echo esc_attr( $id ); ?>" class="<?php echo $input_class; ?>" name="<?php echo esc_attr( $name ); ?>" type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( $value, 1, true ); ?>>
			<label for="<?php echo esc_attr( $id ); ?>"><?php _e( $description, SB_DOMAIN ); ?></label>
            <?php if(!empty($description)) : ?>
                <em><?php _e($description, SB_DOMAIN); ?></em>
            <?php endif; ?>
		</p>
		<?php
	}
	
	public static function widget_field_number($args = array()) {
        $input_class = '';
        $paragraph_class = '';
        $display = '';
        $id = '';
        $description = '';
        $name = '';
        $value = '';
		$defaults = array(
			'id'				=> '',
			'name'				=> '',
			'value'				=> '',
			'description'		=> '',
			'paragraph_id'		=> '',
			'display'			=> true,
			'input_class'		=> '',
			'paragraph_class'	=> ''
		);
		$args = wp_parse_args($args, $defaults);
		extract($args, EXTR_OVERWRITE);
		$input_class = trim($input_class." sb-number");
		?>
		<p class="<?php echo $paragraph_class; ?>"<?php if(!$display) echo ' style="display:none"'; ?>>
			<label for="<?php echo esc_attr( $id ); ?>"><?php _e( $description, SB_DOMAIN ); ?></label>
			<input id="<?php echo esc_attr( $id ); ?>" class="<?php echo $input_class; ?>" name="<?php echo esc_attr( $name ); ?>" type="number" value="<?php echo esc_attr( $value ); ?>">
            <?php if(!empty($description)) : ?>
                <em><?php _e($description, SB_DOMAIN); ?></em>
            <?php endif; ?>
		</p>
		<?php
	}
	
	public static function widget_field_image_size($args = array()) {
        $input_class = '';
        $paragraph_class = '';
        $display = '';
        $description = '';
        $id = '';
        $id_width = '';
        $id_height = '';
        $name_width = '';
        $name_height = '';
        $value = array();
		$defaults = array(
			'id_width'			=> '',
			'name_width'		=> '',
			'id_height'			=> '',
			'name_height'		=> '',
			'value'				=> array(),
			'description'		=> '',
			'paragraph_id'		=> '',
			'display'			=> true,
			'input_class'		=> '',
			'paragraph_class'	=> ''
		);
		$args = wp_parse_args($args, $defaults);
		extract($args, EXTR_OVERWRITE);
		$input_class = trim($input_class." sb-number image-size");
		?>
		<p class="<?php echo $paragraph_class; ?>"<?php if(!$display) echo ' style="display:none"'; ?>>
			<label for="<?php echo esc_attr( $id ); ?>"><?php _e( $description, SB_DOMAIN ); ?></label>
            <label for="<?php echo esc_attr( $id_width ); ?>"></label>
			<input id="<?php echo esc_attr( $id_width ); ?>" class="<?php echo $input_class; ?>" name="<?php echo esc_attr( $name_width ); ?>" type="number" value="<?php echo esc_attr( $value[0] ); ?>">
			<span>x</span>
            <label for="<?php echo esc_attr( $id_height ); ?>"></label>
			<input id="<?php echo esc_attr( $id_height ); ?>" class="<?php echo $input_class; ?>" name="<?php echo esc_attr( $name_height ); ?>" type="number" value="<?php echo esc_attr( $value[1] ); ?>">
            <?php if(!empty($description)) : ?>
                <em><?php _e($description, SB_DOMAIN); ?></em>
            <?php endif; ?>
		</p>
		<?php
	}

    public static function the_poetry() {
        ?>
        <div class="sb-poetry text-center">
            <h6 class="poetry">Code is Poetry</h6>
        </div>
        <?php
    }
	
	public static function addthis_share_button() {
		echo '<div class="sb-addthis sb-share"><div class="addthis_native_toolbox"></div></div>';
	}

    public static function product_detail() {
        $sb_product = new SB_Product();
        ?>
        <div class="sb-product-details">
            <h2 class="product-name post-title"><?php the_title(); ?></h2>
            <dl>
                <?php
                $price = $sb_product->get_price();
                $stock = $sb_product->get_stock_status();
                $weight = $sb_product->get_weight();
                $brand = $sb_product->get_brand();
                $category = $sb_product->get_category();
                $tag = $sb_product->get_tag();
                ?>
                <dt class="price"><?php _e(SB_PHP::add_colon(SB_WP::phrase("price")), SB_DOMAIN); ?></dt>
                <dd class="price"><?php echo SB_WP::format_price(array("price" => $price)); ?></dd>

                <dt><?php _e(SB_PHP::add_colon(SB_WP::phrase("status")), SB_DOMAIN); ?></dt>
                <dd class="available"><?php echo $stock; ?></dd>

                <dt><?php _e(SB_PHP::add_colon(SB_WP::phrase("weight")), SB_DOMAIN); ?></dt>
                <dd><?php echo $weight; ?></dd>

                <?php if(!empty($brand)) : ?>
                <dt class="size"><?php _e(SB_PHP::add_colon(SB_WP::phrase("brand")), SB_DOMAIN); ?></dt>
                <dd class="size">
                    <a href="javascript:;"><?php echo $brand; ?></a>
                </dd>
                <?php endif; ?>

                <?php if(!empty($category)) : ?>
                <dt><?php _e(SB_PHP::add_colon(SB_WP::phrase("category")), SB_DOMAIN); ?></dt>
                <dd><?php echo $category; ?></dd>
                <?php endif; ?>

                <?php if(!empty($tag)) : ?>
                <dt>Tags:</dt>
                <dd><?php echo $tag; ?></dd>
                <?php endif; ?>

            </dl>
            <div class="product-review">
                <?php self::product_rate_result(); ?>
                <a class="new-review" href="<?php echo '#comment'; ?>"><?php _e(SB_WP::phrase("write_product_review"), SB_DOMAIN); ?></a>
                <?php SB_Theme::addthis_share_button(); ?>
            </div>
        </div>
        <?php
    }

    public static function create_post_form($content, $editor_id, $settings = array()) {
        if(!is_user_logged_in()) {
            wp_redirect(SB_WP::get_login_uri());
            exit;
        }
        $user = new SB_User();
        $last_post_minute = $user->get_last_post_minute_diff();
        if($last_post_minute < SB_WP::get_time_between_post()) {
            printf('<div class="messages">%1$s</div>', SB_WP::get_error_line(__(sprintf(SB_PHP::add_dotted(SB_WP::phrase("you_must_wait_x_minute_before_publish_next_post")), SB_PHP::date_minus_minute(SB_WP::current_time_mysql(), $user->get_next_post_time())), SB_DOMAIN)));
            return;
        }
        if ( isset( $_POST['submitted'] ) && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {
            $msg_errors = array();
            if ( isset($_POST["post_title"]) && empty($_POST["post_title"]) ) {
                array_push($msg_errors, SB_PHP::add_dotted(SB_WP::phrase("please_enter_post_title")));
                $has_error = true;
            } else {
                $post_title = wp_strip_all_tags($_POST["post_title"]);
            }

            if ( isset($_POST["content"]) && empty($_POST["content"]) ) {
                array_push($msg_errors, SB_PHP::add_dotted(SB_WP::phrase("please_enter_post_content")));
                $has_error = true;
            } else {
                $post_content = stripcslashes($_POST["content"]);
                $post_character = SB_PHP::count_character($post_content);
                $character_limit = SB_WP::get_post_character_limit();
                if($post_character < $character_limit) {
                    array_push($msg_errors, SB_PHP::add_dotted(sprintf(SB_WP::phrase("your_post_content_must_be_at_least_x_character"), $character_limit)));
                    $has_error = true;
                }
                $post_image = SB_PHP::count_image($post_content);
                $image_limit = SB_WP::get_post_image_limit();
                if($post_image > $image_limit) {
                    array_push($msg_errors, SB_PHP::add_dotted(sprintf(SB_WP::phrase("you_can_only_use_x_image_in_post"), $image_limit)));
                    $has_error = true;
                }
            }

            $post_category = array($_POST["post_category"]);
            $post_tags = wp_strip_all_tags($_POST["post_tags"]);

            $post_information = array(
                'post_title' => $post_title,
                'post_content' => $post_content,
                'post_type' => 'post',
                'post_status' => 'pending',
                'post_author'   => $user->ID,
                'post_category' => $post_category,
                'tags_input'    => $post_tags
            );

            if($has_error) {
                echo '<div class="messages">';
                foreach($msg_errors as $error) {
                    SB_WP::error_line($error);
                }
                do_action("sb_new_post_form_message");
                echo '</div>';
            } else {
                $post_id = wp_insert_post( $post_information );
                if($post_id) {
                    $post_saved = true;
                }
            }
        }
        if(empty($editor_id)) {
            $editor_id = "content";
        }
        ?>
        <?php if(!$post_saved) : ?>
        <div class="sb-create-post">
            <?php do_action("sb_new_post_form_before"); ?>
            <form class="new-post" method="post">
                <p>
                    <label for="title"><?php _e(apply_filters("sb_new_post_form_title", SB_PHP::add_colon(SB_WP::phrase("title"))), SB_DOMAIN) ?></label>
                    <input type="text" name="post_title" id="title" class="required" value="<?php echo $post_title; ?>" autocomplete="off">
                </p>
                <div class="post-content-area">
                    <?php SB_WP::the_editor($content, $editor_id, $settings); ?>
                    <?php
                    $content_description = apply_filters("sb_new_post_form_content_description", "");
                    if(!empty($content_description)) {
                        printf('<em>%1$s</em>', $content_description);
                    }
                    ?>
                </div>

                <p>
                    <?php $cats = SB_WP::get_all_category(); ?>
                    <?php
                    if(is_array($post_category)) {
                        $cat_id = array_shift($post_category);
                    }

                    ?>
                    <label for="category"><?php _e(apply_filters("sb_new_post_form_category", SB_PHP::add_colon(SB_WP::phrase("category"))), SB_DOMAIN) ?></label>
                    <select id="category" name="post_category">
                        <?php foreach($cats as $cat) : ?>
                            <option value="<?php echo $cat->term_id; ?>"<?php selected($cat_id, $cat->term_id); ?>><?php echo $cat->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <label for="tags"><?php _e(apply_filters("sb_new_post_form_tags", SB_PHP::add_colon(SB_WP::phrase("tags"))), SB_DOMAIN) ?></label>
                    <input type="text" name="post_tags" id="tags" class="required" value="<?php echo $post_tags; ?>" autocomplete="off">
                    <?php
                    $tags_description = apply_filters("sb_new_post_form_tags_description", "");
                    if(!empty($tags_description)) {
                        printf('<em>%1$s</em>', $tags_description);
                    }
                    ?>
                </p>
                <p>
                    <?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
                    <input type="hidden" name="submitted" id="submitted" value="true">
                    <button type="submit" class="submit-new-post"><?php _e(apply_filters("sb_new_post_form_submit_text", SB_WP::phrase("submit")), SB_DOMAIN) ?></button>
                </p>
            </form>
            <?php do_action("sb_new_post_form_after"); ?>
        </div>
        <?php else : ?>
            <div class="messages">
                <?php SB_WP::message_line(SB_PHP::add_dotted(SB_WP::phrase("thank_you_your_post_saved"))); ?>
            </div>
            <div class="after-save-post">
                <p><?php echo SB_PHP::add_dotted(SB_WP::phrase("after_saved_post_text")); ?></p>
                <?php do_action("sb_after_saved_post_text"); ?>
            </div>
        <?php endif; ?>
        <?php
    }

    public static function product_rate_result() {
        global $product;

        if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' )
            return;

        $count   = $product->get_rating_count();
        $average = $product->get_average_rating();

        if ( $count > 0 ) : ?>

            <div class="woocommerce-product-rating sb-product-rating">
                <div class="star-rating" title="<?php printf( __( SB_WP::phrase("rated_x_out_of_5"), SB_DOMAIN ), $average ); ?>">
			<span style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%">
				<strong class="rating"><?php echo esc_html( $average ); ?></strong> <?php _e( SB_PHP::lowercase(SB_WP::phrase("out_of_5")), SB_DOMAIN ); ?>
			</span>
                </div>
                <a href="<?php echo '#reviews'; ?>" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s '.SB_WP::phrase("customer_review"), '%s '.SB_WP::phrase("customer_reviews"), $count, SB_DOMAIN ), '<span itemprop="ratingCount" class="count">' . $count . '</span>' ); ?>)</a>
            </div>

        <?php endif;
    }

	public static function option() {
		return SB_WP::option();
	}
	
	public static function options() {
		return self::option();
	}

    public static function breadcrumb() {
		if(function_exists('is_bbpress') && is_bbpress()) {
		
		} elseif ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb('<div class="breadcrumb">', '</div>');
        }
    }
	
	public static function comment_navigation( $type ) {
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	
		<nav id="comment-nav-<?php echo $type; ?>" class="navigation comment-navigation">
			<h4 class="screen-reader-text"><?php _e( SB_WP::phrase('comment_navigation'), SB_DOMAIN ); ?></h4>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; '.SB_WP::phrase('older_comments'), SB_DOMAIN ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( SB_WP::phrase('newer_comments').' &rarr;', SB_DOMAIN ) ); ?></div>
		</nav><!-- #comment-nav-above -->
		
		<?php endif; // Check for comment navigation.
	}

    public static function open_center() {
        echo '<div class="wrap container-fluid"><div class="row">';
    }

    public static function close_center() {
        echo '</div></div>';
    }
	
	public static function tivi_box($src) {
		if(empty($src)) :
		?>
			<div class="sb-tivi no-source"><p>Kênh bạn đang xem hiện chưa cập nhật.</p></div>
		<?php else : ?>
		<div class="sb-tivi-outer text-center"><iframe id="sbTV" src="<?php echo $src; ?>" class="sb-tivi" data-number="1"></iframe></div>
		<?php
		endif;
	}
	
	public static function phrase($phrase) {
		return SB_WP::phrase($phrase);
	}
	
	public static function copyright() {
		include SB_TEMPLATE_PATH . "/template-theme-copyright.php";
	}
	
	public static function copyright_text() {
		echo '&copy; '.date('Y').' '.get_bloginfo('name').'. All Rights Reserved.';
	}
	
	public static function post_comment_link() {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
		?>
		<span class="comments-link post-comment"><i class="fa fa-comments"></i> <?php comments_popup_link( __( '<span class="count">0</span> <span class="text">'.mb_strtolower(SB_WP::phrase('comment')).'</span>', SB_DOMAIN ), __( '<span class="count">1</span> <span class="text">'.mb_strtolower(SB_WP::phrase('comment'))."</span>", SB_DOMAIN ), __( '<span class="count">%</span> <span class="text">'.strtolower(SB_WP::phrase('comments'))."</span>", SB_DOMAIN ) ); ?></span>
		<?php
		endif;
	}
	
	public static function post_date() {
		printf( '<span class="sb-post-date post-date"><i class="fa fa-clock-o"></i> <a href="%1$s" rel="bookmark"><time class="sb-post-time date updated" datetime="%2$s">%3$s</time></a></span>',
			esc_url( get_permalink() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);
	}

    public static function related_post() {
        echo '<div class="sb-related-post">';
        SB_WP::related_post();
        echo '</div>';
    }
	
	public static function post_thumbnail($args = array()) {
        $size_name = 'thumbnail';
        $size = array();
		$defaults = array(
			'size'		=> array(),
			'size_name'	=> 'thumbnail'
		);
		$args = wp_parse_args($args, $defaults);
		extract($args, EXTR_OVERWRITE);
		$real_size = $size_name;
		if(count($size) == 2) {
			$real_size = $size;
		}
		?>
		<div class="post-thumbnail">
			<a href="<?php echo get_permalink(get_the_ID()); ?>"><?php echo SB_WP::get_post_thumbnail($args); ?></a>
		</div>
		<?php
	}
	
	public static function post_author() {
		printf( '<span class="post-author"><i class="fa fa-user"></i> <span class="author vcard"><a class="url fn n" href="%1$s" rel="author">%2$s</a></span></span>',
			esc_url( SB_WP::get_author_post_url() ),
			get_the_author_meta('user_nicename')
		);
	}
	
    public static function menu($args = null) {
        $menu_class = "";
        $theme_location = "";
        $defaults = array(
            'theme_location'	=> '',
            'menu_class'		=> ''
        );
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_OVERWRITE);
        $menu_class .= ' sf-menu '.$theme_location;
        $menu_class = trim($menu_class);
        echo '<div class="sb-navigation '.$theme_location.'">';
        wp_nav_menu(array('theme_location' => $theme_location, 'menu_class' => $menu_class));
        echo '</div>';
    }

    public static function head_title_bar($title) {
        ?>
        <div class="page-head-bar">
            <div class="page-head-inner">
                <span class="page-head-title"><?php echo $title; ?></span>
            </div>
        </div>
    <?php
    }
	
	public static function float_ads_sidebar() {
		$options = SB_WP::option();
		if(isset($options['enable_float_ads']) && (bool)$options['enable_float_ads']) {
			$sidebar_file = locate_template('sidebar-float-ads-left.php', false, false);
			if(!empty($sidebar_file)) {
				get_sidebar('float-ads-left');
			} else {
				self::float_ads('left');
			}
			$sidebar_file = locate_template('sidebar-float-ads-right.php', false, false);
			if(!empty($sidebar_file)) {
				get_sidebar('float-ads-right');
			} else {
				self::float_ads('right');
			}
		}
	}

    public static function wishlist_button() {
        SB_WP::wishlist_button();
    }

    public static function compare_button() {
        SB_WP::compare_button();
    }
	
	public static function tivi_server_list($list_server) {
		if(count($list_server) > 1) : $count = 1; ?>

			<ul class="server-list list-inline text-center">
			<?php foreach($list_server as $server) : ?>
				<li><a class="btn btn-info" href="#" data-server="<?php echo $server; ?>" data-number="<?php echo $count; ?>">Server <?php echo $count; ?></a></li>
			<?php $count++; endforeach; ?>
			</ul>

		<?php endif;
	}
	
    public static function spinner_loading_full() {
        ?>
        <div class="spinner-loading-full spinner">
            <div id="circularG">
                <div id="circularG_1" class="circularG"></div>
                <div id="circularG_2" class="circularG"></div>
                <div id="circularG_3" class="circularG"></div>
                <div id="circularG_4" class="circularG"></div>
                <div id="circularG_5" class="circularG"></div>
                <div id="circularG_6" class="circularG"></div>
                <div id="circularG_7" class="circularG"></div>
                <div id="circularG_8" class="circularG"></div>
            </div>
        </div>
    <?php
    }

    public static function login_link() {
        ?>
        <a href="<?php SB_WP::login_uri(); ?>"><i class="fa fa-lock"></i> <?php _e(SB_WP::phrase("login"), SB_DOMAIN); ?></a>
        <?php
    }

    public static function welcome_account($args = array()) {
        if(is_user_logged_in()) {
            $user = SB_WP::get_current_user();
            $account_url = "";
            extract($args, EXTR_OVERWRITE);
            if(empty($account_url)) {
                if(SB_WP::is_woocommerce_installed()) {
                    $account_url = SB_WP::get_account_url();
                } else {
                    //$account_url = SB_W
                }
            }
            $hello_user = sprintf(__(SB_WP::phrase("hello_user"), SB_DOMAIN), $user->user_nicename);
            printf('<a href="%1$s" title=""><i class="fa fa-lock"></i> %2$s</a>', $account_url, $hello_user);
        }
    }

    public static function welcome_account_link($args = array()) {
        self::welcome_account($args);
    }



    public static function logout_link() {
        if(SB_WP::is_logged_in()) {
            printf('<a href="%1$s" title=""><i class="fa fa-key"></i> %2$s</a>', SB_WP::get_logout_url(), __(SB_WP::phrase("logout"), SB_DOMAIN));
        }
    }


    public static function register_link() {
        echo '<a href="'.SB_WP::register_uri().'"><i class="fa fa-key"></i> '.SB_WP::phrase("register").'</a>';
    }

    public static function search_form($args = array()) {
        $form_class = "";
        $label_text = "";
        $placeholder_text = "";
        $submit_text = "";
        $post_type = "post";
        $defaults = array(
            'label_text'		=> __(SB_WP::phrase('search'), SB_DOMAIN),
            'placeholder_text'	=> __(SB_WP::phrase('enter_keyword').'...', SB_DOMAIN),
            'submit_text'		=> __(SB_WP::phrase('search'), SB_DOMAIN),
            'form_class'		=> ''
        );
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_OVERWRITE);
        $form_class = trim($form_class.' search-form');
        ?>
        <div class="sb-search">
            <form method="get" class="<?php echo $form_class; ?>" action="<?php echo esc_url(home_url('/')); ?>">
                <label>
                    <span class="screen-reader-text"><?php echo $label_text; ?></span>
                    <input type="search" class="search-field" placeholder="<?php echo $placeholder_text; ?>" value="" name="s">
                </label>
                <input type="hidden" name="post_type" value="<?php echo $post_type; ?>">
                <input type="submit" class="search-submit" value="<?php echo $submit_text; ?>">
            </form>
        </div>
    <?php
    }

    public static function hotline_image() {
        $options = SB_WP::option();
        if(isset($options["hotline_image"]) && !empty($options["hotline_image"])) {
            printf('<div class="sb-hotline"><img src="%s" alt="Hotline"></div>', $options["hotline_image"]);
        }
    }

    public static function feed_form($args = array()) {
        self::feedburner_form($args);
    }

    public static function subscribe_box($args = array()) {
        self::feedburner_form($args);
    }

    public static function feedburner_form($args = array()) {
        $form_class = "";
        $label_text = "";
        $placeholder_text = "";
        $submit_text = "";
        $name = "";
        $defaults = array(
            'name'				=> '',
            'label_text'		=> 'Nhập địa chỉ email:',
            'placeholder_text'	=> 'Nhập email...',
            'submit_text'		=> 'Gửi',
            'form_class'		=> ''
        );
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_OVERWRITE);
        $form_class = trim($form_class.' feed-form');
        do_action('sbwp_before_feed_form');
        ?>
        <form class="<?php echo $form_class; ?>" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $name; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
            <?php do_action('sbwp_inside_feed_form'); ?>
            <p class="email-line">
                <label for="email"><?php echo $label_text; ?></label>
                <input class="feed-email" placeholder="<?php echo $placeholder_text; ?>" type="email" name="email"/>
            </p>
            <input type="hidden" value="<?php echo $name; ?>" name="uri"/>
            <input type="hidden" name="loc" value="en_US"/>
            <p class="submit-line">
                <input type="submit" value="<?php echo $submit_text; ?>" />
            </p>
        </form>
        <?php
        do_action('sbwp_after_feed_form');
    }
	
	public static function created_by() {
		echo SB_WP::phrase('theme_created_by').' <a href="http://hocwp.net">SB Team</a>.';
	}
	
	public static function post_category() {
		$categories_list = get_the_category_list( __( ', ', SB_DOMAIN ) );
		if (!empty( $categories_list )) :
		?>
		<span class="post-cats"><i class="fa fa-briefcase"></i> <?php printf( __( SB_PHP::add_colon(SB_WP::phrase('category')).' %1$s', SB_DOMAIN ), $categories_list ); ?></span>
		<?php endif;
	}
	
	public static function author_box() {
		$author = new SB_Author();
		$description = $author->get_description();
		if(!empty($description)) :
	?>
		<div class="author-wrap">
			<div class="author-gravatar">
				<?php echo $author->get_avatar( '100' ); ?>
			</div>
			<div class="author-info">
				<div class="vcard author author-title">
					<span class="fn">
						<a target="_blank" class="ext-link" href="<?php echo $author->get_url(); ?>" title="Ghé thăm website của <?php echo $author->get_username(); ?>" rel="author external nofollow"><?php echo $author->get_display_name(); ?></a>
					</span>
				</div>
				<div class="author-description"><?php echo $description; ?></div>
				<ul>
					<li class="first">
						<a href="<?php echo $author->get_post_url(); ?>">Bài được viết bởi <?php echo $author->get_username(); ?> <span class="meta-nav">→</span></a>
					</li>
					<?php $website_url = $author->get_url(); ?>
					<?php if(!empty($website_url)) : ?>
					<li class="website">
						<a target="_blank" class="ext-link" rel="external nofollow" href="<?php echo $website_url; ?>" title="Ghé thăm trang chủ của <?php echo $author->get_username(); ?>">Blog</a>
					</li>
					<?php endif; ?>
					<?php $facebook_url = $author->get_facebook_url(); ?>
					<?php if(!empty($facebook_url)) : ?>
					<li class="facebook">
						<a target="_blank" class="ext-link" href="<?php echo $facebook_url; ?>" title="Theo dõi <?php echo $author->get_username(); ?> trên Facebook" rel="external nofollow">Facebook</a>
					</li>
					<?php endif; ?>
					<?php $gplus_url = $author->get_gplus_url(); ?>
					<?php if(!empty($gplus_url)) : ?>
					<li class="googleplus">
						<a target="_blank" class="ext-link" href="<?php echo $gplus_url; ?>" title="Theo dõi <?php echo $author->get_username(); ?> trên Goolge Plus" rel="external nofollow">Google Plus</a>
					</li>
					<?php endif; ?>
					<?php $twitter_url = $author->get_twitter_url(); ?>
					<?php if(!empty($twitter_url)) : ?>
					<li class="twitter"><a rel="external" title="Theo dõi <?php echo $author->get_username(); ?> trên Twitter" href="<?php echo $twitter_url; ?>">Twitter</a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	<?php
		endif;
	}
	
	public static function comments_template() {
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}
	
	public static function comment_form_args() {
		global $user_identity;
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$args = array(
			'fields'				=> apply_filters( 'comment_form_default_fields', array(
					'author' => '<p class="comment-form-author">' . '<label for="author">' . __( SB_WP::phrase('your_name'), SB_DOMAIN ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="author" placeholder="'.SB_WP::phrase('your_name').' *" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . ' class="sb-author-info"></p>',
					'email' => '<p class="comment-form-email">' . '<label for="email">' . __( SB_WP::phrase('your_email'), SB_DOMAIN ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="email" placeholder="'.SB_WP::phrase('your_email').' *" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"' . $aria_req . ' class="sb-author-info"></p>',
					'url' => '<p class="comment-form-url">' . '<label for="url">' . __( SB_WP::phrase('your_website'), SB_DOMAIN ) . '</label>' . '<input id="url" name="url" placeholder="'.SB_WP::phrase('your_website').'" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" class="sb-author-info"></p>'
				)
			),
			'comment_field'			=> '<p class="comment-form-comment">' . '<label for="comment">' . __( SB_WP::phrase('comment_body'), SB_DOMAIN ) . '</label>' . '<textarea id="comment" name="comment" placeholder="" aria-required="true" class="sb-comment-msg"></textarea></p>',
			'comment_notes_before'	=> '<p class="comment-notes before">' . __( SB_PHP::add_dotted(SB_WP::phrase('your_email_not_published')), SB_DOMAIN ) . __( $req ? ' '.sprintf(SB_PHP::add_dotted(SB_WP::phrase('require_field_mark')), '(*)') : '' ) . '</p>',
			'comment_notes_after'	=> '<p class="form-allowed-tags comment-notes after">' . sprintf( __( sprintf(SB_PHP::add_colon(SB_WP::phrase('allow_html_tags')), '<abbr title="'.__(SB_WP::phrase('html_intro')).'">HTML</abbr>').' %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
			'must_log_in'			=> '<p class="must-log-in">' . sprintf(__( SB_WP::phrase("you_must").' <a href="%s">'.SB_PHP::lowercase(SB_WP::phrase("login")).'</a> '.SB_PHP::lowercase(SB_PHP::add_dotted(SB_WP::phrase("before_leave_a_comment"))) ), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
			'logged_in_as'			=> '<p class="logged-in-as">' . sprintf(__( __(SB_WP::phrase('you_are_login_as'), SB_DOMAIN).' <a href="%1$s">%2$s</a>. <a href="%3$s" title="'.__(SB_WP::phrase("logout"), SB_DOMAIN).'">'.__(SB_WP::phrase("logout"), SB_DOMAIN).'?</a>' ), admin_url( 'profile.php' ), esc_attr( $user_identity ), wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )) . '</p>',
			'title_reply'			=> '<a id="leaveyourcomment"></a><span class="comment-title">'.__(SB_WP::phrase('leave_reply'), SB_DOMAIN).'</span>',
			'label_submit'			=> __(SB_WP::phrase("post_comment"), SB_DOMAIN),
			'title_reply_to'		=>  __( SB_WP::phrase("reply_comment_to_x") ),
			'cancel_reply_link'		=> __(SB_WP::phrase('cancel_reply'), SB_DOMAIN)
		);
		return $args;
	}
	
	public static function get_image($name) {
		return SB_WP::get_image_url($name);
	}
	
	public static function nothing_found() {
		self::template("template-theme-no-post");
	}
	
	public static function paypal_donate($value = 5) {
		switch($value) {
			case 5:
				echo '<a target="_blank" href="'.SB_PAYPAL_DONATE_5_URL.'"><img src="'.SB_PAYPAL_DONATE_BUTTON_CARD_URL.'" ></a>';
				break;
		}
	}

    public static function mobile_sidebar() {
        ?>
        <div class="sb-mobile-sidebar" data-collapse="collapsed">
            <div class="mobile-sidebar-inner">
                <div class="button-control">
                    <span><i class="fa fa-bars"></i></span>
                </div>
                <div class="mobile-sidebar-content">
                    <div class="search">
                        <?php self::search_form(array("submit_text" => "&#xf002;")); ?>
                    </div>
                    <?php self::menu(array("location" => "primary")); ?>
                </div>
            </div>
        </div>
        <?php
    }

    public static function carousel_navigation($args = array()) {
        $id = "";
        $count = 0;
        $items = array();
        $control_type = "button";
        extract($args, EXTR_OVERWRITE);
        if(!is_numeric($count) || 0 == $count || empty($id)) {
            return;
        }
        ?>
        <ol class="carousel-indicators sb-carousel-navigation">
            <?php for($i = 0; $i < $count; $i++) : $class = "indicator"; if(0 == $i) $class .= ' active'; ?>
                <li data-target="#<?php echo $id; ?>" data-slide-to="<?php echo $i; ?>" class="<?php echo $class; ?>">
                    <?php
                    if(count($items) > $i) {
                        if("text" == $control_type) {
                            $link = $items[$i]["link"];
                            $text = $items[$i]["text"];
                            printf('<a href="%1$s" title="">%2$s</a>', $link, $text);
                        } elseif ("image" == $control_type) {
                            $link = $items[$i]["link"];
                            $image = $items[$i]["url"];
                            printf('<a href="%1$s" title=""><img src="%2$s" alt=""></a>', $link, $image);
                        }
                    }
                    ?>
                </li>
            <?php endfor; ?>
        </ol>
        <?php
    }

    public static function carousel_before($args = array()) {
        $id = "";
        $class = "";
        extract($args, EXTR_OVERWRITE);
        if(empty($id)) {
            return;
        }
        $class = SB_PHP::add_string_unique($class, " carousel");
        $class = SB_PHP::add_string_unique($class, " slide");
        printf('<div id="%1$s" class="%2$s"><div class="carousel-inner">', $id, $class);
    }

    public static function carousel_after($args = array()) {
        echo '</div>';
        self::carousel_navigation($args);
        echo '</div>';
        self::carousel_control($args);
    }

    public static function carousel_control($args = array()) {
        $id = "";
        $hide_control = true;
        extract($args, EXTR_OVERWRITE);
        if(empty($id) || $hide_control) {
            return;
        }
        ?>
        <div class="slide-control sb-carousel-control">
            <a data-slide="prev" href="#<?php echo $id; ?>" class="prev"><i class="fa fa-angle-left"></i></a>
            <a data-slide="next" href="#<?php echo $id; ?>" class="next"><i class="fa fa-angle-right"></i></a>
        </div>
        <?php
    }

    public static function the_cart() {
        SB_WP::the_cart();
    }

    public static function tab_content_control($args = array()) {
        $tabs = array();
        extract($args, EXTR_OVERWRITE);
        if(!is_array($tabs) || count($tabs) < 1) {
            return;
        }
        $first_tab = array_shift($tabs);
        ?>
        <ul class="nav nav-pills">
            <?php $icon = (isset($first_tab['icon']) && !empty($first_tab['icon'])) ? 'fa-'.$first_tab['icon'] : 'none'; ?>
            <li class="active sb-tab"><i class="fa <?php echo $icon; ?>"></i> <a data-toggle="tab" href="#<?php echo $first_tab['id']; ?>"><?php echo $first_tab['text']; ?></a></li>
            <?php foreach($tabs as $tab) : ?>
                <?php $icon = (isset($tab['icon']) && !empty($tab['icon'])) ? 'fa-'.$tab['icon'] : 'none'; ?>
                <li class="sb-tab"><i class="fa <?php echo $icon; ?>"></i> <a data-toggle="tab" href="#<?php echo $tab['id']; ?>"><?php echo $tab['text']; ?></a></li>
            <?php endforeach; ?>
        </ul>
        <?php
    }
	
}
?>