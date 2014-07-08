<?php
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
		global $sb_language;
		$sb_language = new SB_Language();
		$this->data_section = SB_PHP::get_session('data_section');
		$this->add_tab('general', $sb_language->phrase("general_settings"), 'sbtheme_general_section');
		$this->add_tab('home', $sb_language->phrase("home_page_settings"), 'sbtheme_home_section');
		$this->add_tab('social', $sb_language->phrase("social_network_settings"), 'sbtheme_social_section');
		$this->add_tab('sbmodule', $sb_language->phrase("utility_management"), 'sbtheme_sbmodule_section');
		$this->add_tab('aboutsb', $sb_language->phrase("about_sb"), 'sbtheme_aboutsb_section');
	}
	
	/*
	 * Tạo cài đặt các thông tin chung
	 */
	
	// Thêm trang cài đặt thông tin chung
	private function add_general_setting() {
		global $sb_language;
		$this->add_section('sbtheme_general_section', 'Cài đặt chung cho giao diện');
		$this->add_general_field('language', $sb_language->phrase("choose_language"), "language_callback");
		$this->add_general_field('logo', 'Logo', 'logo_callback');
		$this->add_general_field('favicon', 'Favicon', 'favicon_callback');
		$this->add_general_field('banner', 'Banner', 'banner_callback');
	}
	
	// Thêm mục cho trang cài đặt thông tin chung
	private function add_general_field($id, $title, $callback) {
		$this->add_field($id, $title, 'sbtheme_general_section', $callback);
	}
	
	// Hàm hiển thị mục cài đặt logo
	public function logo_callback() {
		$this->set_media_image_field('logo', 'Bạn có thể điền vào đường dẫn hoặc upload logo mới.');
    }
	
	// Hàm hiển thị mục cài đặt favicon
	public function favicon_callback() {
		$this->set_media_image_field('favicon', 'Bạn có thể điền vào đường dẫn hoặc upload favicon mới. Chỉ áp dụng với hình ảnh PNG hoặc tập tin ICON');
    }
	
	// Hàm hiển thị mục cài đặt banner
	public function banner_callback() {
		$this->set_media_image_field('banner', 'Bạn có thể điền vào đường dẫn hoặc upload banner mới.');
	}
	
	public function language_callback() {
		global $sb_language;
		$this->set_select_field('language', $sb_language->phrase("choose_language_description"));
	}
	
	private function set_select_field($name, $description) {
		$this->set_field($name, $description, 'select');
	}
	
	private function select_field($name, $description) {
		global $sb_language;
		$langs = $sb_language->get_list();
		$value = $this->get_option_value($name);
		?>
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
		$this->add_section('sbtheme_home_section', 'Cài đặt hiển thị trang chủ');
	}
	
	/*
	 * Tạo cài đặt thông tin mạng xã hội
	 */
	 
	// Thêm trang cài đặt thông tin mạng xã hội
	private function add_social_setting() {
		$this->add_section('sbtheme_social_section', 'Cài đặt thông tin mạng xã hội');
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
		$this->set_social_field('facebook', 'Nhập vào đường dẫn đến trang Facebook của bạn.');
	}
	
	// Hàm hiển thị mục cài đặt Twitter
	public function twitter_callback() {
		$this->set_social_field('twitter', 'Nhập vào đường dẫn đến trang Twitter của bạn.');
	}
	
	// Hàm hiển thị mục cài đặt Google Plus
	public function gplus_callback() {
		$this->set_social_field('gplus', 'Nhập vào đường dẫn đến trang Google Plus của bạn.');
	}
	
	// Hàm hiển thị mục cài đặt Zing Me
	public function zingme_callback() {
		$this->set_social_field('zingme', 'Nhập vào đường dẫn đến trang Zing Me của bạn.');
	}
	
	// Hàm hiển thị mục cài đặt YouTube
	public function youtube_callback() {
		$this->set_social_field('youtube', 'Nhập vào đường dẫn đến trang YouTube của bạn.');
	}
	
	// Hàm hiển thị mục cài đặt Pinterest
	public function pinterest_callback() {
		$this->set_social_field('pinterest', 'Nhập vào đường dẫn đến trang Pinterest của bạn.');
	}
	
	// Hàm hiển thị mục cài đặt Linkedin
	public function linkedin_callback() {
		$this->set_social_field('linkedin', 'Nhập vào đường dẫn đến trang Linkedin của bạn.');
	}
	
	// Hàm hiển thị mục cài đặt RSS
	public function rss_callback() {
		$this->set_social_field('rss', 'Nhập vào đường dẫn đến trang RSS của bạn.');
	}
	
	/*
	 * Tạo cài đặt quản lý các gói tiện ích
	 */
	private function add_sbmodule_setting() {
		$this->add_section('sbtheme_sbmodule_section', 'Bật hoặc tắt các gói tiện ích kèm theo SB Framework');
		$this->add_sbmodule_field('enable_shop', 'Hỗ trợ trang Shop', 'enable_shop_callback');
		$this->add_sbmodule_field('enable_manga', 'Hỗ trợ trang Manga', 'enable_manga_callback');
		$this->add_sbmodule_field('enable_tivi', 'Hỗ trợ trang Tivi', 'enable_tivi_callback');
		$this->add_sbmodule_field('enable_sb_post_widget', 'SB Post Widget', 'enable_sb_post_widget_callback');
		$this->add_sbmodule_field('enable_sb_tab_widget', 'SB Tab Widget', 'enable_sb_tab_widget_callback');
		$this->add_sbmodule_field('enable_sb_banner_widget', 'SB Banner Widget', 'enable_sb_banner_widget_callback');
		$this->add_sbmodule_field('enable_3dfile', 'Đăng tập tin 3D', 'enable_3dstl_callback');
		$this->add_sbmodule_field('enable_scroll_top', 'Nút quay về đầu trang', 'enable_scroll_top_callback');
		$this->add_sbmodule_field('enable_links_manager', 'Quản lý Links', 'enable_links_manager_callback');
	}
	
	// Thêm mục cho trang quản lý các tiện ích
	private function add_sbmodule_field($id, $title, $callback) {
		$this->add_field($id, $title, 'sbtheme_sbmodule_section', $callback);
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt chức năng Shop
	public function enable_shop_callback() {
		$this->set_switch_field('enable_shop', 'Bật hoặc tắt chức năng làm trang Shop cho WordPress.');
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt chức năng manga
	public function enable_manga_callback() {
		$this->set_switch_field('enable_manga', 'Bật hoặc tắt chức năng làm trang Manga cho WordPress.');
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt chức năng tivi
	public function enable_tivi_callback() {
		$this->set_switch_field('enable_tivi', 'Bật hoặc tắt chức năng làm trang xem Tivi cho WordPress.');
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt widget hiển thị bài viết
	public function enable_sb_post_widget_callback() {
		$this->set_switch_field('enable_sb_post_widget', 'Bật hoặc tắt chức năng widget hiển thị bài viết SB Post Widget.');
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt widget hiển thị tab
	public function enable_sb_tab_widget_callback() {
		$this->set_switch_field('enable_sb_tab_widget', 'Bật hoặc tắt chức năng widget hiển thị tab SB Tab Widget.');
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt widget hiển thị banner
	public function enable_sb_banner_widget_callback() {
		$this->set_switch_field('enable_sb_banner_widget', 'Bật hoặc tắt chức năng widget hiển thị bài viết SB Banner Widget.');
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt chức năng upload tập tin .stl
	public function enable_3dstl_callback() {
		$this->set_switch_field('enable_3dfile', 'Bật hoặc tắt chức năng cho phép đăng tải tập tin 3D .stl.');
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt chức năng hiển thị nút quay về đầu trang
	public function enable_scroll_top_callback() {
		$this->set_switch_field('enable_scroll_top', 'Bật hoặc tắt chức năng cho phép hiển thị nút quay về đầu trang.');
	}
	
	// Hàm hiển thị mục cài đặt bật hoặc tắt chức năng quản lý links
	public function enable_links_manager_callback() {
		$this->set_switch_field('enable_links_manager', 'Bật hoặc tắt chức năng cho phép hiển thị trình quản lý links trên WordPress.');
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
		$this->add_section('sbtheme_aboutsb_section', 'Giới thiệu SB Framework');
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
        $new_input['favicon'] = $this->set_input_data($input, 'favicon', 'icon');		
		$new_input['banner'] = $this->set_input_data($input, 'banner', 'image');
		
		$new_input['facebook'] = $this->set_input_data($input, 'facebook', 'text');
		$new_input['twitter'] = $this->set_input_data($input, 'twitter', 'text');
		$new_input['gplus'] = $this->set_input_data($input, 'gplus', 'text');
		$new_input['zingme'] = $this->set_input_data($input, 'zingme', 'text');
		$new_input['rss'] = $this->set_input_data($input, 'rss', 'text');
		$new_input['youtube'] = $this->set_input_data($input, 'youtube', 'text');
		$new_input['linkedin'] = $this->set_input_data($input, 'linkedin', 'text');
		$new_input['pinterest'] = $this->set_input_data($input, 'pinterest', 'text');
		
		$new_input['enable_shop'] = $this->set_input_data($input, 'enable_shop', 'bool-nummber');
		
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
				case 'text':
					$data = sanitize_text_field( $input[$key] );
					if(SB_PHP::is_url($data)) {
						$kq = $data;
					}
					break;
				case 'bool-nummber':
					$kq = absint($input[$key]);
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
		printf('<div class="sbtheme-upload media"><input type="text" id="%1s" name="%2s" value="%3s" /><a title="Thêm hình ảnh" data-editor="content" class="button insert-media add_media" id="insert-media-button" href="#">Upload</a></div><p class="description">%4s</p>', $name, esc_attr($this->get_field_name($name)), $value, $description);
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
		printf('<input type="text" id="%1s" name="%2s" value="%3s" /><p class="description">%4s</p>', $name, esc_attr($this->get_field_name($name)), $value, $description);
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
			<p class="sb-version">Phiên bản: <?php echo SB_VERSION; ?></p>
			<p>SB Framework là bộ mã nguồn được thực hiện bởi SB Team, mục đích của gói phần mềm này là giúp việc lập trình WordPress trở nên dễ dàng hơn. Bạn có thể tải bản cập nhật mới nhất từ trên <a target="_blank" href="https://github.com/skylarkcob/sb">repository</a> của GitHub.</p>
			<p>SB Framework được thực hiện bởi <a target="_blank" href="https://github.com/skylarkcob">skylarkcob</a> và <a target="_blank" href="https://github.com/flyenuol">flyenuol</a>, mọi thắc mắc cũng như đóng góp xin vui lòng liên hệ qua địa chỉ email bên dưới hoặc gửi bài lên diễn đàn Học WordPress.</p>
			<p>Nếu bạn cảm thấy SB Framework có ích với bạn và muốn đóng góp một ít công sức để các lập trình viên phát triển tiếp mã nguồn này, xin vui lòng sử dụng nút ủng hộ qua PayPal bên dưới.</p>
			<p class="sb-donate"><?php SB_Theme::paypal_donate(); ?></p>
		</div>
		<?php
	}
	
	// Khởi tạo các trang cài đặt tùy chọn
	public function sbtheme_page_init() {
		register_setting( 'sbtheme_option', $this->option_name, array( $this, 'sanitize' ) );
		
		$this->add_general_setting();
		
		$this->add_home_setting();
		
		$this->add_social_setting();
		
		$this->add_sbmodule_setting();
		
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
		add_submenu_page('themes.php', $sb_language->phrase("theme_settings"), 'SBTheme Options', $this->capability, $this->menu_slug, array($this, 'sbtheme_settings_page'));
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
					$name = 'Giao diện chưa đặt tên';
				}
			?>
			<h2><?php echo $name; ?></h2>
			<?php $version = SB_WP::get_theme_version($theme); ?>
			<?php if(!empty($version)) : ?>
			<span><?php echo $sb_language->phrase('version'); ?> <?php echo $version; ?></span>
			<?php endif; ?>
		</div>
		<?php
	}
	
	// Nội dung trang options
	public function sbtheme_settings_page() {
		if (!current_user_can($this->capability)) {
			wp_die('Bạn không có quyền tùy chỉnh giao diện.');
		}
		$option = new SB_Option();
		$this->options = $option->get_all_option();
	?>
		<div class="wrap sb-option">
			<noscript><div class="no-js">Chức này tùy chỉnh giao diện sẽ không hoạt động nếu bạn không bật hỗ trợ Javascript!</div></noscript>
			<h2></h2>
			<?php if (isset($_GET["settings-updated"])) : ?>
				<div id="message" class="updated">
					<p><strong><?php _e(SB_Theme::phrase("your_settings_saved")) ?></strong></p>
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
						</form>
					</div>
				</div>
				<div class="sbtheme-footer">
					<div class="left"><p>Giao diện được thực hiện bởi SB Team. Mọi thắc mắc và đóng góp xin vui lòng liên hệ qua địa chỉ email: <em>laidinhcuongvn@gmail.com</em></p></div>
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
			echo 'Giới thiệu sơ lượt về SB Framework dành cho WordPress.';
		} else {
			echo SB_Theme::phrase("fill_your_settings_below");
		}
    }
}
?>