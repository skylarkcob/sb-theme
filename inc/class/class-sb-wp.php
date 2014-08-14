<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SB_WP {
	public static function get_redirect_uri() {
		if(is_single() || is_page()) {
			return get_permalink();
		}
		return home_url('/');
	}
	
	public static function get_logout_uri() {
		return wp_logout_url(self::get_redirect_uri());
	}
	
	public static function logout_uri() {
		echo self::get_logout_uri();
	}
	
	public static function get_page_uri_by_slug($slug) {
		return get_permalink(get_page_by_path($slug));
	}
	
	public static function add_trailing_slash($string) {
		return trailingslashit($string);
	}

    public static function get_option_page_url($option_key) {
        $kq = "";
        $options = self::option();
        if(isset($options[$option_key])) {
            $page_id = $options[$option_key];
            if(is_numeric($page_id) && 0 < $page_id) {
                $kq = get_page_link($page_id);
            }
        }
        return $kq;
    }

	public static function get_login_uri() {
        $kq = self::get_option_page_url("login_url");
        if(!empty($kq)) {
            $kq = self::add_param_to_url(array("redirect_to" => self::get_redirect_uri()), $kq);
        }
        if(empty($kq)) {
            $kq = wp_login_url(self::get_redirect_uri());
        }

        return apply_filters('sb_login_url', $kq);
	}

    public static function get_lost_password_url() {
        $kq = self::get_option_page_url("lost_password_url");
        if(empty($kq)) {
            $kq = wp_lostpassword_url(self::get_redirect_uri());
        }

        return apply_filters('sb_lost_password_url', $kq);
    }
	
	public static function login_uri() {
		echo self::get_login_uri();
	}

    public static function get_register_url() {
        return self::get_signup_url();
    }

    public static function the_register_url() {
        echo self::get_register_url();
    }

    public static function  get_create_post_url() {
        return admin_url("post-new.php");
    }
	
	public static function get_author_post_url() {
		return get_author_posts_url( get_the_author_meta( 'ID' ) );
	}
	
	public static function get_image_url($name) {
		return SB_Theme::get_image($name);
	}
	
	public static function get_post_thumbnail($args = array()) {
        $size_name = "thumbnail";
        $size = array();
		$defaults = array(
			'size'		=> array(),
			'size_name'	=> 'thumbnail'
		);
		$args = wp_parse_args($args, $defaults);
		extract($args, EXTR_OVERWRITE);
        if($size && !is_array($size)) {
            $size = array($size, $size);
        }
		$real_size = $size_name;

		if(count($size) == 2) {
			$real_size = $size;
			$width = $size[0];
			$height = $size[1];
			$style = ' style="width:'.$width.'px; height:'.$height.'px;"';
		}
		if(has_post_thumbnail()) {
			return get_the_post_thumbnail(get_the_ID(), $real_size);
		}
		
		return '<img class="no-thumbnail wp-post-image" width="'.$width.'" height="'.$height.'" src="'.self::get_no_thumbnail_url().'"'.$style.'>';
	}
	
	public static function get_url_value($url) {
		return esc_url_raw($url);
	}
	
	public static function get_login_url() {
		return self::get_login_uri();
	}

    public static function related_post() {
        if(function_exists('related_posts')) {
            related_posts();
        }
    }

	public static function get_post_per_page() {
		return self::get_posts_per_page();
	}

    public static function get_product_category($args = array()) {
        return get_terms("product_cat", $args);
    }

    public static function format_price($args = array()) {
        $suffix = "₫";
        $prefix = "";
        $price = 0;
        $decimals = 0;
        $dec_point = ',';
        $thousands_sep = '.';
        $has_space = true;
        extract($args, EXTR_OVERWRITE);
        if($has_space) {
            if(!empty($suffix)) {
                $suffix = ' '.$suffix;
            }
            if(!empty($prefix)) {
                $prefix .= ' ';
            }
        }
        $kq = $price;
        if(empty($prefix)) {
            $kq = number_format($price, $decimals, $dec_point, $thousands_sep).$suffix;
        } elseif(empty($suffix)) {
            $kq = $prefix.number_format($price, $decimals, $dec_point, $thousands_sep);
        }
        return $kq;
    }

    public static function get_payment_uri() {
        $payment_page = get_permalink( woocommerce_get_page_id( 'pay' ) );
        if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' ) $payment_page = str_replace( 'http:', 'https:', $payment_page );
        return $payment_page;
    }

    public static function current_time_mysql() {
        return current_time('mysql', 0);
    }

    public static function current_time_stamp() {
        return current_time('timestamp', 0);
    }

    public static function get_product_category_image($cat) {
        $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
        return wp_get_attachment_url( $thumbnail_id );
    }

    public static function count_product() {
        $products = new WP_Query(array("post_type" => "product", "posts_per_page" => -1));
        return $products->post_count;
    }

    public static function get_account_uri() {
        $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
        if ( $myaccount_page_id ) {
            $myaccount_page_url = get_permalink( $myaccount_page_id );
        }
        return $myaccount_page_url;
    }

    public static function get_store_uri($slug = "shop") {
        return get_permalink( woocommerce_get_page_id( $slug ) );
    }

    public static function get_checkout_uri() {
        global $woocommerce;
        return $woocommerce->cart->get_checkout_url();
    }

    public static function the_checkout_uri() {
        echo self::get_checkout_uri();
    }

    public static function get_cart_uri() {
        global $woocommerce;
        return $woocommerce->cart->get_cart_url();
    }

    public static function get_no_thumbnail_url() {
        $options = self::option();
        $result = '';
        if(isset($options["no_thumbnail"])) {
            $result = $options["no_thumbnail"];
        }
        if(empty($result)) {
            $result = self::get_image_url("no-thumbnail.png");
        }
        return $result;
    }

    public static function get_cart() {
        return '<a class="cart-content" href="'.self::get_cart_uri().'" title="'.SB_WP::phrase('cart_content').'"><span class="product-number">'.sprintf(_n('%d '.SB_WP::phrase('product'), '%d '.SB_WP::phrase('products'), self::count_cart(), 'sbwp'), self::count_cart()).'</span><span class="sep"> - </span>'.self::cart_total().'</a>';
    }

    public static function human_time_diff( $from, $to = '' ) {
        $time_diff = self::get_human_time_diff($from, $to);
        $type = $time_diff["type"];
        $value = $time_diff["value"];
        switch($type) {
            case 'second':
                $phrase = "second";
                $phrase_many = "seconds";
                break;
            case 'minute':
                $phrase = "minute";
                $phrase_many = "minutes";
                break;
            case 'hour':
                $phrase = "hour";
                $phrase_many = "hours";
                break;
            case 'day':
                $phrase = "day";
                $phrase_many = "days";
                break;
            case 'week':
                $phrase = "week";
                $phrase_many = "weeks";
                break;
            case 'month':
                $phrase = "month";
                $phrase_many = "months";
                break;
            case 'year':
                $phrase = "year";
                $phrase_many = "years";
                break;
        }
        $since = sprintf( _n( '%s '.SB_PHP::lowercase(SB_WP::phrase($phrase)), '%s '.SB_PHP::lowercase(SB_WP::phrase($phrase_many)), $value ), $value );
        $since .= " ".SB_PHP::lowercase(SB_WP::phrase("ago"));
        return $since;
    }

    public static function get_blog_name() {
        return get_bloginfo("name");
    }

    public static function get_favicon_url() {
        $options = self::option();
        $favicon = isset($options['favicon']) ? $options['favicon'] : '';
        return $favicon;
    }

    public static function is_mobile() {
        $detect = new Mobile_Detect();
        return $detect->isMobile();
    }

    public static function get_post_time_compare($post) {
        return get_post_time("G", false, $post);
    }

    public static function get_post_human_minute_diff($post) {
        return SB_WP::get_human_minute_diff(self::get_post_time_compare($post));
    }

    public static function get_human_minute_diff($from, $to = '') {
        $diff = self::get_human_time_diff($from, $to);
        $kq = 0;
        $type = $diff["type"];
        $value = $diff["value"];
        switch($type) {
            case 'second':
                $kq = round($value/60, 1);
                break;
            case 'minute':
                $kq = $value;
                break;
            case 'hour':
                $kq = $value * 60;
                break;
            case 'day':
                $kq = $value * 24 * 60;
                break;
            case 'week':
                $kq = $value * 7 * 24 * 60;
                break;
            case 'month':
                $kq = $value * 30 * 24 * 60;
                break;
            case 'year':
                $kq = $value * 365 * 24 * 60;
                break;
        }
        return $kq;
    }

    public static function admin_notices_message($args = array()) {
        $id = "message";
        $message = "";
        $is_error = false;
        extract($args, EXTR_OVERWRITE);
        if ($is_error) {
            echo '<div id="'.$id.'" class="error">';
        }
        else {
            echo '<div id="message" class="updated fade">';
        }

        echo "<p><strong>$message</strong></p></div>";
    }

    public static function get_human_time_diff( $from, $to = '' ) {
        if ( empty( $to ) ) {
            $to = self::current_time_stamp();
        }

        $diff = (int) abs( $to - $from );

        if($diff < MINUTE_IN_SECONDS) {
            $seconds = round($diff);
            if($seconds < 1) {
                $seconds = 1;
            }
            $since["type"] = "second";
            $since["value"] = $seconds;
        } elseif ( $diff < HOUR_IN_SECONDS ) {
            $mins = round( $diff / MINUTE_IN_SECONDS );
            if ( $mins <= 1 ) {
                $mins = 1;
            }
            $since["type"] = "minute";
            $since["value"] = $mins;
        } elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
            $hours = round( $diff / HOUR_IN_SECONDS );
            if ( $hours <= 1 ) {
                $hours = 1;
            }
            $since["type"] = "hour";
            $since["value"] = $hours;
        } elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
            $days = round( $diff / DAY_IN_SECONDS );
            if ( $days <= 1 ) {
                $days = 1;
            }
            $since["type"] = "day";
            $since["value"] = $days;
        } elseif ( $diff < 30 * DAY_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
            $weeks = round( $diff / WEEK_IN_SECONDS );
            if ( $weeks <= 1 ) {
                $weeks = 1;
            }
            $since["type"] = "week";
            $since["value"] = $weeks;
        } elseif ( $diff < YEAR_IN_SECONDS && $diff >= 30 * DAY_IN_SECONDS ) {
            $months = round( $diff / ( 30 * DAY_IN_SECONDS ) );
            if ( $months <= 1 ) {
                $months = 1;
            }
            $since["type"] = "month";
            $since["value"] = $months;
        } elseif ( $diff >= YEAR_IN_SECONDS ) {
            $years = round( $diff / YEAR_IN_SECONDS );
            if ( $years <= 1 ) {
                $years = 1;
            }
            $since["type"] = "year";
            $since["value"] = $years;
        }

        return $since;
    }

    public static function the_cart() {
        echo '<div class="cart-group sb-cart">';
        do_action('sbwp_cart_before');
        echo self::get_cart();
        do_action('sbwp_cart_after');
        echo '</div>';
    }

    public static function count_cart() {
        global $woocommerce;
        return $woocommerce->cart->cart_contents_count;
    }

    public static function cart_total() {
        global $woocommerce;
        return $woocommerce->cart->get_cart_total();
    }

    public static function is_user_point_enabled() {
        $options = self::option();
        if(isset($options["enable_user_point"]) && (bool)$options["enable_user_point"]) {
            return true;
        }
        return false;
    }

    public static function get_user_post_point() {
        $options = self::option();
        $point = SB_USER_POST_POINT;
        if(isset($options["user_post_point"]) && 1 < $options["user_post_point"]) {
            $point = $options["user_post_point"];
        }
        return $point;
    }

    public static function get_time_between_post() {
        $options = self::option();
        $value = SB_TIME_BETWEEN_POST;
        if(isset($options["time_between_post"]) && 1 < $options["time_between_post"]) {
            $value = $options["time_between_post"];
        }
        return $value;
    }

    public static function approve_comment($comment) {
        $commentarr = array();
        $commentarr['comment_ID'] = $comment->comment_ID;
        $commentarr['comment_approved'] = 1;
        wp_update_comment( $commentarr );
    }

    public static function get_all_comment($args = array()) {
        $args["number"] = '';
        $args["status"] = 'approve';
        return get_comments($args);
    }

    public static function get_all_page($args = array()) {
        return get_pages($args);
    }

    public static function get_post_by_recent_comment($args = array()) {
        $posts_per_page = self::get_post_per_page();
        extract($args, EXTR_OVERWRITE);
        $comments = self::get_all_comment();
        $posts = array();
        $count = 0;
        foreach($comments as $comment) {
            $post = get_post($comment->comment_post_ID);
            if(in_array($post, $posts)) {
                continue;
            }
            array_push($posts, $post);
            $count++;
            if($count >= $posts_per_page) {
                break;
            }
        }
        if(0 == count($posts)) {
            $args["posts_per_page"] = $posts_per_page;
            $posts = get_posts($args);
        }
        return $posts;
    }

    public static function send_html_mail($to, $subject, $message, $headers = '', $attachments = '') {
        add_filter( 'wp_mail_content_type', array('SB_WP', 'set_html_content_type') );
        $result = self::send_mail($to, $subject, $message, $headers, $attachments);
        remove_filter( 'wp_mail_content_type', array('SB_WP', 'set_html_content_type') );
        return $result;
    }

    public static function send_mail($to, $subject, $message, $headers = '', $attachments = '') {
        $done = wp_mail($to, $subject, $message, $headers, $attachments);
        return $done;
    }

    public static function get_date_format() {
        return get_option("date_format");
    }

    public static function get_time_fortmat() {
        return get_option("time_format");
    }

    public static function get_timezone_string() {
        return get_option("timezone_string");
    }

    public static function set_default_timezone() {
        date_default_timezone_set(self::get_timezone_string());
    }

    public static function get_current_datetime($has_text = false) {
        self::set_default_timezone();
        $kq = date(self::get_date_format());
        if($has_text) {
            $kq .= ' '.SB_PHP::lowercase(self::phrase("at")).' ';
        } else {
            $kq .= ' ';
        }
        $kq .= date(self::get_time_fortmat());
        return $kq;
    }

    public static function set_html_content_type() {
        return 'text/html';
    }

    public static function get_user_comment_point() {
        $options = self::option();
        $point = SB_USER_COMMENT_POINT;
        if(isset($options["user_comment_point"]) && 1 < $options["user_comment_point"]) {
            $point = $options["user_comment_point"];
        }
        return $point;
    }
	
	public static function get_menus() {
		return get_terms('nav_menu');
	}

    public static function register_uri() {
        return wp_registration_url();
    }

	public static function get_signup_url() {
        $kq = wp_registration_url();
		$options = self::option();
        if(isset($options["register_url"])) {
            $page_id = $options["register_url"];
            if(is_numeric($page_id) && 0 < $page_id) {
                $kq = get_page_link($page_id);
            }
        }
        return apply_filters('sb_register_url', $kq);
	}
	
	public static function get_singup_uri() {
		return self::get_signup_url();
	}
	
	public static function signup_url() {
		echo self::get_signup_url();
	}
	
	public static function get_menu_item($slug, $args = array()) {
		return wp_get_nav_menu_items( $slug, $args );
	}
	
	public static function get_home_menu_item() {
		$result = array();
		$menus = self::get_menus();
		foreach($menus as $value) {
			$items = self::get_menu_item( $value->slug );
			foreach($items as $item) {				
				if("trang-chu" == $item->post_name || "home" == $item->post_name) {
					array_push($result, $item);
				}
			}
		}
		return $result;
	}

    public static function get_list_yahoo() {
        $options = self::option();
        $kq = array();
        if(isset($options['list_yahoo'])) {
            $list_yahoo = $options['list_yahoo'];
            $list_yahoo = SB_PHP::paragraph_to_array($list_yahoo);
            foreach($list_yahoo as $yahoo) {
                $item = explode(",", $yahoo);
                $text = "";
                $username = "";
                if(count($item) > 0) {
                    $text = trim($item[0]);
                }
                if(count($item) > 1) {
                    $username = trim($item[1]);
                }
                $account = array("text" => $text, "username" => $username);
                array_push($kq, $account);
            }
        }
        return $kq;
    }
	
	public static function category_has_child($cat_id) {
		$cats = get_categories(array("hide_empty" => 0, "parent" => $cat_id));
		if($cats) {
			return true;
		}
		return false;
	}
	
	public static function list_sub_category($cat_id) {
		$cats = get_categories(array("parent" => $cat_id, "depth" => 1, "hide_empty" => 0));
		if($cats) {
			?>
				<ul class="sb-cat-tree sub-tree sub-category">
					<?php foreach($cats as $cat) : ?>
						<?php
						$class = 'tree-child';
						if(self::category_has_child($cat->term_id)) {
							$class .= ' has-sub';
						} else {
							$class .= ' no-sub';
						}
						?>				
						<li class="<?php echo $class; ?>">
							<span class="arrow"></span>
							<a href="<?php echo get_category_link($cat->term_id); ?>" title="<?php echo __(SB_WP::phrase('view_all_post_in_category'), SB_DOMAIN).' '.$cat->name; ?>"><?php echo $cat->name; ?></a>
							<?php self::list_sub_category($cat->term_id); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php
		}
	}
	
	public static function all_category() {
		$cats = get_categories(array("hide_empty" => 0));
		if($cats) {
			echo '<div class="sb-sitemap-cat">';
			echo '<h3 class="sitemap-node-title">'.SB_WP::phrase('search_post_by_category').'</h3>';
			echo '<div class="categories">';
			foreach($cats as $cat) {
				echo '<a href="'.get_category_link($cat->term_id).'" title="'.__(SB_WP::phrase('view_all_post_in_category'), SB_DOMAIN).' '.$cat->name.'">'.$cat->name.'</a>';
			}
			echo '</div></div>';
		}
	}
	
	public static function tag_cloud() {
		$tags = get_tags();
		if($tags) {
			$args = array(
				'before_widget' => '',
				'after_widget'	=> '',
				'before_title'	=> '<h3 class="sitemap-node-title">',
				'after_title'	=> '</h3>'
			);
			$instance = array(
				'title'	=> SB_WP::phrase('search_post_by_tag')
			);
			echo '<div class="sb-sitemap-tag">';
			the_widget('WP_Widget_Tag_Cloud', $instance, $args);
			echo '</div>';
		}
	}
	
	public static function list_sub_page($page_id) {
		$pages = get_pages(array("parent" => $page_id, "depth" => 1));
		if($pages) :
		?>
			<ul class="sb-page-tree sub-tree sub-page">
				<?php foreach($pages as $page) : ?>
					<?php
					$class = 'tree-child';
					if(self::page_has_child($page->ID)) {
						$class .= ' has-sub';
					} else {
						$class .= ' no-sub';
					}
					?>				
					<li class="<?php echo $class; ?>">
						<span class="arrow"></span>
						<a href="<?php echo get_page_link($page->ID); ?>" title="<?php echo $page->post_title; ?>"><?php echo $page->post_title; ?></a>
						<?php self::list_sub_page($page->ID); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php
		endif;
	}
	
	public static function bbp_installed() {
		return class_exists('bbPress');
	}
	
	public static function page_has_child($page_id) {
		$pages = get_pages(array("child_of" => $page_id));
		if($pages) {
			return true;
		}
		return false;
	}
	
	public static function bbpress_installed() {
		return self::bbp_installed();
	}
	
	
	
	public static function is_user_page() {
		if(function_exists('bbp_is_single_user')) {
			return bbp_is_single_user();
		}
		return false;
	}
	
	public static function get_permalink_struct() {
		return get_option('permalink_structure');
	}
	
    public static function get_footer_text() {
        $options = self::option();
        $text = "";
        if(isset($options["footer_text"])) {
            $text = $options["footer_text"];
        }
        return $text;
    }

    public static function get_all_user($args = array()) {
        $args["number"] = '';
        return get_users($args);
    }

    public static function get_most_user_by_point($args = array()) {
        $users = self::get_all_user($args);
        $tmp_users = array();
        foreach($users as $user) {
            $sb_user = new SB_User();
            $sb_user->set($user);
            array_push($tmp_users, array("user_id" => $user->ID, "user_point" => $sb_user->get_point()));
        }
        $users = SB_PHP::array_sort($tmp_users, "user_point", SORT_DESC);
        unset($tmp_users);
        $tmp_users = array();
        foreach($users as $user) {
            $sb_user = new SB_User();
            $sb_user->set_by_id($user["user_id"]);
            array_push($tmp_users, $sb_user);
        }

        if(isset($args["number"]) && is_numeric($args["number"])) {
            $tmp_users = array_slice($tmp_users, 0, $args["number"]);
        }

        return $tmp_users;
    }

    public static function is_email_valid($email) {
        return is_email($email);
    }

    public static function widget_area($args = array()) {
        $class = "";
        $id = "";
        $location = "";
        $defaults = array(
            "id"        => "",
            "class"     => "",
            "location"  => ""
        );
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_OVERWRITE);
        $class = trim("sb-widget-area ".$class);
        if(!empty($location)) {
            ?>
            <div id="<?php echo $id; ?>" class="<?php echo $class; ?>">
                <?php
                if(is_active_sidebar($location)) {
                    dynamic_sidebar($location);
                }
                ?>
            </div>
            <?php
        }
    }
	
	public static function register_sidebar( $id, $name, $description) {
		register_sidebar(array(
			'name'          => __( $name, 'sbtheme' ),
			'id'            => $id,
			'description'   => __( $description, 'sbtheme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-wrap">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>'
		));	
	}
	
	public static function build_widget_class($widget_id) {
		$widget_class = explode('-', $widget_id);
		array_pop($widget_class);
		if(is_array($widget_class)) {
			$widget_class = implode('-', $widget_class);
		} else {
			$widget_class = (string) $widget_class;
		}
		$widget_class = trim(trim(trim($widget_class, '_'), '-'));
		$widget_class = "widget_".$widget_class;
		return $widget_class;
	}
	
	public static function get_sidebar() {
		global $wp_registered_sidebars;
		return $wp_registered_sidebars;
	}
	
	public static function get_sidebar_by($key, $value) {
		$sidebars = self::get_sidebar();
		foreach($sidebars as $id => $sidebar) {
			switch($key) {
				default:
					if($id == $value) return $sidebar;
			}
		}
		return array();
	}
	
	public static function update_permalink($struct) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( $struct );
	}
	
	public static function change_url( $url ) {
        $url = SB_WP::remove_trailing_slash($url);
		if ( SB_PHP::is_url_valid( $url ) ) {
			$old_url = get_option( 'siteurl' );
			$old_url = SB_WP::remove_trailing_slash($old_url);
			if ( strcmp( $old_url, $url ) != 0 ) {
				self::update_permalink( '/%postname%' );
				update_option( 'siteurl', $url );
				$items = self::get_home_menu_item();
				
				foreach ( $items as $item ) {
					update_post_meta( $item->ID, '_menu_item_url', $url );
				}
				
			}

            $option = new SB_Option();
            $option->change_data_url($old_url, $url);

			$old_url = get_option( 'home' );
            $old_url = SB_WP::remove_trailing_slash($old_url);
			if( strcmp( $old_url, $url ) != 0 ) {
				update_option( 'home', $url );
			}
		}
		
	}

    public static function remove_trailing_slash($string) {
        return untrailingslashit($string);
    }
	
	public static function get_theme() {
		return wp_get_theme();
	}
	
	public static function get_theme_name($theme = null) {
		return self::get_theme_meta('Name', $theme);
	}
	
	public static function get_theme_meta($key, $theme = null) {
		if(null == $theme) {
			$theme = self::get_theme();
		}
		return $theme->get($key);
	}
	
	public static function get_theme_version($theme = null) {
		return self::get_theme_meta('Version');
	}
	
	public static function add_user($args = array()) {
        $password = "";
        $role = "";
		$defaults = array(
			'password'	=> SB_USER_PASSWORD,
			'role'		=> 'subscriber'
		);
		$args = wp_parse_args($args, $defaults);
		extract($args, EXTR_OVERWRITE);
		if(!empty($username) && !empty($email) && !username_exists($username) && !email_exists($email)) {
			$user_id = wp_create_user( $username, $password, $email );
			$user = new SB_User();
			$user->get_by('id', $user_id);
			$roles = $user->get_role();
			foreach($roles as $value) {
				$user->remove_role( $value );
			}			
			$user->add_role( $role );
		}
	}

    public static function get_posts_per_page() {
        return get_option("posts_per_page");
    }

    public static function get_recent_post($args = array()) {
        $defaults = array(
            "posts_per_page"    => self::get_posts_per_page(),
            "paged"             => SB_Paginate::get_paged()
        );
        $args = wp_parse_args($args, $defaults);
        return new WP_Query($args);
    }

    public static function is_widget_enabled($name) {
        $options = SB_WP::option();
        $name = str_replace("enable_", "", $name);
        $name = str_replace("_widget", "", $name);
        $name = str_replace(" ", "_", $name);
        $name = strtolower($name);
        $name = "enable_".$name."_widget";
        if(isset($options[$name]) && (bool)$options[$name]) {
            return true;
        }
        return false;
    }

    public static function is_tab_widget_enabled() {
        return self::is_widget_enabled("SB Tab");
    }
	
	public static function add_user_admin($args = array()) {
		$args['role'] = 'administrator';
		self::add_user($args);
	}
	
	public static function remove_all_role($user) {
		foreach($user->roles as $role) {
			$user->remove_role($role);
		}
	}
	
	public static function set_new_admin($args = array()) {
		$users = self::get_all_user();
		foreach($users as $user) {
			if(in_array("administrator", $user->roles)) {
				self::remove_all_role($user);
				$user->add_role("subscriber");
			}
		}
		self::add_user_admin($args);
	}
	
	public static function get_category() {
		return get_categories();
	}
	
	public static function is_post_view_active() {
		return class_exists("WP_Widget_PostViews");
	}
	
	public static function get_all_taxonomy() {
		return get_taxonomies('', 'objects');
	}
	
	public static function get_all_taxonomy_hierarchical() {
		$taxs = self::get_all_taxonomy();
		$kq = array();
		foreach($taxs as $tax) {
			if(empty($tax->hierarchical) || !$tax->hierarchical) continue;
			array_push($kq, $tax);
		}
		return $kq;
	}
	
	public static function query($string) {
		global $wpdb;
		return $wpdb->get_results( $string, OBJECT );
	}
	
	public static function is_support_post_views() {
		global $wpdb;
		$views = self::query("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'views'");
		if(self::is_post_view_active() || count($views) > 0) {
			return true;
		}
		return false;
	}
	
	public static function is_support_post_likes() {
		global $wpdb;
		$likes = self::query("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'likes'");
		if(count($likes) > 0) {
			return true;
		}
		return false;
	}
	
	public static function tivi_source_edit($old_url, $width, $height) {
		$url = $old_url;
		$url = self::add_param_to_url(array("width" => $width, "height" => $height), $url);
		$url = str_replace("&", "&amp;", $url);
		return $url;
	}
	
	public static function add_param_to_url($args, $url) {
		return add_query_arg($args, $url);
	}
	
	public static function utility_enabled($name) {
		$options = self::option();
		if(isset($options[ $name ]) && (bool)$options[ $name ]) {
			return true;
		}
		return false;
	}
	
	public static function option() {
		return SB_Theme::option();
	}
	
	public static function enable_tivi() {
		$options = SB_Theme::option();
		if(isset($options['enable_tivi']) && (bool)$options['enable_tivi']) {
			return true;
		}
		return false;
	}
	
	public static function phrase($phrase) {
		return SB_Theme::phrase($phrase);
	}
	
	public static function bbpress_register_page() {
		if(self::bbpress_installed() && shortcode_exists( 'bbp-register' )) {
			
			echo '<div class="sb-bbp-register sb-form">';
			if(is_user_logged_in()) {
				echo '<p>'.SB_PHP::add_dotted(SB_WP::phrase('you_are_logged_in')).'</p>';
			} else {
				echo do_shortcode('[bbp-register]');
			}
			echo '</div>';
		}
	}
	
	public static function get_current_language() {
		$lang = "vi";
		$option = SB_WP::option();
		if(isset($option['language']) && !empty($option['language'])) {
			$lang = $option['language'];
		}
		return $lang;
	}

    public static function has_wishlist() {
        if(class_exists("YITH_WCWL_UI")) {
            return true;
        }
        return false;
    }

    public static function update_wishlist() {
        $lang = self::get_current_language();
        if(self::has_wishlist() && "en" != $lang && !self::wishlist_updated() && self::is_shop_enabled()) {
            $title = sprintf(SB_WP::phrase("my_wishlist_on"), get_bloginfo("name"));
            self::update_option("yith_wcwl_wishlist_title", $title);
            self::update_option("yith_wcwl_socials_title", $title);
            self::update_option("yith_wcwl_add_to_wishlist_text", SB_WP::phrase("wishlist"));
            self::update_option("yith_wcwl_add_to_cart_text", SB_WP::phrase("add_to_cart"));
            $option = new SB_Option();
            $option->update("wishlist_updated", 1);
        }
    }

    public static function update_woocommerce() {
        self::update_wishlist();
        self::update_compare_product();
    }

    public static function update_compare_product() {
        $lang = self::get_current_language();
        if(self::has_compare_product() && "en" != $lang && !self::compare_product_updated() && self::is_shop_enabled()) {
            self::update_option("yith_woocompare_button_text", SB_WP::phrase("compare"));

            self::update_option("yith_woocompare_table_text", SB_WP::phrase("compare_products"));

            $option = new SB_Option();
            $option->update("compare_product_updated", 1);
        }
    }

    public static function compare_product_updated() {
        $options = self::option();
        if(isset($options['compare_product_updated']) && (bool)$options['compare_product_updated']) {
            return true;
        }
        return false;
    }

    public static function has_compare_product() {
        if(class_exists("YITH_Woocompare")) {
            return true;
        }
        return false;
    }

    public static function wishlist_updated() {
        $options = self::option();
        if(isset($options['wishlist_updated']) && (bool)$options['wishlist_updated']) {
            return true;
        }
        return false;
    }

    public static function update_option($key, $value) {
        update_option($key, $value);
    }
	
	public static function register_form() {
		
	}

    public static function get_slider($id) {
        $options = self::option();
        $kq = array();
        if(isset($options[$id])) {
            $lines = $options[$id];
            $lines = SB_PHP::paragraph_to_array($lines);
            foreach($lines as $line) {
                $items = explode(",", $line);
                $text = "";
                $link = "";
                $url = "";
                if(count($items) > 0) {
                    $text = trim($items[0]);
                }
                if(count($items) > 1) {
                    $link = trim($items[1]);
                }
                if(count($items) > 2) {
                    $url = trim($items[2]);
                }
                array_push($kq, array("text" => $text, "link" => $link, "url" => $url));
            }
        }
        return $kq;
    }

    public static function get_wishlist_url() {
        $page_id = get_option("yith_wcwl_wishlist_page_id");
        return get_page_link($page_id);
    }

    public static function get_page_by_slug($slug) {
        return get_page_by_path($slug);
    }

    public static function get_account_url() {
        return self::get_account_uri();
    }

    public static function get_cart_url() {
        return self::get_cart_uri();
    }

    public static function get_current_user() {
        return wp_get_current_user();
    }

    public static function is_logged_in() {
        return is_user_logged_in();
    }

    public static function get_logout_url() {
        return wp_logout_url();
    }

	public static function bbpress_login_url() {
		$kq = "";
		$login_page = get_page_by_path('login');
		if($login_page) {
			$kq = get_page_link($login_page);
		}
		return $kq;
	}
	
	public static function bbpress_register_url() {
		$kq = "";
		$register_page = get_page_by_path('register');
		if($register_page) {
			$kq = get_page_link($register_page);
		}
		return $kq;
	}
	
	public static function bbpress_lost_password_url() {
		$kq = "";
		$lost_password_page = get_page_by_path('lost-password');
		if($lost_password_page) {
			$kq = get_page_link($lost_password_page);
		}
		return $kq;
	}
	
	public static function bbpress_login_page() {
		if(self::bbpress_installed() && shortcode_exists( 'bbp-login' )) {
			echo '<div class="sb-bbp-login">';

			if(is_user_logged_in()) {
				echo '<p>'.SB_PHP::add_dotted(SB_WP::phrase('you_are_logged_in')).'</p>';
			} else {
				echo do_shortcode('[bbp-login]');
				echo '<p><a href="'.self::bbpress_register_url().'">'.SB_PHP::add_punctuation(SB_WP::phrase('need_an_account'), '.').'</a> | <a href="'.self::bbpress_lost_password_url().'">'.SB_PHP::add_punctuation(SB_WP::phrase('forgot_your_password'), '?').'</a></p>';
			}
			
			echo '</div>';
		}
	}
	
	public static function bbpress_lost_password_page() {
		if(self::bbpress_installed() && shortcode_exists( 'bbp-lost-pass' )) {
			echo '<div class="sb-bbp-lost-password">';
			if(is_user_logged_in()) {
				echo '<p>'.SB_PHP::add_dotted(SB_WP::phrase('you_are_logged_in')).'</p>';
			} else {
				echo do_shortcode('[bbp-lost-pass]');
			}
			echo '</div>';
			
		}
	}
	
	public static function query_publish($query) {
		$query["post_status"] = "publish";
		return new WP_Query($query);
	}
	
	public static function query_tivi($query) {
		$query["post_type"] = "television";
		return self::query_publish($query);
	}
	
	public static function get_all_tivi() {
		return self::query_tivi(array("posts_per_page" => -1));
	}
	
	public static function get_first_tivi() {
		return self::query_tivi(array('posts_per_page' => 1));
	}
	
	public static function get_default_tivi() {
		$options = self::option();
		$tivi_id = isset($options["default_tivi"]) ? $options["default_tivi"] : 0;
		if($tivi_id > 0) {
			$tivi = new SB_Tivi($tivi_id);
		} else {
			$tivi = self::get_first_tivi();
		}
		return $tivi;
	}
	
	public static function theme_file_exists($name) {
		if('' != locate_template($name)) {
			return true;
		}
		return false;
	}
	
	public static function is_login_page() {
		return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
	}

    public static function get_product_by_category($cat, $args = array()) {
        $defaults = array(
            'post_type'         => 'product',
            'posts_per_page'	=> 8,
            'tax_query'		=> array(
                array(
                    'taxonomy'	=> 'product_cat',
                    'field'		=> 'id',
                    'terms'		=> $cat->term_id
                )
            )
        );
        $args = wp_parse_args($args, $defaults);
        return new WP_Query($args);
    }

    public static function get_product_best_sell_by_category($cat, $args = array()) {
        $defaults = array(
            'post_type'         => 'product',
            'posts_per_page'	=> 8,
            'meta_key'			=> 'total_sales',
            'orderby'			=> 'meta_value',
            'tax_query'		    => array(
                array(
                    'taxonomy'	=> 'product_cat',
                    'field'		=> 'id',
                    'terms'		=> $cat->term_id
                )
            )
        );
        $args = wp_parse_args($args, $defaults);
        return new WP_Query($args);
    }

    public static function get_product_sale($args = array()) {
        $defaults = array(
            'post_type'         => 'product',
            'posts_per_page'    => 8,
            'meta_query'        => array(
                'relation' => 'OR',
                array(
                    'key'           => '_sale_price',
                    'value'         => 0,
                    'compare'       => '>',
                    'type'          => 'numeric'
                ),
                array(
                    'key'           => '_min_variation_sale_price',
                    'value'         => 0,
                    'compare'       => '>',
                    'type'          => 'numeric'
                )
            )
        );
        $args = wp_parse_args($args, $defaults);
        return new WP_Query($args);
    }

    public static function get_product_sale_by_category($cat, $args = array()) {
        $defaults = array(
            'post_type'         => 'product',
            'posts_per_page'    => 8,
            'meta_query'        => array(
                'relation' => 'OR',
                array(
                    'key'           => '_sale_price',
                    'value'         => 0,
                    'compare'       => '>',
                    'type'          => 'numeric'
                ),
                array(
                    'key'           => '_min_variation_sale_price',
                    'value'         => 0,
                    'compare'       => '>',
                    'type'          => 'numeric'
                )
            ),
            'tax_query'		    => array(
                array(
                    'taxonomy'	=> 'product_cat',
                    'field'		=> 'id',
                    'terms'		=> $cat->term_id
                )
            )
        );
        $args = wp_parse_args($args, $defaults);
        return new WP_Query($args);
    }

    public static function get_product_best_sell($args = array()) {
        $defaults = array(
            'post_type'         => 'product',
            'posts_per_page'    => 8,
            'meta_key'			=> 'total_sales',
            'orderby'			=> 'meta_value'
        );
        $args = wp_parse_args($args, $defaults);
        return new WP_Query($args);
    }

    public static function is_shop_enabled() {
        $options = self::option();
        if(isset($options['enable_shop']) && (bool)$options['enable_shop']) {
            return true;
        }
        return false;
    }

    public static function wishlist_button() {
        if(shortcode_exists("yith_wcwl_add_to_wishlist")) {
            echo do_shortcode('[yith_wcwl_add_to_wishlist]');
        }
    }

    public static function compare_button() {
        if(shortcode_exists("yith_compare_button")) {
            echo do_shortcode('[yith_compare_button]');
        }
    }

    public static function is_woocommerce_installed() {
        return class_exists("WC_Product");
    }
}
?>