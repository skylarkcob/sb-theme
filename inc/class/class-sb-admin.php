<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SB_Admin {
	
	/*
	 * Khởi tạo các biến
	 */
	
	// Biến lưu trữ tùy chọn
	private $options;
	
	private $data_section;
	
	// Tên tùy chọn lưu trong cơ sở dữ liệu
	private $option_name = "sb_options";
	
	// Đường dẫn của menu đến trang cài đặt tùy chọn
	private $menu_slug = "sbtheme-option";
	
	// Biến khai báo quyền người dùng được phép xem trang tùy chọn
	private $capability = 'manage_options';
	
	// Mảng chứa danh sách các tab trên sidebar
	private $list_tabs = array();
	
	// Khởi tạo lớp SB_Admin
	public function __construct() {
		$this->tab_init();
		$this->action_init();
	}
	
	// Chạy các action
	private function action_init() {
		add_action("admin_menu", array($this, "sbtheme_add_options_menu"));
		add_action("admin_init", array($this, "sbtheme_page_init"));
		add_action( 'wp_ajax_my_action', array($this, 'sbtheme_admin_ajax_callback' ));
	}
	
	/*
	 * Khai báo các tab
	 */
	
	// Thêm một tab mới vào danh sách
	private function add_tab($key, $title, $section_id) {
		$this->list_tabs[$key] = array('title' => $title, 'section_id' => $section_id);
	}
	
	// Thêm giá trị vào danh sách các tab
	private function tab_init() {
		$this->data_section = SB_PHP::get_session('data_section');
		$this->add_tab('general', SB_WP::phrase("general_settings"), 'sbtheme_general_section');
		$this->add_tab('home', SB_WP::phrase("home_page_settings"), 'sbtheme_home_section');
		if(SB_WP::enable_tivi()) {
			$this->add_tab('tivi', SB_WP::phrase("tivi_settings"), 'sbtheme_tivi_section');
		}
		$this->add_tab('ads', SB_WP::phrase("ads_settings"), 'sbtheme_ads_section');
		$this->add_tab('social', SB_WP::phrase("social_network_settings"), 'sbtheme_social_section');
		$this->add_tab('sbmodule', SB_WP::phrase("utility_management"), 'sbtheme_sbmodule_section');

        $this->add_tab('account', SB_WP::phrase("account"), 'sbtheme_account_section');

		$this->add_tab('aboutsb', SB_WP::phrase("about_sb"), 'sbtheme_aboutsb_section');
	}
	
	/*
	 * Tạo cài đặt các thông tin chung
	 */
	
	// Thêm trang cài đặt thông tin chung
	private function add_general_setting() {
		global $sb_language;
		$this->add_section('sbtheme_general_section', SB_WP::phrase("general_setting_description"));
		$this->add_general_field('language', SB_WP::phrase("choose_language"), "language_callback");
		$this->add_general_field('logo', 'Logo', 'logo_callback');
        $this->add_general_field('footer_logo', 'Footer logo', 'footer_logo_callback');
		$this->add_general_field('favicon', 'Favicon', 'favicon_callback');
		$this->add_general_field('banner', 'Banner', 'banner_callback');
        $this->add_general_field('hotline_image', 'Hotline', 'hotline_image_callback');
        $this->add_general_field('list_yahoo', SB_WP::phrase('list_yahoo'), 'list_yahoo_callback');
		$this->add_general_field('footer_text', SB_WP::phrase('footer_text'), 'footer_text_callback');
	}
	
	// Thêm mục cho trang cài đặt thông tin chung
	private function add_general_field($id, $title, $callback) {
		$this->add_field($id, $title, 'sbtheme_general_section', $callback);
	}
	
	// Hàm hiển thị mục cài đặt logo
	public function logo_callback() {
		$this->set_media_image_field('logo', SB_PHP::add_dotted(SB_WP::phrase("input_url_or_upload_new_logo")));
    }

    public function footer_logo_callback() {
        $this->set_media_image_field('footer_logo', SB_PHP::add_dotted(SB_WP::phrase("input_url_or_upload_new_logo")));
    }

    public function hotline_image_callback() {
        $this->set_media_image_field('hotline_image', SB_PHP::add_dotted(SB_WP::phrase("input_url_or_upload_new_image")));
    }
	
	// Hàm hiển thị mục cài đặt favicon
	public function favicon_callback() {
		$this->set_media_image_field('favicon', SB_PHP::add_dotted(SB_WP::phrase("input_url_or_upload_new_favicon")));
    }
	
	// Hàm hiển thị mục cài đặt banner
	public function banner_callback() {
		$this->set_media_image_field('banner', SB_PHP::add_dotted(SB_WP::phrase("input_url_or_upload_new_banner")));
	}
	
	public function language_callback() {
		global $sb_language;
		$this->set_select_field('language', SB_PHP::add_dotted(SB_WP::phrase("choose_language_description")));
	}
	
	public function footer_text_callback() {
        $args = array(
            "media_buttons" => false,
            "wpautop"       => true
        );
		$this->rich_editor_field('footer_text', SB_PHP::add_dotted(SB_WP::phrase('footer_text_description')), $args);
	}

    public function list_yahoo_callback() {
        $args = array(
            "media_buttons" => false,
            "wpautop"       => true,
            "quicktags"     => false
        );
        $this->rich_editor_field('list_yahoo', SB_PHP::add_dotted(SB_WP::phrase('list_yahoo_support_description')), $args);
    }
	
	private function rich_editor_field($name, $description, $args = array()) {
        $defaults = array(
            "textarea_name" => esc_attr($this->get_field_name($name)),
            "textarea_rows" => 5
        );
        $args = wp_parse_args($args, $defaults);
		$value = $this->get_option_value($name);
		?>
		<div id="sbRichEditor" class="sb-rich-editor">
			<?php wp_editor($value, $name, $args); ?>
			<p class="description"><?php echo $description; ?></p>
		</div>
		<?php
	}


	
	private function set_select_field($name, $description) {
		$this->set_field($name, $description, 'select');
	}
	
	private function select_field($name, $description) {
		global $sb_language;
		$langs = $sb_language->get_list();
		$value = $this->get_option_value($name);
		?>
        <label for="<?php echo $name; ?>"></label>
		<select id="<?php echo $name; ?>" name="<?php echo esc_attr($this->get_field_name($name)); ?>">
			<?php foreach($langs as $key => $title) : ?>
			<option value="<?php echo $key; ?>"<?php selected( $value, $key ); ?>><?php echo $title; ?></option>
			<?php endforeach; ?>
		</select>
		<p class="description"><?php echo $description; ?></p>
		<?php
	}
	
	/*
	 * Tạo cài đặt hiển thị trang chủ
	 */
	 
	// Thêm trang cài đặt hiển thị trang chủ
	private function add_home_setting() {
		$this->add_section('sbtheme_home_section', SB_WP::phrase('home_setting'));
		$this->add_home_field_setting();
	}
	
	private function add_home_field_setting() {
        $this->add_home_field("main_slider", SB_WP::phrase("main_slider"), "main_slider_callback");
        $this->add_home_field("sub_slider", SB_WP::phrase("sub_slider"), "sub_slider_callback");
		if(SB_WP::enable_tivi()) {
			$this->add_home_field("default_tivi", SB_WP::phrase("default_tivi"), "default_tivi_callback");
		}
	}
	
	private function add_home_field($id, $title, $callback) {
		$this->add_field($id, $title, 'sbtheme_home_section', $callback);
	}
	
	public function default_tivi_callback() {
		$televisions = SB_WP::get_all_tivi();
		$name = "default_tivi";
		$value = $this->get_option_value($name);
		?>
		<select id="<?php echo $name; ?>" name="<?php echo esc_attr($this->get_field_name($name)); ?>">
		<option value="0"><?php echo SB_WP::phrase("choose_tivi_channel"); ?></option>
		<?php if($televisions->have_posts()) : ?>
			<?php while($televisions->have_posts()) : ?>
				<?php $televisions->the_post(); ?>
				<?php
					$tivi_title = get_the_title();
					if(empty($tivi_title)) {
						continue;
					}
				?>
				<option value="<?php the_ID(); ?>"<?php selected( $value, get_the_ID() ); ?>><?php echo $tivi_title; ?></option>
			<?php endwhile; wp_reset_postdata(); ?>
		<?php endif; ?>
		</select>
		<p class="description"><?php echo SB_PHP::add_dotted(SB_WP::phrase("default_tivi_description")); ?></p>
		<?php
	}

    public function main_slider_callback() {
        $args = array(
            "media_buttons" => true,
            "wpautop"       => true,
            "quicktags"     => false,
            "textarea_rows" => 10
        );
        $this->rich_editor_field('main_slider', SB_PHP::add_dotted(SB_WP::phrase('main_slider_setting_description')), $args);
    }

    public function sub_slider_callback() {
        $args = array(
            "media_buttons" => true,
            "wpautop"       => true,
            "quicktags"     => false,
            "textarea_rows" => 10
        );
        $this->rich_editor_field('sub_slider', SB_PHP::add_dotted(SB_WP::phrase('main_slider_setting_description')), $args);
    }
	
	/*
	 * Tạo cài đặt thông tin mạng xã hội
	 */
	 
	// Thêm trang cài đặt thông tin mạng xã hội
	private function add_social_setting() {
		$this->add_section('sbtheme_social_section', SB_WP::phrase('social_setting'));
		$this->add_social_field('facebook', 'Facebook', 'facebook_callback');
		$this->add_social_field('twitter', 'Twitter', 'twitter_callback');
		$this->add_social_field('gplus', 'Google Plus', 'gplus_callback');
		$this->add_social_field('youtube', 'YouTube', 'youtube_callback');
		$this->add_social_field('linkedin', 'Linkedin', 'linkedin_callback');
		$this->add_social_field('pinterest', 'Pinterest', 'pinterest_callback');
		$this->add_social_field('rss', 'RSS', 'rss_callback');
		$this->add_social_field('zingme', 'Zing Me', 'zingme_callback');
	}
	
	// Thêm mục cho trang cài đặt thông tin mạng xã hội
	private function add_social_field($id, $title, $callback) {
		$this->add_field($id, $title, 'sbtheme_social_section', $callback);
	}
	
	// Hàm gán giá trị cho mục cài đặt mạng xã hội
	private function set_social_field($name, $description) {
		$this->set_field($name, $description, 'social');
	}
	
	
	private function social_field($name, $value, $description) {
		$this->text_field($name, $value, $description);
	}
	
	// Hàm hiển thị mục cài đặt Facebook
	public function facebook_callback() {
		$this->set_social_field('facebook', SB_PHP::add_dotted(SB_WP::phrase('input_facebook_url')));
	}
	
	// Hàm hiển thị mục cài đặt Twitter
	public function twitter_callback() {
		$this->set_social_field('twitter', SB_PHP::add_dotted(SB_WP::phrase('input_twitter_url')));
	}
	
	// Hàm hiển thị mục cài đặt Google Plus
	public function gplus_callback() {
		$this->set_social_field('gplus', SB_PHP::add_dotted(SB_WP::phrase('input_gplus_url')));
	}
	
	// Hàm hiển thị mục cài đặt Zing Me
	public function zingme_callback() {
		$this->set_social_field('zingme', SB_PHP::add_dotted(SB_WP::phrase('input_zingme_url')));
	}
	
	// Hàm hiển thị mục cài đặt YouTube
	public function youtube_callback() {
		$this->set_social_field('youtube', SB_PHP::add_dotted(SB_WP::phrase('input_youtube_url')));
	}
	
	// Hàm hiển thị mục cài đặt Pinterest
	public function pinterest_callback() {
		$this->set_social_field('pinterest', SB_PHP::add_dotted(SB_WP::phrase('input_pinterest_url')));
	}
	
	// Hàm hiển thị mục cài đặt Linkedin
	public function linkedin_callback() {
		$this->set_social_field('linkedin', SB_PHP::add_dotted(SB_WP::phrase('input_linkedin_url')));
	}
	
	// Hàm hiển thị mục cài đặt RSS
	public function rss_callback() {
		$this->set_social_field('rss', SB_PHP::add_dotted(SB_WP::phrase('input_rss_url')));
	}
	
	/*
	 * Tạo cài đặt quản lý quảng cáo
	 */
	private function add_ads_setting() {
		$this->add_section('sbtheme_ads_section', SB_WP::phrase("ads_settings_description"));
	}
	
	
	
	/*
	 * Tạo cài đặt quản lý tivi
	 */
	private function add_tivi_setting() {
		$this->add_section('sbtheme_tivi_section', SB_WP::phrase("tivi_settings_description"));
	}

    private function add_account_setting() {
        $this->add_section("sbtheme_account_section", SB_WP::phrase("account_setting_page"));
        $this->add_account_field("user_post_point", SB_WP::phrase("user_post_point"), "user_post_point_callback");
        $this->add_account_field("user_comment_point", SB_WP::phrase("user_comment_point"), "user_comment_point_callback");
    }

    public function user_post_point_callback() {
        $this->set_number_field("user_post_point", SB_PHP::add_dotted(SB_WP::phrase("user_post_point_setting_description")));
    }

    public function user_comment_point_callback() {
        $this->set_number_field("user_comment_point", SB_PHP::add_dotted(SB_WP::phrase("user_comment_point_setting_description")));
    }

    private function add_account_field($id, $title, $callback) {
        $this->add_field($id, $title, 'sbtheme_account_section', $callback);
    }

	/*
	 * Tạo cài đặt quản lý các gói tiện ích
	 */
	private function add_sbmodule_setting() {
		$this->add_section('sbtheme_sbmodule_section', SB_WP::phrase('sb_framework_utility_management'));
		$this->add_sbmodule_field('enable_shop', SB_WP::phrase('support_shop_functional'), 'enable_shop_callback');
		$this->add_sbmodule_field('enable_manga', SB_WP::phrase('support_manga_functional'), 'enable_manga_callback');
		$this->add_sbmodule_field('enable_tivi', SB_WP::phrase('support_tivi_functional'), 'enable_tivi_callback');

		$this->add_sbmodule_field('enable_sb_post_widget', 'SB Post Widget', 'enable_sb_post_widget_callback');
        $this->add_sbmodule_field('enable_sb_support_widget', 'SB Support Widget', 'enable_sb_support_widget_callback');
        $this->add_sbmodule_field('enable_sb_link_widget', 'SB Link Widget', 'enable_sb_link_widget_callback');
		$this->add_sbmodule_field('enable_sb_tab_widget', 'SB Tab Widget', 'enable_sb_tab_widget_callback');
		$this->add_sbmodule_field('enable_sb_banner_widget', 'SB Banner Widget', 'enable_sb_banner_widget_callback');

		$this->add_sbmodule_field('enable_3dfile', SB_WP::phrase('support_3d_functional'), 'enable_3dstl_callback');
		$this->add_sbmodule_field('enable_scroll_top', SB_WP::phrase('support_scroll_top_functional'), 'enable_scroll_top_callback');
		$this->add_sbmodule_field('enable_links_manager', SB_WP::phrase('support_link_functional'), 'enable_links_manager_callback');
		$this->add_sbmodule_field('enable_float_ads', SB_WP::phrase('float_ads'), 'enable_float_ads_callback');
		$this->add_sbmodule_field('enable_leaderboard_ads', SB_WP::phrase('leaderboard_ads'), 'enable_leaderboard_ads_callback');
		$this->add_sbmodule_field('enable_addthis', SB_WP::phrase('addthis_share_button'), 'enable_addthis_callback');
        $this->add_sbmodule_field('enable_user_point', SB_WP::phrase('user_point'), 'enable_user_point_callback');
	}
	
	// Thêm mục cho trang quản lý các tiện ích
	private function add_sbmodule_field($id, $title, $callback) {
		$this->add_field($id, $title, 'sbtheme_sbmodule_section', $callback);
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt chức năng Shop
	public function enable_shop_callback() {
		$this->set_switch_field('enable_shop', SB_PHP::add_dotted(SB_WP::phrase('switch_shop_functional')));
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt chức năng manga
	public function enable_manga_callback() {
		$this->set_switch_field('enable_manga', SB_PHP::add_dotted(SB_WP::phrase('switch_manga_functional')));
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt chức năng tivi
	public function enable_tivi_callback() {
		$this->set_switch_field('enable_tivi', SB_PHP::add_dotted(SB_WP::phrase('switch_tivi_functional')));
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt widget hiển thị bài viết
	public function enable_sb_post_widget_callback() {
		$this->set_switch_field('enable_sb_post_widget', SB_PHP::add_dotted(SB_WP::phrase('switch_sb_post_widget_functional')));
	}

    public function enable_sb_support_widget_callback() {
        $this->set_switch_field('enable_sb_support_widget', SB_PHP::add_dotted(SB_WP::phrase('switch_sb_support_widget_functional')));
    }

    public function enable_sb_link_widget_callback() {
        $this->set_switch_field('enable_sb_link_widget', SB_PHP::add_dotted(SB_WP::phrase('switch_sb_link_widget_functional')));
    }
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt widget hiển thị tab
	public function enable_sb_tab_widget_callback() {
		$this->set_switch_field('enable_sb_tab_widget', SB_PHP::add_dotted(SB_WP::phrase('switch_sb_tab_widget_functional')));
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt widget hiển thị banner
	public function enable_sb_banner_widget_callback() {
		$this->set_switch_field('enable_sb_banner_widget', SB_PHP::add_dotted(SB_WP::phrase('switch_sb_banner_widget_functional')));
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt chức năng upload tập tin .stl
	public function enable_3dstl_callback() {
		$this->set_switch_field('enable_3dfile', SB_PHP::add_dotted(SB_WP::phrase('switch_3d_functional')));
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt chức năng hiển thị nút quay về đầu trang
	public function enable_scroll_top_callback() {
		$this->set_switch_field('enable_scroll_top', SB_PHP::add_dotted(SB_WP::phrase('switch_scroll_top_functional')));
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt chức năng quản lý links
	public function enable_links_manager_callback() {
		$this->set_switch_field('enable_links_manager', SB_PHP::add_dotted(SB_WP::phrase('switch_link_manager')));
	}
	
	public function enable_float_ads_callback() {
		$this->set_switch_field('enable_float_ads', SB_WP::phrase('enable_float_ads_description'));
	}
	
	public function enable_leaderboard_ads_callback() {
		$this->set_switch_field('enable_leaderboard_ads', SB_WP::phrase('enable_leaderboard_ads_description'));
	}
	
	public function enable_addthis_callback() {
		$this->set_switch_field('enable_addthis', SB_WP::phrase('enable_addthis_description'));
	}

    public function enable_user_point_callback() {
        $this->set_switch_field('enable_user_point', SB_WP::phrase('enable_user_point_description'));
    }
	
	private function set_switch_field($name, $description) {
		$this->set_field($name, $description, 'switch');
	}
	
	private function switch_field($name, $value, $description) {
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
				<label data-switch="on" class="<?php echo $class_on; ?> left"><span><?php echo SB_Theme::phrase("on"); ?></span></label>
				<label data-switch="off" class="<?php echo $class_off; ?> right"><span><?php echo SB_Theme::phrase("off"); ?></span></label>
				<input type="hidden" value="<?php echo $value; ?>" name="<?php echo esc_attr($this->get_field_name($name)); ?>" id="<?php echo $name; ?>" class="checkbox checkbox-input">
				<p class="description"><?php echo $description; ?></p>
			</div>
		</fieldset>
		<?php
	}
	
	/*
	 * Tạo tab giới thiệu về SB Framework
	 */
	
	// Thêm trang hiển thị giới thiệu SB Framework
	private function add_aboutsb_setting() {
		$this->add_section('sbtheme_aboutsb_section', SB_WP::phrase('about_sb_framework'));
	}
	
	// Lấy name của mục
	public function get_field_name($name) {
		return $this->option_name . '['. $name .']';
	}
	
	// Lấy id của mục
	public function get_field_id($name) {
		return $name;
	}
	
	/*
	 * Gom dữ liệu cài đặt mới vào mảng
	 */
	public function sanitize( $input ) {
        $new_input = array();
		
		$new_input['language'] = $this->set_input_data($input, 'language');
        $new_input['logo'] = $this->set_input_data($input, 'logo', 'image');
        $new_input['footer_logo'] = $this->set_input_data($input, 'footer_logo', 'image');
        $new_input['favicon'] = $this->set_input_data($input, 'favicon', 'icon');
		$new_input['banner'] = $this->set_input_data($input, 'banner', 'image');
        $new_input['hotline_image'] = $this->set_input_data($input, 'hotline_image', 'image');
		$new_input['footer_text'] = $this->set_input_data($input, 'footer_text', 'html');
        $new_input['list_yahoo'] = $this->set_input_data($input, 'list_yahoo', 'html');
		
		$new_input['facebook'] = $this->set_input_data($input, 'facebook', 'url');
		$new_input['twitter'] = $this->set_input_data($input, 'twitter', 'url');
		$new_input['gplus'] = $this->set_input_data($input, 'gplus', 'url');
		$new_input['zingme'] = $this->set_input_data($input, 'zingme', 'url');
		$new_input['rss'] = $this->set_input_data($input, 'rss', 'url');
		$new_input['youtube'] = $this->set_input_data($input, 'youtube', 'url');
		$new_input['linkedin'] = $this->set_input_data($input, 'linkedin', 'url');
		$new_input['pinterest'] = $this->set_input_data($input, 'pinterest', 'url');
		
		$new_input['enable_shop'] = $this->set_input_data($input, 'enable_shop', 'bool-nummber');
		$new_input['enable_tivi'] = $this->set_input_data($input, 'enable_tivi', 'bool-nummber');

		$new_input['enable_sb_post_widget'] = $this->set_input_data($input, 'enable_sb_post_widget', 'bool-nummber');
        $new_input['enable_sb_support_widget'] = $this->set_input_data($input, 'enable_sb_support_widget', 'bool-nummber');
        $new_input['enable_sb_link_widget'] = $this->set_input_data($input, 'enable_sb_link_widget', 'bool-nummber');
		$new_input['enable_sb_tab_widget'] = $this->set_input_data($input, 'enable_sb_tab_widget', 'bool-nummber');
		$new_input['enable_sb_banner_widget'] = $this->set_input_data($input, 'enable_sb_banner_widget', 'bool-nummber');

		$new_input['enable_float_ads'] = $this->set_input_data($input, 'enable_float_ads', 'bool-nummber');
		$new_input['enable_leaderboard_ads'] = $this->set_input_data($input, 'enable_leaderboard_ads', 'bool-nummber');
		$new_input['enable_addthis'] = $this->set_input_data($input, 'enable_addthis', 'bool-nummber');
        $new_input['enable_user_point'] = $this->set_input_data($input, 'enable_user_point', 'bool-nummber');
        $new_input['enable_links_manager'] = $this->set_input_data($input, 'enable_links_manager', 'bool-nummber');
		
		$new_input['default_tivi'] = $this->set_input_data($input, 'default_tivi', 'int-nummber');
        $new_input['main_slider'] = $this->set_input_data($input, 'main_slider', 'html');
        $new_input['sub_slider'] = $this->set_input_data($input, 'sub_slider', 'html');

        $new_input['user_post_point'] = $this->set_input_data($input, 'user_post_point', 'nummber');
        $new_input['user_comment_point'] = $this->set_input_data($input, 'user_comment_point', 'nummber');
		
        return $new_input;
    }
	
	// Kiểm tra dữ liệu đầu vào
	private function set_input_data($input, $key, $type = "default") {
		$kq = '';
		if(isset($input[$key])) {
			switch($type) {
				case 'image':
					$data = sanitize_text_field( $input[$key] );
					if(SB_PHP::is_image_url($data)) {
						$kq = $data;
					}
					break;
				case 'icon':
					$data = sanitize_text_field( $input[$key] );
					if(SB_PHP::is_favicon_url($data)) {
						$kq = $data;
					}
					break;
                case 'html':
                    $kq = wpautop($input[$key]);
                    break;
				case 'text':
					$data = trim(sanitize_text_field( $input[$key] ));
					$kq = $data;
					break;
				case 'url':
					$data = esc_url_raw( $input[$key] );
					if(SB_PHP::is_url($data)) {
						$kq = $data;
					}
					break;
				case 'bool-nummber':
					$kq = absint($input[$key]);
					break;
				case 'int-number':
					$kq = absint($input[$key]);
					break;
                case 'number':
                    $kq = SB_PHP::get_input_number($input[$key]);
                    break;
				default:
					$kq = $input[$key];
			}
		}
		return $kq;
	}
	
	/*
	 * Tạo mục upload hình ảnh
	 */
	private function media_upload_field($name, $value, $description) {
		printf('<div class="sbtheme-upload media"><input type="text" id="%1s" name="%2s" value="%3s" /><a title="'.SB_WP::phrase("insert_image").'" data-editor="content" class="button insert-media add_media" id="insert-media-button" href="#">Upload</a></div><p class="description">%4s</p>', $name, esc_attr($this->get_field_name($name)), $value, $description);
	}
	
	// Khung đăng hình ảnh đã cài đặt
	private function media_image_thumbnail($class = "", $src = "") {
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
	
	// Mục hiển thị và cho upload hình ảnh
	private function media_image_upload_field($name, $value, $description) {
		echo '<div class="sbtheme-media-image">';
		$this->media_image_thumbnail($name, $value);
		$this->media_upload_field($name, $value, $description);
		echo '</div>';
	}
	
	// Gán giá trị cho mục hình ảnh
	private function set_media_image_field($name, $description) {
		$this->set_field($name, $description, 'media-image');
	}
	
	// Lấy giá trị từ option
	private function get_option_value($key) {
		return (isset($this->options[$key])) ? $this->options[$key] : '';
	}
	
	// Mục cho nhập dữ liệu chuỗi
	private function text_field($name, $value, $description) {
		$value = trim($value);
		printf('<input type="text" id="%1$s" name="%2$s" value="%3$s" class=""><p class="description">%4$s</p>', $name, esc_attr($this->get_field_name($name)), $value, $description);
	}

    private function number_field($name, $value, $description) {
        $value = trim($value);
        if("user_post_point" == $name && intval($value) < 1) {
            $value = SB_USER_POST_POINT;
        }
        if("user_comment_point" == $name && intval($value) < 1) {
            $value = SB_USER_COMMENT_POINT;
        }
        printf('<input type="number" id="%1$s" name="%2$s" value="%3$s" class=""><p class="description">%4$s</p>', $name, esc_attr($this->get_field_name($name)), $value, $description);
    }

    private function set_number_field($name, $description) {
        $this->set_field($name, $description, "number");
    }
	
	// Điều khiển gán giá trị cho mục nhất định
	private function set_field($name, $description, $type) {
		switch($type) {
			case 'social':
				$this->social_field($name, $this->get_option_value($name), $description);
				break;
			case 'media-image':
				$this->media_image_upload_field($name, $this->get_option_value($name), $description);
				break;
			case 'switch':
				$this->switch_field($name, $this->get_option_value($name), $description);
				break;
            case 'number':
                $this->number_field($name, $this->get_option_value($name), $description);
                break;
			case 'select':
				$this->select_field($name, $description);
				break;
		}
	}
	
	private function set_data_section() {
		$this->data_section = SB_PHP::get_session('data_section');
	}
	
	// Hiển thị và xử lý các tùy chọn
	function do_settings_sections( $page ) {
		global $wp_settings_sections, $wp_settings_fields;
		$this->set_data_section();
		 
		if ( ! isset( $wp_settings_sections[$page] ) ) {
			return;
		}
		$count = 0;
		foreach ( (array) $wp_settings_sections[$page] as $section ) {

			$section_id = $section['id'];
			$class = 'sbtheme-option-section'.$this->set_active_class($count, $section_id);
			echo '<div id="'.$section_id.'" class="'.$class.'">';
			
			if ( $section['title'] ) {
				echo "<h3>{$section['title']}</h3>\n";
			}
			 
			if ( $section['callback'] ) {
				call_user_func( $section['callback'], $section );
			}
			$has_field = true;
			if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section_id] ) ) {
				$has_field = false;
			}
			
			echo '<table class="form-table">';
				if($has_field && 'sbtheme_aboutsb_section' != $section_id) {
					do_settings_fields( $page, $section_id );
				} elseif('sbtheme_aboutsb_section' == $section_id) {
					$this->sbtheme_about_page();
				}
			echo '</table>';
			echo '</div>';
			$count++;
		}
	}
	
	private function sbtheme_about_page() {
		?>
		<div class="sbtheme-about">
			<div class="sb-logo"><img src="<?php echo SB_Theme::get_image('sb-framework-logo-300.png'); ?>"></div>
			<p class="sb-version"><?php echo SB_PHP::add_colon(SB_WP::phrase('version')); ?> <?php echo SB_VERSION; ?></p>
			<p><?php echo SB_WP::phrase("about_sb_framework_1"); ?></p>
			<p><?php echo SB_WP::phrase("about_sb_framework_2"); ?></p>
			<p><?php echo SB_WP::phrase("about_sb_framework_3"); ?></p>
			<p class="sb-donate"><?php SB_Theme::paypal_donate(); ?></p>
		</div>
		<?php
	}
	
	// Khởi tạo các trang cài đặt tùy chọn
	public function sbtheme_page_init() {
		register_setting( 'sbtheme_option', $this->option_name, array( $this, 'sanitize' ) );
		
		$this->add_general_setting();
		$this->add_ads_setting();
		$this->add_home_setting();
		$this->add_tivi_setting();
		$this->add_social_setting();
		
		$this->add_sbmodule_setting();
		$this->add_account_setting();
		$this->add_aboutsb_setting();
	}
	
	// Thêm mục vào một trang của tab
	public function add_field($id, $title, $section_id, $callback) {
		add_settings_field($id, $title, array( $this, $callback ), $this->menu_slug, $section_id);
	}
	
	// Tạo một trang nội dung cho tab
	public function add_section($id, $title) {
		add_settings_section($id, $title, array( $this, 'print_section_info' ), $this->menu_slug);
	}
	
	// Tạo menu con bên trong menu Appearance
	public function sbtheme_add_options_menu() {
		global $sb_language;
		add_submenu_page('themes.php', SB_WP::phrase("theme_settings"), 'SB Options', $this->capability, $this->menu_slug, array($this, 'sbtheme_settings_page'));
	}
	
	private function set_active_class($count, $section_id) {
		$class = '';
		$do_set = false;
		if((0 == $count && empty($this->data_section)) || (!empty($this->data_section) && $this->data_section == $section_id)) {
			$do_set = true;
		}
		if($do_set) {
			$class .= " active";
		}
		return $class;
	}
	
	private function option_page_header() {
		global $sb_language;
		?>
		<div class="sbtheme-header">
			<?php $theme = SB_WP::get_theme(); ?>
			<?php $name = SB_WP::get_theme_name($theme); ?>
			<?php
				if(empty($name)) {
					$name = SB_WP::phrase('no_name_theme');
				}
			?>
			<h2><?php echo $name; ?></h2>
			<?php $version = SB_WP::get_theme_version($theme); ?>
			<?php if(!empty($version)) : ?>
			<span><?php echo SB_WP::phrase('version'); ?> <?php echo $version; ?></span>
			<?php endif; ?>
		</div>
		<?php
	}
	
	// Nội dung trang options
	public function sbtheme_settings_page() {
		if (!current_user_can($this->capability)) {
			wp_die(SB_PHP::add_dotted(SB_WP::phrase('not_have_permission_to_edit_theme')));
		}
		$option = new SB_Option();
		$this->options = $option->get_all_option();
	?>
		<div class="wrap sb-option">
			<noscript><div class="no-js"><?php echo SB_PHP::add_exclamation_mark(SB_WP::phrase('no_javascript_text')); ?></div></noscript>
			<h2></h2>
			<?php if (isset($_GET["settings-updated"])) : ?>
				<div id="message" class="updated">
					<p><strong><?php _e(SB_PHP::add_dotted(SB_Theme::phrase("your_settings_saved"))); ?></strong></p>
				</div>
			<?php endif; ?>
			<div class="sbtheme-container">				
				<?php
				$this->option_page_header();
				$this->data_section = SB_PHP::get_session('data_section');
				?>
				<div class="sbtheme-content">
					<div class="sidebar">
						<ul class="sbtheme-list-section">
							<?php $count = 0; ?>
							<?php foreach($this->list_tabs as $key => $value) : ?>
							<?php $class = "section-item tab-".$key.$this->set_active_class($count, $value['section_id']); ?>
							<li class="<?php echo $class; ?>">
								<a class="sbtheme-group-tab" href="javascript:void(0);" data-section="<?php echo $value['section_id']; ?>"><i class="tab-icon <?php echo $key; ?>"></i> <span class="group-title"><?php echo $value['title']; ?></span></a>
							</li>
							<?php $count++; ?>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="main">
						<form method="post" action="options.php">
							<?php settings_fields( 'sbtheme_option' ); ?>
							<?php $this->do_settings_sections( $this->menu_slug ); ?>
							<?php submit_button(SB_Theme::phrase("save_changes")); ?>
							<div class="top-save-button"><?php submit_button(SB_Theme::phrase("save_changes")); ?></div>
						</form>
					</div>
				</div>
				<div class="sbtheme-footer">
					<div class="left"><p><?php echo SB_PHP::add_dotted(SB_WP::phrase("theme_created_by_sbteam")); ?> <?php echo SB_PHP::add_colon(SB_WP::phrase('send_mail_if_have_question')); ?> <em><?php echo SB_SUPPORT_EMAIL; ?></em></p></div>
					<div class="right">
						<ul class="sb-social-list">
							<li class="github"><a target="_blank" href="https://github.com/skylarkcob/sb"></a></li>
							<li class="facebook"><a target="_blank" href="https://www.facebook.com/Sauhicom"></a></li>
							<li class="twitter"><a target="_blank" href="https://twitter.com/skylarkcob"></a></li>
						</ul>
					</div>					
				</div>
				
			</div>
			
			<div class="sbtheme-copyright">
				<p>&copy; 2008 - <?php echo date('Y'); ?> <a href="http://hocwp.net">SB Team</a>. All Rights Reserved.</p>
			</div>
			
		</div>
	<?php
	}
	
	public function sbtheme_admin_ajax_callback() {
		$data_section = isset($_POST['data_section']) ? $_POST['data_section'] : '';
		SB_PHP::set_session('data_section', $data_section);
		die();
	}
	
	// Hiển thị dòng chú thích bên dưới tiêu đề trang
	public function print_section_info($args) {
		if('sbtheme_aboutsb_section' == $args['id']) {
			echo SB_PHP::add_dotted(SB_WP::phrase("sb_framework_shortdescription"));
		} else {
			echo SB_PHP::add_colon(SB_Theme::phrase("fill_your_settings_below"));
		}
    }
}
?>