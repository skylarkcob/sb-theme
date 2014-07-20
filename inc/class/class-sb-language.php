<?php
class SB_Language {
	private $translators = array();
	private $languages = array();
	private $language = "vi";
	
	public function __construct($lang = "vi") {
		$this->init();
		$this->translator_init();
		if("vi" != $lang) {
			$this->language = $lang;
		} else {
			$options = SB_WP::option();
			if(isset($options['language'])) {
				$this->language = $options['language'];
			} else {
				SB_Option::update("language", "vi");
			}
		}
	}
	
	private function init() {
		$this->add("vi", "Tiếng Việt");
		$this->add("en", "English");
	}
	
	private function translator_init() {
		$this->overwrite_translator("vi", "on", "Bật");
		$this->overwrite_translator("en", "on", "On");
		
		$this->overwrite_translator("vi", "off", "Tắt");
		$this->overwrite_translator("en", "off", "Off");
		
		$this->overwrite_translator("vi", "theme_settings", "Cài đặt giao diện");
		$this->overwrite_translator("en", "theme_settings", "Theme Settings");
		
		$this->overwrite_translator("vi", "general_settings", "Thiết lập chung");
		$this->overwrite_translator("en", "general_settings", "General Settings");
		
		$this->overwrite_translator("vi", "home_page_settings", "Cài đặt trang chủ");
		$this->overwrite_translator("en", "home_page_settings", "Home page Settings");
		
		$this->overwrite_translator("vi", "social_network_settings", "Thông tin mạng xã hội");
		$this->overwrite_translator("en", "social_network_settings", "Social Settings");
		
		$this->overwrite_translator("vi", "utility_management", "Quản lý tiện ích");
		$this->overwrite_translator("en", "utility_management", "Utility Management");
		
		$this->overwrite_translator("vi", "about_sb", "Giới thiệu SB Framework");
		$this->overwrite_translator("en", "about_sb", "About SB Framework");
		
		$this->overwrite_translator("vi", "version", "Phiên bản");
		$this->overwrite_translator("en", "version", "Version");
		
		$this->overwrite_translator("vi", "choose_language_description", "Lựa chọn ngôn ngữ để sử dụng trên giao diện được tạo bởi SB Team");
		$this->overwrite_translator("en", "choose_language_description", "Choose language to use on SB Framework");
		
		$this->overwrite_translator("vi", "choose_language", "Lựa chọn ngôn ngữ");
		$this->overwrite_translator("en", "choose_language", "Choose language");
		
		$this->overwrite_translator("vi", "settings_saved", "Thiếp lập của bạn đã được lưu thành công");
		$this->overwrite_translator("en", "settings_saved", "Your settings have been saved successfully");
		
		$this->overwrite_translator("vi", "your_settings_saved", "Thiếp lập của bạn đã được lưu thành công");
		$this->overwrite_translator("en", "your_settings_saved", "Your settings have been saved successfully");
		
		$this->overwrite_translator("vi", "fill_your_settings_below", "Thiết lập thông tin cài đặt của bạn ở bên dưới");
		$this->overwrite_translator("en", "fill_your_settings_below", "Fill your settings below");
		
		$this->overwrite_translator("vi", "save_changes", "Lưu thiết lập");
		$this->overwrite_translator("en", "save_changes", "Save Changes");
		
		$this->overwrite_translator("vi", "right_sidebar_description", "Sidebar hiển thị phía bên phải màn hình");
		$this->overwrite_translator("en", "right_sidebar_description", "Sidebar that appears on the right screen");
		
		$this->overwrite_translator("vi", "left_sidebar_description", "Sidebar hiển thị phía bên trái màn hình");
		$this->overwrite_translator("en", "left_sidebar_description", "Sidebar that appears on the left screen");
		
		$this->overwrite_translator("vi", "main_sidebar_description", "Sidebar chính trên trang của bạn");
		$this->overwrite_translator("en", "main_sidebar_description", "Main sidebar on your website");
		
		$this->overwrite_translator("vi", "default_tivi_description", "Chọn kênh Tivi mặc định để hiển thị ngoài trang chủ");
		$this->overwrite_translator("en", "default_tivi_description", "Choose default television channel to display on home page");
		
		$this->overwrite_translator("vi", "default_tivi", "Kênh Tivi mặc định");
		$this->overwrite_translator("en", "default_tivi", "Default television channel");
		
		$this->overwrite_translator("vi", "choose_tivi_channel", "Lựa chọn kênh Tivi");
		$this->overwrite_translator("en", "choose_tivi_channel", "Choose television channel");
		
		$this->overwrite_translator("vi", "choose_category", "Chọn chuyên mục");
		$this->overwrite_translator("en", "choose_category", "Choose category");
		
		$this->overwrite_translator("vi", "new_post", "Bài viết mới");
		$this->overwrite_translator("en", "new_post", "New posts");
		
		$this->overwrite_translator("vi", "category", "Chuyên mục");
		$this->overwrite_translator("en", "category", "Category");
		
		$this->overwrite_translator("vi", "username", "Tên tài khoản");
		$this->overwrite_translator("en", "username", "Username");
		
		$this->overwrite_translator("vi", "login", "Đăng nhập");
		$this->overwrite_translator("en", "login", "Login");
		
		$this->overwrite_translator("vi", "log_in", "Đăng nhập");
		$this->overwrite_translator("en", "log_in", "Log in");
		
		$this->overwrite_translator("vi", "password", "Mật khẩu");
		$this->overwrite_translator("en", "password", "Password");
		
		$this->overwrite_translator("vi", "remember_me", "Nhớ đăng nhập");
		$this->overwrite_translator("en", "remember_me", "Remember me");
		
		$this->overwrite_translator("vi", "lost_your_password", "Quên mật khẩu");
		$this->overwrite_translator("en", "lost_your_password", "Lost your password");
		
		$this->overwrite_translator("vi", "back_to_home_page", "Quay lại trang chủ");
		$this->overwrite_translator("en", "back_to_home_page", "Back to home page");
		
		$this->overwrite_translator("vi", "register", "Đăng ký");
		$this->overwrite_translator("en", "register", "Register");
		
		$this->overwrite_translator("vi", "email", "Địa chỉ email");
		$this->overwrite_translator("en", "email", "E-mail");
		
		$this->overwrite_translator("vi", "you_are_now_logged_out", "Bạn đã đăng xuất khỏi hệ thống");
		$this->overwrite_translator("en", "you_are_now_logged_out", "You are now logged out");
		
		$this->overwrite_translator("vi", "get_new_password", "Nhận mật khẩu mới");
		$this->overwrite_translator("en", "get_new_password", "Get New Password");
		
		$this->overwrite_translator("vi", "username_or_email", "Tên tài khoản hoặc địa chỉ email");
		$this->overwrite_translator("en", "username_or_email", "Username or E-mail");
		
		$this->overwrite_translator("vi", "a_password_will_be_email_to_you", "Mật khẩu sẽ được chuyển đến email của bạn");
		$this->overwrite_translator("en", "a_password_will_be_email_to_you", "A password will be e-mailed to you");
		
		$this->overwrite_translator("vi", "most_comment_post", "Bài viết nhiều bình luận");
		$this->overwrite_translator("en", "most_comment_post", "Most comment posts");
		
		$this->overwrite_translator("vi", "enter_your_email_to_receive_new_password", "Điền vào địa chỉ email của bạn để nhận lại mật khẩu");
		$this->overwrite_translator("en", "enter_your_email_to_receive_new_password", "Please enter your email to receive password");
		
		$this->overwrite_translator("vi", "random_post", "Bài viết ngẫu nhiên");
		$this->overwrite_translator("en", "random_post", "Random posts");
		
		$this->overwrite_translator("vi", "login_failed", "Đăng nhập thất bại");
		$this->overwrite_translator("en", "login_failed", "Login failed");
		
		$this->overwrite_translator("vi", "error", "Lỗi");
		$this->overwrite_translator("en", "error", "Error");
		
		$this->overwrite_translator("vi", "please_enter_your_account_correctly", "Xin vui lòng nhập đúng tên tài khoản và mật khẩu của bạn");
		$this->overwrite_translator("en", "please_enter_your_account_correctly", "Please enter your account correctly");
		
		$this->overwrite_translator("vi", "please_enter_your_email_correctly", "Xin vui lòng nhập chính xác địa chỉ email");
		$this->overwrite_translator("en", "please_enter_your_email_correctly", "Please enter your email address correctly");
		
		$this->overwrite_translator("vi", "you_do_not_have_permission_to_register", "Chức năng đăng ký tài khoản hiện đang tắt");
		$this->overwrite_translator("en", "you_do_not_have_permission_to_register", "User registration is currently not allowed");
		
		$this->overwrite_translator("vi", "you_do_not_have_permission_to_register", "Chức năng đăng ký tài khoản hiện đang tắt");
		$this->overwrite_translator("en", "you_do_not_have_permission_to_register", "User registration is currently not allowed");
		
		$this->overwrite_translator("vi", "register_for_this_site", "Đăng ký làm thành viên");
		$this->overwrite_translator("en", "register_for_this_site", "Register for this site");
		
		$this->overwrite_translator("vi", "please_check_your_information", "Xin vui lòng kiểm tra lại thông tin");
		$this->overwrite_translator("en", "please_check_your_information", "Please check your information");
		
		$this->overwrite_translator("vi", "most_view_post", "Bài viết được xem nhiều");
		$this->overwrite_translator("en", "most_view_post", "Most view posts");
		
		$this->overwrite_translator("vi", "post_by_category", "Bài viết theo chuyên mục");
		$this->overwrite_translator("en", "post_by_category", "Posts in category");
		
		$this->overwrite_translator("vi", "most_like_post", "Bài viết được yêu thích");
		$this->overwrite_translator("en", "most_like_post", "Most like posts");
		
		$this->overwrite_translator("vi", "favorite_post", "Bài viết được đánh dấu");
		$this->overwrite_translator("en", "favorite_post", "Favorite posts");
		
		$this->overwrite_translator("vi", "show_post_on_sidebar", "Hiển thị bài viết trên sidebar");
		$this->overwrite_translator("en", "show_post_on_sidebar", "Show post on sidebar");
		
		$this->overwrite_translator("vi", "only_show_thumbnail", "Chỉ hiển thị hình ảnh");
		$this->overwrite_translator("en", "only_show_thumbnail", "Only show post's thumbnail");
		
		$this->overwrite_translator("vi", "excerpt_length", "Độ dài của đoạn chữ tóm tắt");
		$this->overwrite_translator("en", "excerpt_length", "Excerpt length");
		
		$this->overwrite_translator("vi", "image_size", "Kích thước ảnh");
		$this->overwrite_translator("en", "image_size", "Image size");
		
		$this->overwrite_translator("vi", "post_information", "Thông tin bài viết");
		$this->overwrite_translator("en", "post_information", "Post information");
		
		$this->overwrite_translator("vi", "show_author", "Hiển thị tác giả");
		$this->overwrite_translator("en", "show_author", "Show author");
		
		$this->overwrite_translator("vi", "show_date", "Hiển thị ngày tháng");
		$this->overwrite_translator("en", "show_date", "Show date");
		
		$this->overwrite_translator("vi", "comment", "Bình luận");
		$this->overwrite_translator("en", "comment", "Comment");
		
		$this->overwrite_translator("vi", "comments", "Bình luận");
		$this->overwrite_translator("en", "comments", "Comments");
		
		$this->overwrite_translator("vi", "show_comment_count", "Hiển thị số bình luận");
		$this->overwrite_translator("en", "show_comment_count", "Show comment count");
		
		$this->overwrite_translator("vi", "tivi_settings", "Cài đặt cho trang Tivi");
		$this->overwrite_translator("en", "tivi_settings", "Television settings");
		
		$this->overwrite_translator("vi", "ads_settings", "Cài đặt quảng cáo");
		$this->overwrite_translator("en", "ads_settings", "Ads management");
		
		$this->overwrite_translator("vi", "ads_settings_description", "Cài đặt các quảng cáo hiển thị trên trang");
		$this->overwrite_translator("en", "ads_settings_description", "Manage ads banner on your website");
		
		$this->overwrite_translator("vi", "tivi_settings_description", "Cài đặt thông tin quản lý kênh Tivi");
		$this->overwrite_translator("en", "tivi_settings_description", "Setup information for television website");
		
		$this->overwrite_translator("vi", "content_sidebar_description", "Sidebar hiển thị widget trên trang nội dung");
		$this->overwrite_translator("en", "content_sidebar_description", "Sidbar shows widgets on content");
		
		$this->overwrite_translator("vi", "category_type", "Chọn kiểu chuyên mục");
		$this->overwrite_translator("en", "category_type", "Choose category type");
		
		$this->overwrite_translator("vi", "show_banner_on_sidebar", "Hiển thị banner trên sidebar");
		$this->overwrite_translator("en", "show_banner_on_sidebar", "Show banner on sidebar");
		
		$this->overwrite_translator("vi", "tab_widget_description", "Hiển thị tab chứa các widget con");
		$this->overwrite_translator("en", "tab_widget_description", "Show widget as tabber");
		
		$this->overwrite_translator("vi", "you_do_not_choose_sidebar", "Bạn chưa chọn sidebar để hiển thị");
		$this->overwrite_translator("en", "you_do_not_choose_sidebar", "You haven't been choosen sidebar yet");
		
		$this->overwrite_translator("vi", "choose_sidebar", "Chọn sidebar");
		$this->overwrite_translator("en", "choose_sidebar", "Choose sidebar");
		
		$this->overwrite_translator("vi", "do_not_choose_sidebar_contain_widget", "Không được chọn sidebar đang chứa widget này, nếu không widget sẽ không hiển thị");
		$this->overwrite_translator("en", "do_not_choose_sidebar_contain_widget", "Do not choose sidebar that contains this widget or it will not be shown");
		
		$this->overwrite_translator("vi", "please_put_widget_into_sidebar", "Xin vui lòng đặt widget vào bên trong sidebar");
		$this->overwrite_translator("en", "please_put_widget_into_sidebar", "Please put your widget into sidebar");
		
		$this->overwrite_translator("vi", "post_number", "Số lượng bài viết");
		$this->overwrite_translator("en", "post_number", "Post numbers");
		
		$this->overwrite_translator("vi", "get_post_by", "Lấy bài viết theo");
		$this->overwrite_translator("en", "get_post_by", "Get posts by");
		
		$this->overwrite_translator("vi", "show_excerpt", "Hiển thị tóm tắt bài viết");
		$this->overwrite_translator("en", "show_excerpt", "Show excerpt");
		
		$this->overwrite_translator("vi", "title", "Tiêu đề");
		$this->overwrite_translator("en", "title", "Title");
		
		$this->overwrite_translator("vi", "banner_image_url", "Đường link của hỉnh ảnh banner");
		$this->overwrite_translator("en", "banner_image_url", "Banner image url");
		
		$this->overwrite_translator("vi", "banner_url", "Đường link của banner");
		$this->overwrite_translator("en", "banner_url", "Banner image link");
		
		$this->overwrite_translator("vi", "insert_image", "Thêm hình ảnh");
		$this->overwrite_translator("en", "insert_image", "Insert image");
		
		$this->overwrite_translator("vi", "leaderboard_banner_description", "Quảng cáo leaderboard");
		$this->overwrite_translator("en", "leaderboard_banner_description", "Leaderboard banner ads");
		
		$this->overwrite_translator("vi", "footer_text", "Ghi chú dưới footer");
		$this->overwrite_translator("en", "footer_text", "Footer text");
		
		$this->overwrite_translator("vi", "footer_text_description", "Dòng chữ giới thiệu về trang web hoặc copyright bên dưới footer");
		$this->overwrite_translator("en", "footer_text_description", "The text about your site or copyright on footer");
		
		$this->overwrite_translator("vi", "float_ads_left_description", "Quảng cáo trượt bên trái trang");
		$this->overwrite_translator("en", "float_ads_left_description", "Float ads on the left site");
		
		$this->overwrite_translator("vi", "float_ads_right_description", "Quảng cáo trượt bên phải trang");
		$this->overwrite_translator("en", "float_ads_right_description", "Float ads on the right site");
		
		$this->overwrite_translator("vi", "float_ads", "Quảng cáo 2 bên");
		$this->overwrite_translator("en", "float_ads", "Float ads");
		
		$this->overwrite_translator("vi", "addthis_share_button", "Nút chia sẻ AddThis");
		$this->overwrite_translator("en", "addthis_share_button", "AddThis share button");
		
		$this->overwrite_translator("vi", "enable_addthis_description", "Bất hoặc tắt chức năng hiển thị nút chia sẻ AddThis");
		$this->overwrite_translator("en", "enable_addthis_description", "Turn on or turn off AddThis share button");
		
		$this->overwrite_translator("vi", "leaderboard_ads", "Quảng cáo leaderboard");
		$this->overwrite_translator("en", "leaderboard_ads", "Leaderboard ads");
		
		$this->overwrite_translator("vi", "enable_leaderboard_ads_description", "Bật hoặc tắt quảng cáo trên header");
		$this->overwrite_translator("en", "enable_leaderboard_ads_description", "Turn on or turn off leaderboard ads");
		
		$this->overwrite_translator("vi", "enable_float_ads_description", "Bật hoặc tắt chức năng cho phép hiển thị quảng cáo trượt 2 bên trang");
		$this->overwrite_translator("en", "enable_float_ads_description", "Turn on or turn off options to display float ads");
		
		$this->overwrite_translator("vi", "float_ads_description", "Quảng cáo trượt 2 bên trang");
		$this->overwrite_translator("en", "float_ads_description", "Ads floats on left and right of the site");
		
		$this->overwrite_translator("vi", "order_by", "Sắp xếp theo");
		$this->overwrite_translator("en", "order_by", "Order by");
		
		$this->overwrite_translator("vi", "home_setting", "Cài đặt hiển thị trang chủ");
		$this->overwrite_translator("en", "home_setting", "Home settings");
		
		$this->overwrite_translator("vi", "social_setting", "Cài đặt thông tin mạng xã hội");
		$this->overwrite_translator("en", "social_setting", "Social settings");
		
		$this->overwrite_translator("vi", "input_facebook_url", "Nhập vào đường dẫn đến trang Facebook của bạn");
		$this->overwrite_translator("en", "input_facebook_url", "Enter your Facebook account url");
		
		$this->overwrite_translator("vi", "input_twitter_url", "Nhập vào đường dẫn đến trang Twitter của bạn");
		$this->overwrite_translator("en", "input_twitter_url", "Enter your Twitter account url");
		
		$this->overwrite_translator("vi", "input_gplus_url", "Nhập vào đường dẫn đến trang Google Plus của bạn");
		$this->overwrite_translator("en", "input_gplus_url", "Enter your Google Plus account url");
		
		$this->overwrite_translator("vi", "input_zingme_url", "Nhập vào đường dẫn đến trang Zing Me của bạn");
		$this->overwrite_translator("en", "input_zingme_url", "Enter your Zing Me account url");
		
		$this->overwrite_translator("vi", "input_youtube_url", "Nhập vào đường dẫn đến trang YouTube của bạn");
		$this->overwrite_translator("en", "input_youtube_url", "Enter your YouTube account url");
		
		$this->overwrite_translator("vi", "input_pinterest_url", "Nhập vào đường dẫn đến trang Pinterest của bạn");
		$this->overwrite_translator("en", "input_pinterest_url", "Enter your Pinterest account url");
		
		$this->overwrite_translator("vi", "input_linkedin_url", "Nhập vào đường dẫn đến trang Linkedin của bạn");
		$this->overwrite_translator("en", "input_linkedin_url", "Enter your Linkedin account url");
		
		$this->overwrite_translator("vi", "input_rss_url", "Nhập vào đường dẫn đến trang RSS của bạn");
		$this->overwrite_translator("en", "input_rss_url", "Enter your site RSS url");
		
		$this->overwrite_translator("vi", "post_date", "Ngày đăng bài viết");
		$this->overwrite_translator("en", "post_date", "Post date");
		
		$this->overwrite_translator("vi", "order_desc", "Sắp xếp giảm dần");
		$this->overwrite_translator("en", "order_desc", "Order DESC");
		
		$this->overwrite_translator("vi", "order_asc", "Sắp xếp tăng dần");
		$this->overwrite_translator("en", "order_asc", "Order ASC");
		
		$this->overwrite_translator("vi", "order_type", "Kiểu sắp xếp");
		$this->overwrite_translator("en", "order_type", "Order type");
		
		$this->overwrite_translator("vi", "show_title", "Hiển thị tiêu đề");
		$this->overwrite_translator("en", "show_title", "Show title");
		
		$this->overwrite_translator("vi", "sb_framework_utility_management", "Bật hoặc tắt các gói tiện ích kèm theo SB Framework");
		$this->overwrite_translator("en", "sb_framework_utility_management", "SB Framework Utilities Management");
		
		$this->overwrite_translator("vi", "sb_framework_short_description", "Giới thiệu sơ lượt về SB Framework dành cho WordPress");
		$this->overwrite_translator("en", "sb_framework_short_description", "Short descriptions about SB Framework for WordPress");
		
		$this->overwrite_translator("vi", "general_setting_description", "Cài đặt chung cho giao diện");
		$this->overwrite_translator("en", "general_setting_description", "General settings for your site");
		
		$this->overwrite_translator("vi", "input_url_or_upload_new_logo", "Bạn có thể điền vào đường dẫn hoặc upload logo mới");
		$this->overwrite_translator("en", "input_url_or_upload_new_logo", "You can type url or upload new logo file");
		
		$this->overwrite_translator("vi", "input_url_or_upload_new_favicon", "Bạn có thể điền vào đường dẫn hoặc upload favicon mới");
		$this->overwrite_translator("en", "input_url_or_upload_new_favicon", "You can type url or upload new favicon file");
		
		$this->overwrite_translator("vi", "input_url_or_upload_new_banner", "Bạn có thể điền vào đường dẫn hoặc upload banner mới");
		$this->overwrite_translator("en", "input_url_or_upload_new_banner", "You can type url or upload new banner file");
		
		$this->overwrite_translator("vi", "theme_created_by_sbteam", "Giao diện được tạo bởi SB Team");
		$this->overwrite_translator("en", "theme_created_by_sbteam", "Theme is created by SB Team");
		
		$this->overwrite_translator("vi", "send_mail_if_have_question", "Mọi thắc mắc và đóng góp xin vui lòng liên hệ qua địa chỉ email");
		$this->overwrite_translator("en", "send_mail_if_have_question", "If you have any questions or feedback, please send mail via");
		
		$this->overwrite_translator("vi", "no_javascript_text", "Các chức năng có sử dụng javascript sẽ không hoạt động nếu trình duyệt của bạn không hỗ trợ");
		$this->overwrite_translator("en", "no_javascript_text", "Any functions are using javascript will be crashed if your browser doesn't support it");
		
		$this->overwrite_translator("vi", "not_have_permission_to_edit_theme", "Bạn không có quyền tùy chỉnh giao diện");
		$this->overwrite_translator("en", "not_have_permission_to_edit_theme", "You don't have permissions to edit theme");
		
		$this->overwrite_translator("vi", "switch_shop_functional", "Bật hoặc tắt chức năng làm trang Shop cho WordPress");
		$this->overwrite_translator("en", "switch_shop_functional", "Turn on or turn off shop functional for WordPress");
		
		$this->overwrite_translator("vi", "switch_manga_functional", "Bật hoặc tắt chức năng làm trang Manga cho WordPress");
		$this->overwrite_translator("en", "switch_manga_functional", "Turn on or turn off manga functional for WordPress");
		
		$this->overwrite_translator("vi", "switch_tivi_functional", "Bật hoặc tắt chức năng làm trang xem Tivi cho WordPress");
		$this->overwrite_translator("en", "switch_tivi_functional", "Turn on or turn off television functional for WordPress");
		
		$this->overwrite_translator("vi", "switch_sb_post_widget_functional", "Bật hoặc tắt chức năng widget hiển thị bài viết SB Post Widget");
		$this->overwrite_translator("en", "switch_sb_post_widget_functional", "Turn on or turn off SB Post Widget to display posts on sidebar");
		
		$this->overwrite_translator("vi", "switch_sb_tab_widget_functional", "Bật hoặc tắt chức năng widget hiển thị tab SB Tab Widget");
		$this->overwrite_translator("en", "switch_sb_tab_widget_functional", "Turn on or turn off SB Tab Widget to display widget as tabber on sidebar");
		
		$this->overwrite_translator("vi", "switch_sb_banner_widget_functional", "Bật hoặc tắt chức năng widget hiển thị bài viết SB Banner Widget");
		$this->overwrite_translator("en", "switch_sb_banner_widget_functional", "Turn on or turn off SB Banner Widget to display banner image on sidebar");
		
		$this->overwrite_translator("vi", "switch_3d_functional", "Bật hoặc tắt chức năng cho phép đăng tải tập tin 3D .stl");
		$this->overwrite_translator("en", "switch_3d_functional", "Turn on or turn off functional to post 3D file on WordPress site");
		
		$this->overwrite_translator("vi", "switch_scroll_top_functional", "Bật hoặc tắt chức năng cho phép hiển thị nút quay về đầu trang");
		$this->overwrite_translator("en", "switch_scroll_top_functional", "Turn on or turn off functional to display scroll top button");
		
		$this->overwrite_translator("vi", "support_shop_functional", "Hỗ trợ trang Shop");
		$this->overwrite_translator("en", "support_shop_functional", "Support shop");
		
		$this->overwrite_translator("vi", "support_manga_functional", "Hỗ trợ trang Manga");
		$this->overwrite_translator("en", "support_manga_functional", "Support manga");
		
		$this->overwrite_translator("vi", "support_tivi_functional", "Hỗ trợ trang Tivi");
		$this->overwrite_translator("en", "support_tivi_functional", "Support television");
		
		$this->overwrite_translator("vi", "support_3d_functional", "Đăng tập tin 3D");
		$this->overwrite_translator("en", "support_3d_functional", "Support 3D file");
		
		$this->overwrite_translator("vi", "support_scroll_top_functional", "Nút quay về đầu trang");
		$this->overwrite_translator("en", "support_scroll_top_functional", "Scroll top button");
		
		$this->overwrite_translator("vi", "support_link_functional", "Quản lý Links");
		$this->overwrite_translator("en", "support_link_functional", "Links management");
		
		$this->overwrite_translator("vi", "no_name_theme", "Giao diện chưa đặt tên");
		$this->overwrite_translator("en", "no_name_theme", "No name theme");
		
		$this->overwrite_translator("vi", "about_sb_framework", "Giới thiệu SB Framework");
		$this->overwrite_translator("en", "about_sb_framework", "About SB Framework");
		
		$this->overwrite_translator("vi", "switch_link_manager", "Bật hoặc tắt chức năng cho phép hiển thị trình quản lý links trên WordPress");
		$this->overwrite_translator("en", "switch_link_manager", "Turn on or turn off WordPress Links management");
		
		$this->overwrite_translator("vi", "about_sb_framework_1", 'SB Framework là bộ mã nguồn được thực hiện bởi SB Team, mục đích của gói phần mềm này là giúp việc lập trình WordPress trở nên dễ dàng hơn. Bạn có thể tải bản cập nhật mới nhất từ trên <a target="_blank" href="https://github.com/skylarkcob/sb">repository</a> của GitHub.');
		$this->overwrite_translator("en", "about_sb_framework_1", 'SB Framework is a PHP framework that created by SB Team, the purposes of this framework are to help coding WordPress more easier. You can download the latest version from <a target="_blank" href="https://github.com/skylarkcob/sb">SB\'s GitHub Repository</a>.');
		
		$this->overwrite_translator("vi", "about_sb_framework_2", 'SB Framework được thực hiện bởi <a target="_blank" href="https://github.com/skylarkcob">skylarkcob</a> và <a target="_blank" href="https://github.com/flyenuol">flyenuol</a>, mọi thắc mắc cũng như đóng góp xin vui lòng liên hệ qua địa chỉ email bên dưới hoặc gửi bài lên diễn đàn Học WordPress.');
		$this->overwrite_translator("en", "about_sb_framework_2", 'SB Framework is writen by <a target="_blank" href="https://github.com/skylarkcob">skylarkcob</a> and <a target="_blank" href="https://github.com/flyenuol">flyenuol</a>, if you have any questions please send mail via the email address below this panel or write a post on learn WordPress forum.');
		
		$this->overwrite_translator("vi", "about_sb_framework_3", 'Nếu bạn cảm thấy SB Framework có ích với bạn và muốn đóng góp một ít công sức để các lập trình viên phát triển tiếp mã nguồn này, xin vui lòng sử dụng nút ủng hộ qua PayPal bên dưới.');
		$this->overwrite_translator("en", "about_sb_framework_3", 'If you feel this framework helpful, you can use the PayPal donate button below to send a beer or a coffee cup to these founders. It will help us to create more functional on this framework.');
	}
	
	
	public function set($lang) {
		if($this->exists($lang)) {
			$this->language = $lang;
		}
	}
	
