<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SB_Hook {
	private $styles = array();
	private $scripts = array();
	private $sidebars = array();
	public function __construct() {
		$this->style_init();
		$this->move_jquery_to_footer();
		$this->script_init();
		$this->run();
	}
	
	private function style_init() {
		$this->styles['bootstrap-style'] = SB_LIB_URI . '/bootstrap/css/bootstrap.min.css';
		$this->styles['font-awesome-style'] = SB_LIB_URI . '/font-awesome/css/font-awesome.min.css';
		$this->styles['superfish-style'] = SB_LIB_URI . '/superfish/css/superfish.min.css';
		$this->styles['sb-style'] = SB_CSS_URI . '/sb-style.css';
		$main_style = SB_THEME_PATH . "/main-style.css";

		if(file_exists($main_style)) {
			$this->styles['sbtheme-style'] = SB_THEME_URI . "/main-style.css";
		}

        if(SB_WP::is_mobile()) {
            $this->styles['sb-mobile-style'] = SB_CSS_URI . "/sb-mobile-style.css";
            $main_mobile_style = SB_THEME_PATH . "/main-mobile-style.css";
            if(file_exists($main_mobile_style)) {
                $this->styles['sbtheme-mobile-style'] = SB_THEME_URI . "/main-mobile-style.css";
            }
        }

	}

    public function allow_contributor_upload_media() {
        SB_WP::allow_contributor_upload_media();
    }
	
	private function script_init() {
		global $sb_enable_3dfile;
		array_push($this->scripts, array('bootstrap', SB_LIB_URI . '/bootstrap/js/bootstrap.min.js', array()));
		array_push($this->scripts, array('superfish', SB_LIB_URI . '/superfish/js/superfish.min.js', array('jquery', 'hoverIntent')));
		array_push($this->scripts, array('sbtheme', SB_JS_URI . '/sb-script.js', array('jquery')));
		array_push($this->scripts, array('addthis', SB_JS_URI . '/addthis_widget.js#pubid=ra-4e8109ea4780ac8d', array()));
		$main_script = SB_THEME_PATH . "/js/sbtheme-script.js";
		if(file_exists($main_script)) {
			array_push($this->scripts, array('sbtheme-script', SB_THEME_URI . '/js/sbtheme-script.js', array('sbtheme')));
		}
	}
	
	public function allow_upload_stl_file($mimes) {
		return array_merge($mimes, array('stl' => '3d/stl'));
	}

    public function sb_new_post_status() {

    }

    public function set_current_user() {
        if(!SB_WP::show_admin_bar()) {
            show_admin_bar(false);
        } else {
            if(current_user_can("edit_posts")) {
                show_admin_bar(true);
            }
        }
    }

	private function sidebar_init() {
		$options = SB_WP::option();
		$widget = new SB_Widget();
		$this->register_sidebar('primary', 'Main Sidebar', SB_PHP::add_dotted(SB_Theme::phrase("main_sidebar_description")));
        $this->register_sidebar('footer', 'Footer Widgets', SB_PHP::add_dotted(SB_Theme::phrase("footer_sidebar_description")));
		do_action("sbtheme_widget_area");
		if(isset($options['enable_leaderboard_ads']) && (bool)$options['enable_leaderboard_ads']) {
			SB_WP::register_sidebar( 'leaderboard-ads', "Leaderboard Banner", SB_PHP::add_dotted(SB_Theme::phrase("leaderboard_banner_description")));
		}
		if(isset($options['enable_float_ads']) && (bool)$options['enable_float_ads']) {
			SB_WP::register_sidebar( 'float-ads-left', "Float Ads Left", SB_PHP::add_dotted(SB_Theme::phrase("float_ads_left_description")));
			SB_WP::register_sidebar( 'float-ads-right', "Float Ads Right", SB_PHP::add_dotted(SB_Theme::phrase("float_ads_right_description")));
		}
		if(SB_WP::is_tab_widget_enabled()) {
            SB_WP::register_sidebar("tab-sidebar", "Tab Sidebar", SB_PHP::add_dotted(SB_WP::phrase("tab_sidebar_description")));
        }
	}
	
	public function sbtheme_widget_init() {
		$this->sidebar_init();
        do_action("sb_widgets_init");
	}
	
	public function sbtheme_sidebar_widget_hook() {
		add_action("widgets_init", array($this, 'sbtheme_widget_init'));
		//add_filter('dynamic_sidebar_params', array($this, 'sbtheme_widget_param'));
		//add_filter('widget_title', array($this, 'default_widget_title'), 10, 3);
	}
	
	private function move_jquery_to_footer() {
		if(!is_admin()) {
			$jquery = ABSPATH . WPINC . "/js/jquery/jquery.js";
			$jquery_migrate = ABSPATH . WPINC . "/js/jquery/jquery-migrate.min.js";
			if(file_exists($jquery) && file_exists($jquery_migrate)) {
                add_action( 'wp_print_scripts', array($this, "dequeue_jquery_script"), 100 );
                add_action('wp_enqueue_scripts', array($this, "enqueue_jqeury_script"));
			}
		}
	}

    public function dequeue_jquery_script() {
        wp_dequeue_script('jquery');
        wp_deregister_script('jquery');
        wp_dequeue_script('jquery-migrate');
        wp_deregister_script('jquery-migrate');
    }

    public function enqueue_jqeury_script() {
        wp_register_script('jquery-migrate', includes_url("js/jquery/jquery-migrate.min.js"), array(), false, true);
        wp_register_script('jquery', includes_url("js/jquery/jquery.js"), array('jquery-migrate'), false, true);
        wp_enqueue_script('jquery');
    }
	
	public function script_and_style() {
		// Enqueue style
		foreach($this->styles as $key => $url) {
            if("sbtheme-mobile-style" == $key || "sb-mobile-style" == $key) {
                wp_register_style($key, $url, array(), false, "only screen and (min-width: 320px) and (max-width: 1024px)");
            } else {
                wp_register_style($key, $url);
            }

			wp_enqueue_style($key);
		}
		
		// Enqueue script
		foreach($this->scripts as $value) {
			$options = SB_WP::option();
			$handle = $value[0];
            if("sbtheme-script" == $handle) {
                $file = SB_THEME_PATH . "/js/sbtheme-script.js";
                if(!file_exists($file)) continue;
            }
			if("addthis" == $handle) {
				if(!SB_WP::utility_enabled('enable_addthis')) continue;
			}
			wp_register_script($handle, $value[1], $value[2], false, true);
			wp_enqueue_script($handle);
			if("sbtheme" == $handle) {
				wp_localize_script( $handle, 'sbAjax', array( 'url' => SB_WP::get_ajax_url() ) );
			}
		}
		
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
	}
	
	public function sbtheme_set_html_mail(){
		return "text/html";
	}
	
	public function sbtheme_customize_init($wp_customize) {
		$customize = new SB_Customize();
		$customize->init($wp_customize);
	}

    public function pre_get_posts($query) {
        do_action("sb_pre_get_posts", $query);
    }
	
	private function run() {
		$this->clear_head_meta();
        add_action('set_current_user', array($this, 'set_current_user'));
        add_action("pre_get_posts", array($this, "pre_get_posts"));
        add_filter('sanitize_file_name', array($this, 'fix_media_name'), 10);
		add_filter('intermediate_image_sizes_advanced', array($this, 'remove_default_image_sizes'));
		$this->sbtheme_sidebar_widget_hook();
		add_action('wp_enqueue_scripts', array($this, 'script_and_style'));
		add_action('after_setup_theme', array($this, 'sbtheme_setup'));
		add_action( 'customize_preview_init', array($this, 'sbtheme_customize_script') );
		add_filter('excerpt_more', array($this, 'change_excerpt_more'));
		add_filter( 'wp_mail_content_type', array($this, 'sbtheme_set_html_mail' ));
		if(SB_WP::utility_enabled('enable_3dfile')) {
			add_filter('upload_mimes', array($this, 'allow_upload_stl_file'));
		}
		
		if(SB_WP::utility_enabled('enable_links_manager')) {
			add_filter( 'pre_option_link_manager_enabled', '__return_true' );
		}
		
		add_action('wp_footer', array($this, 'sbtheme_footer_element'));
		
		$this->sbtheme_admin_hook();
		add_action('init', array($this, 'sbtheme_init'));
		$this->sbtheme_custom_login_page();
		$this->sbtheme_translation();
        if(SB_WP::is_shop_enabled()) {
            add_filter( 'woocommerce_product_tabs', array($this, 'sbtheme_product_tab') );
            add_filter('woocommerce_cart_shipping_method_full_label', array($this, 'sbtheme_shipping_label'));
            add_filter('woocommerce_order_button_text', array($this, 'sbtheme_order_button_text'));
            add_filter('woocommerce_default_address_fields', array($this, 'sbtheme_default_address_fields'));
            add_filter('woocommerce_catalog_orderby', array($this, 'sbtheme_product_sort_default'));
            add_filter('add_to_cart_fragments', array($this, 'sbtheme_auto_update_cart'));
        }
        if(SB_WP::is_user_point_enabled()) {
            add_action('wp_insert_comment', array($this, 'on_comment_inserted'), 99, 2);
        }
        $this->sb_front_end();
	}

    public function on_comment_inserted($comment_id, $comment_object) {
        $comment = get_comment($comment_id);
        $user = new SB_User();
        $user->set_by_id($comment->user_id);
        if(SB_WP::is_user_point_enabled()) {
            if($comment && $user->is_valid()) {
                SB_WP::approve_comment($comment);
                if(!empty($comment->comment_type) || 1 != $comment->comment_approved) {
                    return;
                }
                $user->update_post_comment($comment);
                $user->update_point(SB_WP::get_user_comment_point());
                $post = get_post($comment->comment_post_ID);

                if($user->ID != $post->post_author) {
                    $author = new SB_User();
                    $author->set_by_id($post->post_author);
                    $author->minus_point(SB_WP::get_user_comment_point());
                    $author->receive_mail_post_have_comment($post);
                }

            }
        }
    }

    public function sbtheme_order_button_text() {
        return SB_WP::phrase("place_order");
    }

	public function sbtheme_translation() {
		if(!is_admin()) {
			$lang = SB_WP::get_current_language();
			if("en" != $lang) {
				add_filter('gettext', array($this, 'sbtheme_translation_all'), 20, 3);
                add_filter( 'gettext_with_context', array($this, "sbtheme_translate_text_with_context") );
			}
		}
	}

    public function sbtheme_translate_text_with_context($translated) {
        $punctuation = SB_PHP::get_punctuation($translated);
        $str_text = SB_PHP::remove_punctuation(SB_PHP::lowercase($translated));
        switch($str_text) {
            case 'street address':
                $translated = SB_WP::phrase("street_address");
                break;
            case 'notes about your order, e.g. special notes for delivery':
                $translated = SB_WP::phrase("order_note_description");
                break;
            case 'apartment, suite, unit etc. (optional)':
                $translated = SB_WP::phrase("more_address_description");
                break;
        }
        return $translated;
    }

    public function sbtheme_default_address_fields() {
        $fields = array(
            'country'            => array(
                'type'     => 'country',
                'label'    => __( 'Country', 'woocommerce' ),
                'required' => true,
                'class'    => array( 'form-row-wide', 'address-field', 'update_totals_on_change' ),
            ),
            'first_name'         => array(
                'label'    => __( 'First Name', 'woocommerce' ),
                'required' => true,
                'class'    => array( 'form-row-first' ),
            ),
            'last_name'          => array(
                'label'    => __( 'Last Name', 'woocommerce' ),
                'required' => true,
                'class'    => array( 'form-row-last' ),
                'clear'    => true
            ),
            'company'            => array(
                'label' => __( 'Company Name', 'woocommerce' ),
                'class' => array( 'form-row-wide' ),
            ),
            'address_1'          => array(
                'label'       => __( 'Address', 'woocommerce' ),
                'placeholder' => _x( 'Street address', 'placeholder', 'woocommerce' ),
                'required'    => true,
                'class'       => array( 'form-row-wide', 'address-field' )
            ),
            'address_2'          => array(
                'placeholder' => _x( SB_WP::phrase('more_address_description'), 'placeholder', 'woocommerce' ),
                'class'       => array( 'form-row-wide', 'address-field' ),
                'required'    => false
            ),
            'city'               => array(
                'label'       => __( 'Town / City', 'woocommerce' ),
                'placeholder' => __( 'Town / City', 'woocommerce' ),
                'required'    => true,
                'class'       => array( 'form-row-wide', 'address-field' )
            ),
            'state'              => array(
                'type'        => 'state',
                'label'       => __( 'State / County', 'woocommerce' ),
                'placeholder' => __( 'State / County', 'woocommerce' ),
                'required'    => true,
                'class'       => array( 'form-row-first', 'address-field' ),
                'validate'    => array( 'state' )
            ),
            'postcode'           => array(
                'label'       => __( 'Postcode / Zip', 'woocommerce' ),
                'placeholder' => __( 'Postcode / Zip', 'woocommerce' ),
                'required'    => true,
                'class'       => array( 'form-row-last', 'address-field' ),
                'clear'       => true,
                'validate'    => array( 'postcode' )
            ),
        );
        return $fields;
    }

    public function sbtheme_product_sort_default() {
        $args = array(
            'menu_order' => __( SB_WP::phrase('default_sorting'), SB_DOMAIN ),
            'popularity' => __( SB_WP::phrase('sort_by_popularity'), SB_DOMAIN ),
            'rating'     => __( SB_WP::phrase('sort_by_rating'), SB_DOMAIN ),
            'date'       => __( SB_WP::phrase('sort_by_newness'), SB_DOMAIN ),
            'price'      => __( SB_WP::phrase('sort_by_price_asc'), SB_DOMAIN ),
            'price-desc' => __( SB_WP::phrase('sort_by_price_desc'), SB_DOMAIN )
        );
        return $args;
    }

    public function sbtheme_auto_update_cart( $fragments ) {
        ob_start();
        SB_Theme::the_cart();
        $fragments['div.cart-group'] = ob_get_clean();
        return $fragments;
    }
	
	public function sbtheme_translation_all($translated_text) {
		$punctuation = SB_PHP::get_punctuation($translated_text);
        $str_text = SB_PHP::lowercase($translated_text);
        $str_text = SB_PHP::remove_punctuation($str_text);
		switch($str_text) {
            case 'registration complete. please check your e-mail':
                $translated_text = SB_WP::phrase('registration_complete_check_email').$punctuation;
                break;
            case 'check your e-mail for your new password':
                $translated_text = SB_WP::phrase('registration_complete_check_email').$punctuation;
                break;
            case 'check your e-mail for the confirmation link':
                $translated_text = SB_WP::phrase('check_email_for_confirm_link').$punctuation;
                break;
            case 'user registration is currently not allowed':
                $translated_text = SB_WP::phrase('registration_not_allowed').$punctuation;
                break;
            case 'remove':
                $translated_text = SB_WP::phrase('remove').$punctuation;
                break;
            case 'product successfully removed':
                $translated_text = SB_WP::phrase('product_successfully_removed').$punctuation;
                break;
            case 'no products were added to the wishlist':
                $translated_text = SB_WP::phrase('no_product_in_wishlist').$punctuation;
                break;
            case 'product added':
                $translated_text = SB_WP::phrase('product_added').$punctuation;
                break;
            case 'availability':
                $translated_text = SB_WP::phrase('availability').$punctuation;
                break;
            case 'added':
                $translated_text = SB_WP::phrase('added').$punctuation;
                break;
            case 'product name':
                $translated_text = SB_WP::phrase('product_name').$punctuation;
                break;
            case 'share on':
                $translated_text = SB_WP::phrase('share_on').$punctuation;
                break;
            case 'stock status':
                $translated_text = SB_WP::phrase('stock_status').$punctuation;
                break;
            case 'unit price':
                $translated_text = SB_WP::phrase('unit_price').$punctuation;
                break;
            case 'in stock':
                $translated_text = SB_WP::phrase('in_stock').$punctuation;
                break;
            case 'hello <strong>%1$s</strong> (not %1$s? <a href="%2$s">sign out</a>)':
                $translated_text = SB_WP::phrase('hello_account').$punctuation;
                break;
            case 'from your account dashboard you can view your recent orders, manage your shipping and billing addresses and <a href="%s">edit your password and account details</a>':
                $translated_text = SB_WP::phrase('account_dashboard_descrition').$punctuation;
                break;
            case 'my addresses':
                $translated_text = SB_WP::phrase('my_addresses');
                break;
            case 'the following addresses will be used on the checkout page by default':
                $translated_text = SB_WP::phrase('my_address_description');
                break;
            case 'confirm new password':
                $translated_text = SB_WP::phrase('confirm_new_password');
                break;
            case 'password (leave blank to leave unchanged)':
                $translated_text = SB_WP::phrase('password_with_description');
                break;
            case 'save changes':
                $translated_text = SB_WP::phrase('save_changes');
                break;
            case 'is a required field':
                $translated_text = SB_WP::phrase('is_a_required_field');
                break;
            case 'you have not set up this type of address yet':
                $translated_text = SB_WP::phrase("not_setup_this_type_address").$punctuation;
                break;
            case 'product':
                $translated_text = SB_WP::phrase('product');
                break;
            case 'price':
                $translated_text = SB_WP::phrase('price');
                break;
            case 'quantity':
                $translated_text = SB_WP::phrase('quantity');
                break;
            case 'coupon code':
                $translated_text = SB_WP::phrase('coupon_code');
                break;
            case 'apply coupon':
                $translated_text = SB_WP::phrase('apply_coupon');
                break;
            case 'update cart':
                $translated_text = SB_WP::phrase('update_cart');
                break;
            case 'proceed to checkout':
                $translated_text = SB_WP::phrase('proceed_to_checkout');
                break;
            case 'cart totals':
                $translated_text = SB_WP::phrase('cart_totals');
                break;
            case 'cart subtotal':
                $translated_text = SB_WP::phrase('cart_subtotal');
                break;
            case 'cart':
                $translated_text = SB_WP::phrase('cart');
                break;
            case 'order total':
                $translated_text = SB_WP::phrase('order_total');
                break;
            case 'direct bank transfer':
                $translated_text = SB_WP::phrase('direct_bank_transfer').$punctuation;
                break;
            case 'place order':
                $translated_text = SB_WP::phrase('place_order').$punctuation;
                break;
			case 'home':
				$translated_text = SB_WP::phrase('home').$punctuation;
				break;
			case 'search':
				$translated_text = SB_WP::phrase('search').$punctuation;
				break;
			case 'tags':
				$translated_text = SB_WP::phrase('tags').$punctuation;
				break;
			case 'normal':
				$translated_text = SB_WP::phrase('normal').$punctuation;
				break;
			case 'edit':
				$translated_text = SB_WP::phrase('edit').$punctuation;
				break;
			case 'close':
				$translated_text = SB_WP::phrase('close').$punctuation;
				break;
			case 'author':
				$translated_text = SB_WP::phrase('author').$punctuation;
				break;
			case 'reply':
				$translated_text = SB_WP::phrase('reply').$punctuation;
				break;
			case 'reply to':
				$translated_text = SB_WP::phrase('reply_to').$punctuation;
				break;
			case 'reply to: %s':
				$translated_text = SB_PHP::add_colon(SB_WP::phrase('reply_to')).' %s';
				break;
			case 'log in':
                $translated_text = SB_WP::phrase('log_in').$punctuation;
                break;
            case 'username':
                $translated_text = SB_WP::phrase('username').$punctuation;
                break;
			case 'password':
                $translated_text = SB_WP::phrase('password').$punctuation;
                break;
			case 'remember me':
                $translated_text = SB_WP::phrase('remember_me').$punctuation;
                break;
			case 'lost your password':
                $translated_text = SB_WP::phrase('lost_your_password').$punctuation;
                break;
			case '&larr; back to %s':
                $translated_text = SB_WP::phrase('back_to_home_page').$punctuation;
                break;
			case 'register':
                $translated_text = SB_WP::phrase('register').$punctuation;
                break;
			case 'e-mail':
                $translated_text = SB_WP::phrase('email').$punctuation;
                break;
			case 'a password will be e-mailed to you':
                $translated_text = SB_WP::phrase('a_password_will_be_email_to_you').$punctuation;
                break;
			case 'username or e-mail':
                $translated_text = SB_WP::phrase('username_or_email').$punctuation;
                break;
			case 'get new password':
                $translated_text = SB_WP::phrase('get_new_password').$punctuation;
                break;
			case 'you are now logged out':
                $translated_text = SB_WP::phrase('you_are_now_logged_out').$punctuation;
                break;
			case 'registration form':
                $translated_text = SB_WP::phrase('registration_form').$punctuation;
                break;
			case 'lost password':
                $translated_text = SB_WP::phrase('lost_password').$punctuation;
                break;
			case 'forums':
				$translated_text = SB_WP::phrase('forums').$punctuation;
				break;
			case 'forum':
				$translated_text = SB_WP::phrase('forum').$punctuation;
				break;
			case 'topics':
				$translated_text = SB_WP::phrase('topics').$punctuation;
				break;
			case 'posts':
				$translated_text = SB_WP::phrase('posts').$punctuation;
				break;
			case 'freshness':
				$translated_text = SB_WP::phrase('freshness').$punctuation;
				break;
			case 'started by: %1$s':
				$translated_text = SB_PHP::add_colon(SB_WP::phrase('started_by')).' %1$s';
				break;
			case 'topic':
				$translated_text = SB_WP::phrase('topic').$punctuation;
				break;
			case 'notify me of follow-up replies via email':
				$translated_text = SB_WP::phrase('notify_follow_up_email').$punctuation;
				break;
			case 'your account has the ability to post unrestricted html content':
				$translated_text = SB_WP::phrase('you_can_post_html_content').$punctuation;
				break;
			case 'subscribe':
				$translated_text = SB_WP::phrase('subscribe').$punctuation;
				break;
			case 'unsubscribe':
				$translated_text = SB_WP::phrase('unsubscribe').$punctuation;
				break;
			case 'favorite':
				$translated_text = SB_WP::phrase('favorite').$punctuation;
				break;
			case 'favorited':
				$translated_text = SB_WP::phrase('favorited').$punctuation;
				break;
			case 'topic title (maximum length: %d)':
				$translated_text = SB_WP::phrase('topic_title').' ('.SB_PHP::add_colon(SB_WP::phrase('maximum_length')).' %d)'.$punctuation;
				break;
			case 'topic tags':
				$translated_text = SB_WP::phrase('topic_tags').$punctuation;
				break;
			case 'topic type':
				$translated_text = SB_WP::phrase('topic_type').$punctuation;
				break;
			case 'topic status':
				$translated_text = SB_WP::phrase('topic_status').$punctuation;
				break;
            case 'the product is already in the wishlist':
                $translated_text = SB_PHP::add_punctuation(SB_WP::phrase("product_already_in_wishlist"), "!");
                break;
            case 'browse wishlist':
                $translated_text = SB_WP::phrase("browse_wishlist");
                break;
            case 'reviews':
                $translated_text = SB_WP::phrase("reviews");
                break;
            case 'description':
                $translated_text = SB_WP::phrase("description");
                break;
            case 'category':
                $translated_text = SB_WP::phrase("category").$punctuation;
                break;
            case 'reviews (%d)':
                $translated_text = SB_WP::phrase("reviews")." (%d)";
                break;
            case 'your review':
                $translated_text = SB_WP::phrase("your_review");
                break;
            case 'submit':
                $translated_text = SB_WP::phrase("submit");
                break;
            case 'add to cart':
                $translated_text = SB_WP::phrase("add_to_cart");
                break;
            case 'products':
                $translated_text = SB_WP::phrase("products");
                break;
            case 'company name':
                $translated_text = SB_WP::phrase("company_name");
                break;
            case 'address':
                $translated_text = SB_WP::phrase("address");
                break;
            case 'email address':
                $translated_text = SB_WP::phrase("email_address");
                break;
            case 'phone':
                $translated_text = SB_WP::phrase("phone");
                break;
            case 'billing details':
                $translated_text = SB_WP::phrase("billing_details");
                break;
            case 'billing address':
                $translated_text = SB_WP::phrase("billing_address");
                break;
            case 'shipping address':
                $translated_text = SB_WP::phrase("shipping_address");
                break;
            case 'save address':
                $translated_text = SB_WP::phrase("save_address");
                break;
            case 'ship to a different address':
                $translated_text = SB_WP::phrase("ship_to_different_address");
                break;
            case 'first name':
                $translated_text = SB_WP::phrase("first_name");
                break;
            case 'last name':
                $translated_text = SB_WP::phrase("last_name");
                break;
            case 'street address':
                $translated_text = SB_WP::phrase("street_address");
                break;
            case 'town / city':
                $translated_text = SB_WP::phrase("town_or_city");
                break;
            case 'your order':
                $translated_text = SB_WP::phrase("your_order");
                break;
            case 'total':
                $translated_text = SB_WP::phrase("total");
                break;
            case 'shipping and handling':
                $translated_text = SB_WP::phrase("shipping_fees");
                break;
            case 'order notes':
                $translated_text = SB_WP::phrase("order_notes");
                break;
		}
		return $translated_text;
	}

    public function sbtheme_shipping_label($label) {
        $tmp = SB_PHP::lowercase($label);
        if(SB_PHP::is_string_contain($tmp, 'free')) {
            $label = SB_WP::phrase('shipping_free');
        }
        return $label;
    }



    public function sbtheme_product_tab( $tabs = array() ) {
        global $product, $post;

        // Description tab - shows product content
        if(function_exists("woocommerce_product_description_tab")) {
            if ( $post->post_content ) {
                $tabs['description'] = array(
                    'title'    => __( SB_WP::phrase("product_description"), SB_DOMAIN ),
                    'priority' => 10,
                    'callback' => 'woocommerce_product_description_tab'
                );
            }
        }

        // Additional information tab - shows attributes
        if(function_exists("woocommerce_product_additional_information_tab")) {
            if ( $product && ( $product->has_attributes() || ( $product->enable_dimensions_display() && ( $product->has_dimensions() || $product->has_weight() ) ) ) ) {
                $tabs['additional_information'] = array(
                    'title'    => __( SB_WP::phrase("product_information"), SB_DOMAIN ),
                    'priority' => 20,
                    'callback' => 'woocommerce_product_additional_information_tab'
                );
            }
        }

        // Reviews tab - shows comments
        if(function_exists("comments_template")) {
            if ( comments_open() ) {
                $tabs['reviews'] = array(
                    'title'    => sprintf( __( SB_WP::phrase("reviews").' (%d)', SB_DOMAIN ), get_comments_number( $post->ID ) ),
                    'priority' => 30,
                    'callback' => 'comments_template'
                );
            }
        }

        return $tabs;
    }

	public function sbtheme_custom_login_page() {
		if(!is_admin() && SB_WP::is_login_page()) {
			$this->sbtheme_login_style_and_script();
			add_filter( 'login_headerurl', array($this, 'sbtheme_login_form_logo_url'));
			add_filter('login_headertitle', array($this, 'sbtheme_login_form_logo_description'));
			add_filter( 'login_errors', array($this, 'sbtheme_login_error_message'));
			add_filter('login_message', array($this, 'sbtheme_login_message'));
		}
	}
	
	public function sbtheme_login_style() {
		echo '<link media="all" type="text/css" href="'.SB_CSS_URI.'/sb-login-style.css" id="sb-login-style-css" rel="stylesheet">';

		$logo_url = SB_WP::get_logo_url();
		if(!empty($logo_url)) {
			echo '<style>';
			echo 'body.login div#login h1 a{background-image:url("'.$logo_url.'");}';
			echo '</style>';
		}

        $show_login_logo = apply_filters("sb_show_login_logo", true);
        if(!$show_login_logo) {
            printf('<style>body.login div#login h1 a{display:none;}</style>');
        }
	}


    public function default_image_upload_settings() {
        update_option('image_default_align', 'center' );
        update_option('image_default_link_type', 'none' );
        update_option('image_default_size', 'large' );
    }

    public function clear_head_meta() {
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
        remove_action('wp_head', 'feed_links');
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
    }

    public function empty_value() {
        return "";
    }

    public function fix_media_name($filename) {
        $info = pathinfo($filename);
        $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
        $ext = SB_PHP::lowercase($ext);
        $name = basename($filename, $ext);
        return SB_PHP::remove_vietnamese($name) . $ext;
    }

	public function sbtheme_login_style_and_script() {
		add_action( 'login_enqueue_scripts', array($this, 'sbtheme_login_style'));
		add_action( 'login_enqueue_scripts', array($this, 'sbtheme_login_script'));
	}
	
	public function sbtheme_login_script() {
		wp_register_script('sb-login', SB_JS_URI.'/sb-login-script.js', array(), false, true);
		wp_enqueue_script('sb-login');
	}
	
	public function sbtheme_login_form_logo_url() {
		return home_url('/');
	}
	
	public function sbtheme_login_form_logo_description() {
		return get_bloginfo('description');
	}
	
	public function sbtheme_login_message($message) {
		$action = $_REQUEST['action'];
		if($action == 'register') {
			$message = '<p class="message register">'.SB_PHP::add_dotted(SB_WP::phrase('register_for_this_site')).'</p>';
		} elseif($action == 'lostpassword') {
			$message = '<p class="message">'.SB_PHP::add_dotted(SB_WP::phrase('enter_your_email_to_receive_new_password')).'</p>';
		}
		return $message;
	}
	
	public function sbtheme_login_error_message($error) {
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'lostpassword') {
			$error = '<strong>'.SB_PHP::add_colon(SB_WP::phrase('error')).'</strong> '.SB_PHP::add_dotted(SB_WP::phrase('please_enter_your_email_correctly'));
		} elseif(isset($_REQUEST['registration']) && $_REQUEST['registration'] == 'disabled') {
			$error = '<strong>'.SB_PHP::add_colon(SB_WP::phrase('error')).'</strong> '.SB_PHP::add_dotted(SB_WP::phrase('you_do_not_have_permission_to_register'));
		} elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'register') {
			$error = '<strong>'.SB_PHP::add_colon(SB_WP::phrase('error')).'</strong> '.SB_PHP::add_dotted(SB_WP::phrase('please_check_your_information'));
		} else {
			$error = '<strong>'.SB_PHP::add_colon(SB_WP::phrase('login_failed')).'</strong> '.SB_PHP::add_dotted(SB_WP::phrase('please_enter_your_account_correctly'));
		}
		return $error;
	}
	

	
	public function sbtheme_footer_element() {
		if(SB_WP::utility_enabled('enable_scroll_top')) {
			$this->scroll_to_top();
		}
		if(SB_WP::utility_enabled('enable_float_ads')) {
			$this->float_ads_init();
		}
	}
	
	public function sbtheme_admin_hook() {
		if(is_admin()) {
            global $pagenow;
			add_action('admin_enqueue_scripts', array($this, 'sbtheme_admin_script_and_style'));

			//add_action('admin_enqueue_scripts', array($this, 'enqueue_rich_editor_script'));

            if('widgets.php' == $pagenow) {
                wp_enqueue_media();
            }

			add_action('admin_menu', array($this, 'sbtheme_custom_menu_page'), 102);

			add_action('admin_init', array($this, 'sbtheme_admin_init'), 99);
            add_action( 'save_post', array($this, 'on_post_published') );
            add_action('admin_notices', array($this, 'admin_notices'));
		}
	}

    public function enqueue_rich_editor_script() {
        wp_enqueue_script( 'word-count' );
        wp_enqueue_script('post');
        if ( user_can_richedit() ) {
            wp_enqueue_script('editor');
        }
    }

    public function on_post_published($post_id) {
        if ( (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) || (defined( 'DOING_AJAX' ) && DOING_AJAX) || (! current_user_can( 'edit_post', $post_id )) || (false !== wp_is_post_revision( $post_id )) ) {
            return;
        }
        $post = get_post($post_id);
        $minute = SB_WP::get_post_human_minute_diff($post);
        if(empty($post->post_status) || "publish" != $post->post_status || "post" != $post->post_type || 0.5 <= $minute) {
            delete_transient( "post_after_x_minute" );
            return;
        }
        $user = new SB_User();
        $user->set_by_id($post->post_author);
        $user_can_publish_post = apply_filters("sb_user_can_publish_post", true);
        if(SB_WP::is_user_point_enabled()) {

            if(!$user_can_publish_post) {
                set_transient( "post_after_x_minute", "no" );
                remove_action('save_post', array($this, "on_post_published"));
                wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
                add_action('save_post', array($this, "on_post_published"));
            } else {

                delete_transient( "post_after_x_minute" );

                $last_post_time = $user->get_last_post_time();
                $user->update_point(SB_WP::get_user_post_point());
                $time = SB_WP::current_time_mysql();
                $user->update_last_post_time($time);
                $user->set_next_post_time($time);
            }
        }

    }

    public function admin_notices() {
        if ( get_transient( "post_after_x_minute" ) == "no" ) {
            $user = new SB_User();
            SB_WP::admin_notices_message(array("message" => __(sprintf(SB_PHP::add_dotted(SB_WP::phrase("you_must_wait_x_minute_before_publish_next_post")), SB_PHP::date_minus_minute(SB_WP::current_time_mysql(), $user->get_next_post_time())), SB_DOMAIN), "is_error" => true, "id" => "sbPostLimit"));
            ?>
            <script>
                jQuery(document).ready(function($){
                   $("div.updated").css("display", "none");
                });
            </script>
            <?php
            delete_transient( "post_after_x_minute" );
        }
    }

	public function sbtheme_custom_menu_page() {
		remove_submenu_page( 'themes.php', 'customize.php' );
		remove_submenu_page( 'themes.php', 'theme-editor.php' );
	}
	
	public function change_excerpt_more($more) {
		return '...';
	}
	
	public function remove_default_image_sizes( $sizes ) {
		if(isset($sizes['thumbnail'])) unset( $sizes['thumbnail'] );
		if(isset($sizes['medium'])) unset( $sizes['medium'] );
		if(isset($sizes['large'])) unset( $sizes['large'] );     
		return $sizes;
	}
	
	public function register_sidebar($id, $name, $description) {
		SB_WP::register_sidebar( $id, $name, $description);
	}
	

	
	public function float_ads_init() {
		SB_Theme::float_ads_sidebar();
	}

	public function sbtheme_widget_param($params) {
		$widget_id = $params[0]['widget_id'];
		$widget_class = SB_WP::build_widget_class($widget_id);
        $params[0]['before_widget'] = '<div id="'.$widget_id.'" class="widget '.$widget_class.'"><div class="widget-wrap">';
        $params[0]['after_widget'] = '</div></div>';
        $params[0]['before_title'] = '<h4 class="widget-title">';
        $params[0]['after_title'] = '</h4>';
        return $params;
    }
	
	public function default_widget_title($title) {
		if(empty($title)) {
			$title = '<span class="no-title"></span>';
		}
		return $title;
	}
	
	public function sbtheme_setup() {
		global $sb_language;
		load_theme_textdomain( SB_DOMAIN, get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 300, 300 );
		add_image_size('thumbnail', 180, 120, false);
		add_image_size('thumbnail_crop', 180, 120, true);
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ));
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery' ));
		register_nav_menus( array(
			'top'		=> __( 'Top menu', 'sbtheme' ),
			'footer'	=> __( 'Footer menu', 'sbtheme' ),
			'primary'	=> __('Primary menu', 'sbtheme')
		));
		if(SB_WP::is_shop_enabled()) {
			add_theme_support( 'woocommerce' );
			add_image_size('product_large', 600, 600, false);
			add_image_size('product_large_crop', 600, 600, true);
			add_image_size('product_medium', 400, 400, false);
			add_image_size('product_medium_crop', 400, 400, true);
			add_image_size('product_small', 200, 200, false);
			add_image_size('product_small_crop', 200, 200, true);			
		}
		if("vi" == $sb_language->get()) {
			update_option("timezone_string", "Asia/Ho_Chi_Minh");
			update_option("date_format", "d/m/Y");
		}
		$permalink_struct = SB_WP::get_permalink_struct();
		if(empty($permalink_struct)) {
			SB_WP::update_permalink("/%postname%");
		}
		if(SB_WP::bbp_installed() && is_user_logged_in()) {
			add_filter( 'bbp_after_get_the_content_parse_args', array($this, 'bbp_enable_visual_editor'));
			add_filter( 'bbp_get_single_forum_description', array($this, 'bbp_empty_description') );
			add_filter( 'bbp_get_single_topic_description', array($this, 'bbp_empty_description') );
		}
        $this->default_image_upload_settings();
	}
	
	public function bbp_enable_visual_editor( $args = array() ) {
		$args['tinymce'] = true;
		$args['media_buttons'] = true;
		$args['dfw'] = true;
		$args['teeny'] = false;
		return $args;
	}
	
	public function bbp_empty_description() {
		return '';
	}
	
	public function sb_woocommerce_image_size() {
        $option = new SB_Option();
        $woocommerce_image_size_updated = (bool)$option->get("woocommerce_image_size_updated");
		if(SB_WP::is_shop_enabled() && SB_WP::is_woocommerce_installed() && !$woocommerce_image_size_updated) {
			$catalog = array(
				'width'		=> '400',
				'height'	=> '400',
				'crop'		=> 1
			);				 
			$single = array(
				'width'		=> '600',
				'height'	=> '600',
				'crop'		=> 1
			);				 
			$thumbnail = array(
				'width'		=> '200',
				'height'	=> '200',
				'crop'		=> 1
			);				
			update_option( 'shop_catalog_image_size', $catalog );
			update_option( 'shop_single_image_size', $single );
			update_option( 'shop_thumbnail_image_size', $thumbnail );
            $option->update("woocommerce_image_size_updated", 1);
		}
	}
	
	public function sbtheme_admin_script_and_style() {
		wp_register_script('sbtheme-admin', SB_JS_URI . '/sb-admin-script.js', array('jquery'), false, true);
		wp_localize_script( 'sbtheme-admin', 'sbAdminAjax', array( 'url' => SB_WP::get_ajax_url() ) );
		wp_enqueue_script('sbtheme-admin');
		
		
		wp_register_style('sbtheme-admin-style', SB_CSS_URI . '/sb-admin-style.css');
		wp_enqueue_style('sbtheme-admin-style');
		
	}
	
	public function sbtheme_customize_script() {
		wp_register_script( 'sb-customize', SB_JS_URI . '/customize.js', array( 'customize-preview' ), false, true );
		wp_enqueue_script('sb-customize');
	}
	
	public function session_init() {
		if(!session_id()) {
			session_start();
		}
	}
	
	public function scroll_to_top() {
		echo '<a id="scroll-to-top" href="#" class="go-top-button"><i class="fa fa-chevron-up"></i></a>';
	}
	
	public function sbtheme_admin_init() {
		add_filter('user_contactmethods', array($this, 'sbtheme_add_user_contact_method'));
	}
	
	public function sbtheme_add_user_contact_method($profile_fields) {
		if(!isset($profile_fields['twitter'])) {
			$profile_fields['twitter'] = 'Twitter URL';
		}
		if(!isset($profile_fields['facebook'])) {
			$profile_fields['facebook'] = 'Facebook URL';
		}
		if(!isset($profile_fields['gplus'])) {
			$profile_fields['gplus'] = 'Google+ URL';
		}
		if(isset($profile_fields['aim'])) {
			unset($profile_fields['aim']);
		}
		return $profile_fields;
	}

    public function sb_front_end() {
        if(!is_admin()) {
            add_action( 'admin_bar_menu', array($this, 'sb_toolbar_front_end'), 999 );
        }
    }

    public function sb_toolbar_front_end( $wp_admin_bar ) {
        if(current_user_can("manage_options")) {
            $args = array(
                'id'        => 'sb-options',
                'title'     => 'SB Options',
                'href'      => admin_url('themes.php?page=sbtheme-option'),
                'meta'      => array( 'class' => 'sb-options' ),
                'parent'    => 'themes',
                'tabindex'  => '10'
            );
            $wp_admin_bar->add_node( $args );
        }
    }

	public function sbtheme_init() {
		if(is_admin() && SB_WP::utility_enabled('enable_tivi')) {
			global $wp_post_types;
			if(post_type_exists("television")) {
				$television = $wp_post_types['television'];
				if($television) {
					$wp_post_types['television']->menu_icon = SB_Icon::post_type_icon("tivi");
				}
			}
		}
        $this->sb_new_post_status();
		$this->session_init();
        $this->sb_woocommerce_image_size();
	}
}
?>