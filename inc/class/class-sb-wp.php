<?php
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
	
	public static function remove_trailing_slash($string) {
		return untrailingslashit($string);
	}
	
	public static function get_login_uri() {
		return wp_login_url(self::get_redirect_uri());
	}
	
	public static function login_uri() {
		echo self::get_login_uri();
	}
	
	public static function get_author_post_url() {
		return get_author_posts_url( get_the_author_meta( 'ID' ) );
	}
	
	public static function get_image_url($name) {
		return SB_Theme::get_image($name);
	}
	
	public static function get_post_thumbnail($args = array()) {
		$defaults = array(
			'size'		=> array(),
			'size_name'	=> 'thumbnail'
		);
		$args = wp_parse_args($args, $defaults);
		extract($args, EXTR_SKIP);
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
		
		return '<img class="no-thumbnail wp-post-image" width="'.$width.'" height="'.$height.'" src="'.self::get_image_url('no-thumbnail.png').'"'.$style.'>';
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
		return get_option('posts_per_page');
	}
	
	public static function get_menus() {
		return get_terms('nav_menu');
	}
	
	public static function get_signup_url() {
		return apply_filters('sb_register_url', wp_registration_url());
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
	
	public static function send_mail($to, $subject, $body) {
		//add_filter( 'wp_mail_content_type', array(self, 'set_html_content_type' ));
		$done = wp_mail( $to, $subject, $body );
		//remove_filter( 'wp_mail_content_type', array(self, 'set_html_content_type') );
		return $done;
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

		if ( SB_PHP::is_url_valid( $url ) ) {
			$old_url = get_option( 'siteurl' );
			
			if ( strcmp( $old_url, $url ) != 0 ) {
				self::update_permalink( '/%postname%' );
				update_option( 'siteurl', $url );
				$items = self::get_home_menu_item();
				
				foreach ( $items as $item ) {
					update_post_meta( $item->ID, '_menu_item_url', $url );
				}
				
			}
			
			$old_url = get_option( 'home' );
			
			if( strcmp( $old_url, $url ) != 0 ) {
				update_option( 'home', $url );
			}
			
		}
		
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
		$defaults = array(
			'password'	=> SB_USER_PASSWORD,
			'role'		=> 'subscriber'
		);
		$args = wp_parse_args($args, $defaults);
		extract($args, EXTR_SKIP);
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
	
	public static function get_all_user() {
		return get_users();
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
	
	public static function register_form() {
		
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
}
?>