	public function exists($lang) {
		foreach($this->languages as $key => $value) {
			if($lang == $key) {
				return true;
			}
		}
		return false;
	}
	
	
	
	public function add($lang_code, $lang_title) {
		if(!in_array($lang_code, $this->languages)) {
			$this->languages[$lang_code] = $lang_title;
		}
	}
	
	
	
	public function add_text($lang, $phrase, $text) {
		if(!$this->translator_exists($phrase)) {
			$this->overwrite_text($lang, $phrase, $text);
		}
	}
	
	public function add_translator($lang, $phrase, $text) {
		$this->add_text($lang, $phrase, $text);
	}
	
	public function overwrite_text($lang, $phrase, $text) {
		$this->translators[$lang][$phrase] = $text;
	}
	
	public function overwrite_translator($lang, $phrase, $text) {
		$this->overwrite_text($lang, $phrase, $text);
	}
	
	public function translator_exists($phrase) {
		foreach($this->translators as $key => $value) {
			if(array_key_exists($phrase, $value)) {
				return true;
			}
		}
		return false;
	}
	
	public function text_exists($phrase) {
		return $this->translator_exists($phrase);
	}
	
	public function phrase_exists($phrase) {
		return $this->text_exists($phrase);
	}
	
	public function phrase($phrase) {
		$text = '';
		if($this->phrase_exists($phrase)) {
			$text = $this->translators[$this->language][$phrase];
		}
		return $text;
	}
	
	public function get() {
		return $this->language;
	}
	
	public function get_array() {
		return $this->translators;
	}
	
	public function get_list() {
		return $this->languages;
	}
}
?>