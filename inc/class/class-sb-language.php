<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SB_Language {
	private $translators = array();
	private $languages = array();
	private $language = "vi";
	
	public function __construct($lang = "") {
        if(empty($lang)) {
            $lang = SB_WP::get_current_language();
        }
		$this->init();
		$this->translator_init();
		$this->language = $lang;
        if(defined("SB_LANGUAGE") && in_array(SB_LANGUAGE, $this->languages)) {
            $this->language = SB_LANGUAGE;
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

        $this->overwrite_translator("vi", "create_post_url", "Link tạo bài viết");
        $this->overwrite_translator("en", "create_post_url", "Create post url");

        $this->overwrite_translator("vi", "register_url", "Link đăng ký");
        $this->overwrite_translator("en", "register_url", "Registration url");

        $this->overwrite_translator("vi", "related_posts", "Bài viết liên quan");
        $this->overwrite_translator("en", "related_posts", "Related posts");

        $this->overwrite_translator("vi", "you_can_only_use_x_image_in_post", 'Bạn chỉ có thể được phép chèn %s hình ảnh vào bài viết');
        $this->overwrite_translator("en", "you_can_only_use_x_image_in_post", 'You can only be allowed to insert %s image in post');

        $this->overwrite_translator("vi", "your_post_content_must_be_at_least_x_character", 'Nội dung bài viết của bạn ít nhất phải chứa %s ký tự');
        $this->overwrite_translator("en", "your_post_content_must_be_at_least_x_character", 'Your post content must be at least %s character');

        $this->overwrite_translator("vi", "create_post_url_setting_description", "Lựa chọn trang cho người dùng đăng bài viết mới");
        $this->overwrite_translator("en", "create_post_url_setting_description", "Choose the page for user to create new post");

        $this->overwrite_translator("vi", "account_setting_page", "Cài đặt thông tin cho tài khoản");
        $this->overwrite_translator("en", "account_setting_page", "Settings for user account");

        $this->overwrite_translator("vi", "user_post_point", "Điểm cho bài viết");
        $this->overwrite_translator("en", "user_post_point", "Point for writing post");

        $this->overwrite_translator("vi", "show_admin_bar", "Hiển thị thanh quản lý");
        $this->overwrite_translator("en", "show_admin_bar", "Show admin bar");

        $this->overwrite_translator("vi", "show_admin_bar_setting_description", "Hiển thị hoặc ẩn thanh quản lý bên ngoài giao diện của website");
        $this->overwrite_translator("en", "show_admin_bar_setting_description", "Show or hide admin bar from front end");

        $this->overwrite_translator("vi", "after_saved_post_text", "Chúng tôi sẽ xem xét và đăng tin của bạn nếu bài viết thỏa mãn yêu cầu về mặt nội dung trên website");
        $this->overwrite_translator("en", "after_saved_post_text", "We will review and publish your post if the article is satisfactory in terms of content on websites");

        $this->overwrite_translator("vi", "user_post_point_setting_description", "Điểm cho thành viên khi người dùng tạo bài viết mới");
        $this->overwrite_translator("en", "user_post_point_setting_description", "The number of point for user when they write a post");
		
		$this->overwrite_translator("vi", "first_name", "Tên");
		$this->overwrite_translator("en", "first_name", "First name");

        $this->overwrite_translator("vi", "please_enter_post_title", "Xin vui lòng nhập tiêu đề bài viết");
        $this->overwrite_translator("en", "please_enter_post_title", "Please enter post title");

        $this->overwrite_translator("vi", "please_enter_post_content", "Xin vui lòng nhập nội dung bài viết");
        $this->overwrite_translator("en", "please_enter_post_content", "Please enter post content");

        $this->overwrite_translator("vi", "reply_comment_to_x", 'Trả lời bình luận của %s');
        $this->overwrite_translator("en", "reply_comment_to_x", 'Reply to %s');

        $this->overwrite_translator("vi", "you_must", "Bạn phải");
        $this->overwrite_translator("en", "you_must", "You must");

        $this->overwrite_translator("vi", "thank_you_your_post_saved", "Cảm ơn bạn, bài viết của bạn đã được lưu thành công");
        $this->overwrite_translator("en", "thank_you_your_post_saved", "Thank you, your post has been saved successfully");

        $this->overwrite_translator("vi", "before_leave_a_comment", "Trước khi gửi bình luận");
        $this->overwrite_translator("en", "before_leave_a_comment", "Before leave a comment");

        $this->overwrite_translator("vi", "user_comment_point", "Điểm cho bình luận");
        $this->overwrite_translator("en", "user_comment_point", "Point for writing comment");

        $this->overwrite_translator("vi", "user_comment_point_setting_description", "Điểm cho thành viên khi người dùng gửi bình luận");
        $this->overwrite_translator("en", "user_comment_point_setting_description", "The number of point for user when they write a comment");

        $this->overwrite_translator("vi", "title_length", "Độ dài tiêu đề");
        $this->overwrite_translator("en", "title_length", "Title length");
		
		$this->overwrite_translator("vi", "last_name", "Họ");
		$this->overwrite_translator("en", "last_name", "Last name");

        $this->overwrite_translator("vi", "account", "Tài khoản");
        $this->overwrite_translator("en", "account", "Account");

        $this->overwrite_translator("vi", "ago", "Trước");
        $this->overwrite_translator("en", "ago", "Ago");

        $this->overwrite_translator("vi", "second", "Giây");
        $this->overwrite_translator("en", "second", "Second");

        $this->overwrite_translator("vi", "seconds", "Giây");
        $this->overwrite_translator("en", "seconds", "Seconds");

        $this->overwrite_translator("vi", "minute", "Phút");
        $this->overwrite_translator("en", "minute", "Minute");

        $this->overwrite_translator("vi", "minutes", "Phút");
        $this->overwrite_translator("en", "minutes", "Minutes");

        $this->overwrite_translator("vi", "hour", "Giờ");
        $this->overwrite_translator("en", "hour", "Hour");

        $this->overwrite_translator("vi", "hours", "Giờ");
        $this->overwrite_translator("en", "hours", "Hours");

        $this->overwrite_translator("vi", "day", "Ngày");
        $this->overwrite_translator("en", "day", "Day");

        $this->overwrite_translator("vi", "days", "Ngày");
        $this->overwrite_translator("en", "days", "Days");

        $this->overwrite_translator("vi", "week", "Tuần");
        $this->overwrite_translator("en", "week", "Week");

        $this->overwrite_translator("vi", "weeks", "Tuần");
        $this->overwrite_translator("en", "weeks", "Weeks");

        $this->overwrite_translator("vi", "month", "Tháng");
        $this->overwrite_translator("en", "month", "Month");

        $this->overwrite_translator("vi", "months", "Tháng");
        $this->overwrite_translator("en", "months", "Months");

        $this->overwrite_translator("vi", "year", "Năm");
        $this->overwrite_translator("en", "year", "Year");

        $this->overwrite_translator("vi", "years", "Năm");
        $this->overwrite_translator("en", "years", "Years");
		
		$this->overwrite_translator("vi", "you_are_login_as", "Xin chào");
		$this->overwrite_translator("en", "you_are_login_as", "You are logged in as");
		
		$this->overwrite_translator("vi", "hello", "Xin chào");
		$this->overwrite_translator("en", "hello", "Hello");

        $this->overwrite_translator("vi", "hello", "Xin chào");
        $this->overwrite_translator("en", "hello", "Hello");

        $this->overwrite_translator("vi", "registration_not_allowed", "Hiện tại bạn không được phép đăng ký tài khoản mới");
        $this->overwrite_translator("en", "registration_not_allowed", "User registration is currently not allowed");

        $this->overwrite_translator("vi", "check_email_for_confirm_link", "Kiểm tra địa chỉ email của bạn để lấy link xác thực");
        $this->overwrite_translator("en", "check_email_for_confirm_link", "Check your e-mail for the confirmation link");

        $this->overwrite_translator("vi", "check_email_for_new_password", "Kiểm tra địa chỉ email của bạn để lấy mật khẩu mới");
        $this->overwrite_translator("en", "check_email_for_new_password", "Check your e-mail for your new password");

        $this->overwrite_translator("vi", "registration_complete_check_email", "Đăng ký hoàn tất, xin vui lòng kiểm tra thông tin qua địa chỉ email");
        $this->overwrite_translator("en", "registration_complete_check_email", "Registration complete. Please check your e-mail");

        $this->overwrite_translator("vi", "register_url_setting_description", "Lựa chọn trang cho người dùng đăng ký tài khoản");
        $this->overwrite_translator("en", "register_url_setting_description", "Choose the page for user to register");

        $this->overwrite_translator("vi", "login_url", "Link đăng nhập");
        $this->overwrite_translator("en", "login_url", "Login url");

        $this->overwrite_translator("vi", "login_url_setting_description", "Lựa chọn trang cho người dùng đăng nhập vào hệ thống");
        $this->overwrite_translator("en", "login_url_setting_description", "Choose the page for user to login");

        $this->overwrite_translator("vi", "lost_password_url", "Link quên mật khẩu");
        $this->overwrite_translator("en", "lost_password_url", "Lost password url");

        $this->overwrite_translator("vi", "lost_password_url_setting_description", "Lựa chọn trang cho người dùng lấy lại mật khẩu");
        $this->overwrite_translator("en", "lost_password_url_setting_description", "Choose the page for user to get new password");

        $this->overwrite_translator("vi", "choose_page", "Lựa chọn trang");
        $this->overwrite_translator("en", "choose_page", "Choose page");

        $this->overwrite_translator("vi", "user_point", "Tính điểm thành viên");
        $this->overwrite_translator("en", "user_point", "User point");

        $this->overwrite_translator("vi", "enable_user_point_description", "Bật hoặc tắc chức năng tính điểm cho thành viên");
        $this->overwrite_translator("en", "enable_user_point_description", "Turn on or turn off functional to count user point");
		
		$this->overwrite_translator("vi", "name", "Tên");
		$this->overwrite_translator("en", "name", "Name");

        $this->overwrite_translator("vi", "tab_sidebar_description", "Sidebar chứa các widget hiển thị trên tab");
        $this->overwrite_translator("en", "tab_sidebar_description", "The widget area that contains widgets to display in tab");

        $this->overwrite_translator("vi", "comment_insert_on_date", 'Bình luận được đăng vào ngày %s');
        $this->overwrite_translator("en", "comment_insert_on_date", 'Comment was inserted on %s');

        $this->overwrite_translator("vi", "your_post_name_has_a_new_comment", 'Bài viết "%s" của bạn vừa có bình luận mới');
        $this->overwrite_translator("en", "your_post_name_has_a_new_comment", 'Your post "%s" has new comment');

        $this->overwrite_translator("vi", "your_post_name_on_blog_name_has_new_comment", 'Bài viết "%1$s" của bạn trên %2$s vừa có bình luận mới');
        $this->overwrite_translator("en", "your_post_name_on_blog_name_has_new_comment", 'Your post "%1$s" on %2$s has new comment');

        $this->overwrite_translator("vi", "account_setting", "Cài đặt cho tài khoản");
        $this->overwrite_translator("en", "account_setting", "Account settings");
		
		$this->overwrite_translator("vi", "your_name", "Tên của bạn");
		$this->overwrite_translator("en", "your_name", "Your name");
		
		$this->overwrite_translator("vi", "your_email", "Email của bạn");
		$this->overwrite_translator("en", "your_email", "Your email");
		
		$this->overwrite_translator("vi", "your_website", "Website của bạn");
		$this->overwrite_translator("en", "your_website", "Your website");
		
		$this->overwrite_translator("vi", "add_your_comment", "Viết ý kiến của bạn");
		$this->overwrite_translator("en", "add_your_comment", "Leave Yours +");
		
		$this->overwrite_translator("vi", "logout", "Thoát");
		$this->overwrite_translator("en", "logout", "Logout");

        $this->overwrite_translator("vi", "time_between_post_setting_description", "Thành viên chỉ được đăng bài viết cách nhau x phút");
        $this->overwrite_translator("en", "time_between_post_setting_description", "User can only publish post after x minute(s)");

        $this->overwrite_translator("vi", "time_between_posts", "Thời gian giữa các bài viết");
        $this->overwrite_translator("en", "time_between_posts", "Time between posts");

        $this->overwrite_translator("vi", "you_must_wait_x_minute_before_publish_next_post", "Bạn phải đợi thêm %s phút nữa mới có thể đăng bài viết");
        $this->overwrite_translator("en", "you_must_wait_x_minute_before_publish_next_post", "You must wait % minute(s) before publishing the next post");
		
		$this->overwrite_translator("vi", "create_post", "Tạo bài viết");
		$this->overwrite_translator("en", "create_post", "Create post");
		
		$this->overwrite_translator("vi", "pages", "Trang");
		$this->overwrite_translator("en", "pages", "Pages");
		
		$this->overwrite_translator("vi", "get_started_here", "Bắt đầu từ đây");
		$this->overwrite_translator("en", "get_started_here", "Get started here");
		
		$this->overwrite_translator("vi", "ready_publish_first_post", "Sẵn sàng để đăng bài viết đầu tiên của bạn");
		$this->overwrite_translator("en", "ready_publish_first_post", "Ready to publish your first post");
		
		$this->overwrite_translator("vi", "nothing_found", "Không tìm thấy nội dung");
		$this->overwrite_translator("en", "nothing_found", "Nothing found");
		
		$this->overwrite_translator("vi", "no_post_found", "Không tìm thấy nội dung, xin vui lòng thử lại bằng cách dùng công cụ tìm kiếm");
		$this->overwrite_translator("en", "no_post_found", "It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help");
		
		$this->overwrite_translator("vi", "no_search_result", "Không tìm thấy kết quả, xin vui lòng thử tìm kiếm lại với từ khóa khác");
		$this->overwrite_translator("en", "no_search_result", "Sorry, but nothing matched your search terms. Please try again with some different keywords");
		
		$this->overwrite_translator("vi", "sitemap", "Sơ đồ trang web");
		$this->overwrite_translator("en", "sitemap", "Sitemap");

        $this->overwrite_translator("vi", "list_wishlist", "Danh sách ưa thích");
        $this->overwrite_translator("en", "list_wishlist", "Wishlist");

        $this->overwrite_translator("vi", "personal_information", "Thông tin cá nhân");
        $this->overwrite_translator("en", "personal_information", "Personal information");

        $this->overwrite_translator("vi", "checkout", "Thanh toán");
        $this->overwrite_translator("en", "checkout", "Checkout");

        $this->overwrite_translator("vi", "list_yahoo", "Danh sách tài khoản Yahoo");
        $this->overwrite_translator("en", "list_yahoo", "List Yahoo account");

        $this->overwrite_translator("vi", "online_support", "Hỗ trợ trực tuyến");
        $this->overwrite_translator("en", "online_support", "Online support");

        $this->overwrite_translator("vi", "main_slider", "Slider chính");
        $this->overwrite_translator("en", "main_slider", "Main slider");

        $this->overwrite_translator("vi", "sub_slider", "Slider phụ");
        $this->overwrite_translator("en", "sub_slider", "Sub slider");

        $this->overwrite_translator("vi", "hello_user", 'Chào bạn %1$s');
        $this->overwrite_translator("en", "hello_user", 'Hello %1$s');

        $this->overwrite_translator("vi", "welcome_user", 'Chào bạn %1$s');
        $this->overwrite_translator("en", "welcome_user", 'Welcome %1$s');

        $this->overwrite_translator("vi", "hi_user", 'Chào bạn %1$s');
        $this->overwrite_translator("en", "hi_user", 'Hi %1$s');

        $this->overwrite_translator("vi", "main_slider_setting_description", "Nhập danh sách hình ảnh cho slider, mỗi nhóm là một dòng bao gồm tiêu đề, link bài viết và link hình ảnh");
        $this->overwrite_translator("en", "main_slider_setting_description", "Enter list images for slider, each line includes a text label, image link and image url separated by commas");

        $this->overwrite_translator("vi", "list_yahoo_support_description", "Nhập danh sách tài khoản Yahoo, mỗi nhóm là một dòng bao gồm tên hiển thị và tên tài khoản cách nhau bởi dấu phẩy");
        $this->overwrite_translator("en", "list_yahoo_support_description", "Enter list Yahoo account, each line includes a display name and account names separated by commas");

        $this->overwrite_translator("vi", "cart_content", "Thông tin giỏ hàng");
        $this->overwrite_translator("en", "cart_content", "The cart content");

        $this->overwrite_translator("vi", "show_x_x_of_x_result", 'Hiển thị %1$s – %2$s trên tổng số %3$s kết quả');
        $this->overwrite_translator("en", "show_x_x_of_x_result", 'Showing %1$s – %2$s of %3$s results');

        $this->overwrite_translator("vi", "show_single_result", "Hiển thị 1 kết quả");
        $this->overwrite_translator("en", "show_single_result", "Showing the single result");
		
		$this->overwrite_translator("vi", "page_not_found", "Không tìm thấy trang");
		$this->overwrite_translator("en", "page_not_found", "Page not found");
		
		$this->overwrite_translator("vi", "page_not_found_description", "Trang bạn đang xem hiện không tồn tại, xin vui lòng dùng công cụ tìm kiếm bên dưới hoặc liên hệ với người quản lý");
		$this->overwrite_translator("en", "page_not_found_description", "Sorry, no posts matched your criteria. You can try to use the search form below or contact the administrator");
		
		$this->overwrite_translator("vi", "search_post_by_tag", "Tìm kiếm bài viết theo thẻ");
		$this->overwrite_translator("en", "search_post_by_tag", "Search post by tag");
		
		$this->overwrite_translator("vi", "search_post_by_category", "Tìm kiếm bài viết theo chuyên mục");
		$this->overwrite_translator("en", "search_post_by_category", "Search post by category");
		
		$this->overwrite_translator("vi", "theme_settings", "Cài đặt giao diện");
		$this->overwrite_translator("en", "theme_settings", "Theme Settings");
		
		$this->overwrite_translator("vi", "view_all_post_in_category", "Xem tất cả bài viết của chuyên mục");
		$this->overwrite_translator("en", "view_all_post_in_category", "View all posts in category");
		
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
		
		$this->overwrite_translator("vi", "topic_title", "Tiêu đề bài viết");
		$this->overwrite_translator("en", "topic_title", "Topic title");
		
		$this->overwrite_translator("vi", "topic_type", "Thể loại bài viết");
		$this->overwrite_translator("en", "topic_type", "Topic type");
		
		$this->overwrite_translator("vi", "topic_status", "Trạng thái bài viết");
		$this->overwrite_translator("en", "topic_status", "Topic status");
		
		$this->overwrite_translator("vi", "topic_tags", "Thẻ bài viết");
		$this->overwrite_translator("en", "topic_tags", "Topic tags");
		
		$this->overwrite_translator("vi", "normal", "Bình thường");
		$this->overwrite_translator("en", "normal", "Normal");
		
		$this->overwrite_translator("vi", "edit", "Sửa");
		$this->overwrite_translator("en", "edit", "Edit");
		
		$this->overwrite_translator("vi", "close", "Đóng");
		$this->overwrite_translator("en", "close", "Close");

        $this->overwrite_translator("vi", "all", "Tất cả");
        $this->overwrite_translator("en", "all", "All");

        $this->overwrite_translator("vi", "sort_by_popularity", "Sắp xếp theo độ phổ biến");
        $this->overwrite_translator("en", "sort_by_popularity", "Sort by popularity");

        $this->overwrite_translator("vi", "sort_by_rating", "Sắp xếp theo đánh giá trung bình");
        $this->overwrite_translator("en", "sort_by_rating", "Sort by average rating");

        $this->overwrite_translator("vi", "sort_by_newness", "Sắp xếp theo thời gian đăng");
        $this->overwrite_translator("en", "sort_by_newness", "Sort by newness");

        $this->overwrite_translator("vi", "sort_by_price_asc", "Sắp xếp theo giá thấp đến cao");
        $this->overwrite_translator("en", "sort_by_price_asc", "Sort by price: low to high");

        $this->overwrite_translator("vi", "sort_by_price_desc", "Sắp xếp theo giá cao đến thấp");
        $this->overwrite_translator("en", "sort_by_price_desc", "Sort by price: high to low");

        $this->overwrite_translator("vi", "default_sorting", "Sắp xếp mặc định");
        $this->overwrite_translator("en", "default_sorting", "Default sorting");

        $this->overwrite_translator("vi", "no_product_found", "Không có sản phẩm");
        $this->overwrite_translator("en", "no_product_found", "No products were found matching your selection");

        $this->overwrite_translator("vi", "show_x_result", "Hiển thị tất cả %d kết quả");
        $this->overwrite_translator("en", "show_x_result", "Showing all %d results");

        $this->overwrite_translator("vi", "no_product_in_wishlist", "Chưa có sản phẩm trong danh sách ưa thích");
        $this->overwrite_translator("en", "no_product_in_wishlist", "No products were added to the wishlist");

        $this->overwrite_translator("vi", "product_added", "Sản phẩm đã được thêm");
        $this->overwrite_translator("en", "product_added", "Product added");

        $this->overwrite_translator("vi", "product_successfully_removed", "Sản phẩm đã được xóa thành công");
        $this->overwrite_translator("en", "product_successfully_removed", "Product successfully removed");
		
		$this->overwrite_translator("vi", "allow_html_tags", "Bạn có thể sử dụng những thẻ %s và thuộc tính sau");
		$this->overwrite_translator("en", "allow_html_tags", "You may use these %s tags and attributes");
		
		$this->overwrite_translator("vi", "html_intro", "Ngôn ngữ liên kết siêu văn bản");
		$this->overwrite_translator("en", "html_intro", "HyperText Markup Language");
		
		$this->overwrite_translator("vi", "require_field_mark", "Những mục bắt buộc được đánh dấu %s");
		$this->overwrite_translator("en", "require_field_mark", "Required fields are marked %s");
		
		$this->overwrite_translator("vi", "your_email_not_published", "Địa chỉ email của bạn sẽ được giữ bí mật");
		$this->overwrite_translator("en", "your_email_not_published", "Your email address will not be published");
		
		$this->overwrite_translator("vi", "cancel_reply", "Hủy trả lời");
		$this->overwrite_translator("en", "cancel_reply", "Cancel reply");
		
		$this->overwrite_translator("vi", "post_comment", "Gửi bình luận");
		$this->overwrite_translator("en", "post_comment", "Post comment");
		
		$this->overwrite_translator("vi", "comment_navigation", "Phân trang bình luận");
		$this->overwrite_translator("en", "comment_navigation", "Comment navigation");
		
		$this->overwrite_translator("vi", "older_comments", "Bình luận cũ hơn");
		$this->overwrite_translator("en", "older_comments", "Older comments");
		
		$this->overwrite_translator("vi", "newer_comments", "Bình luận mới hơn");
		$this->overwrite_translator("en", "newer_comments", "Newer Comments");
		
		$this->overwrite_translator("vi", "comment_body", "Nội dung tin nhắn");
		$this->overwrite_translator("en", "comment_body", "Comment body");
		
		$this->overwrite_translator("vi", "leave_reply", "Gửi bình luận");
		$this->overwrite_translator("en", "leave_reply", "Leave a reply");
		
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

        $this->overwrite_translator("vi", "hotline", "Hotline");
        $this->overwrite_translator("en", "hotline", "Hotline");

        $this->overwrite_translator("vi", "skype_widget_description", "Tài khoản Skype");
        $this->overwrite_translator("en", "skype_widget_description", "Skype account");

        $this->overwrite_translator("vi", "list_yahoo_widget_description", "Danh sách tài khoản Yahoo, cách nhau bằng dấu phẩy");
        $this->overwrite_translator("en", "list_yahoo_widget_description", "List yahoo accounts, separate by commas");

        $this->overwrite_translator("vi", "email_description", "Địa chỉ e-mail");
        $this->overwrite_translator("en", "email_description", "E-mail address");

        $this->overwrite_translator("vi", "hotline_description", "Số điện thoại đường dây nóng");
        $this->overwrite_translator("en", "hotline_description", "Hotline phone number");

        $this->overwrite_translator("vi", "support_widget_description", "Widget chứa thông tin hỗ trợ");
        $this->overwrite_translator("en", "support_widget_description", "Widget that contains support links");

        $this->overwrite_translator("vi", "switch_sb_support_widget_functional", "Bật hoặc tắt chức năng hiển thị Widget chứa thông tin hỗ trợ");
        $this->overwrite_translator("en", "switch_sb_support_widget_functional", "Turn on or turn off the Widget that contains support links");

        $this->overwrite_translator("vi", "switch_sb_link_widget_functional", "Bật hoặc tắt chức năng hiển thị Widget chứa link");
        $this->overwrite_translator("en", "switch_sb_link_widget_functional", "Turn on or turn off the Widget that displays links");

        $this->overwrite_translator("vi", "choose_link_type", "Chọn kiểu link");
        $this->overwrite_translator("en", "choose_link_type", "Choose link type");

        $this->overwrite_translator("vi", "menu", "Menu");
        $this->overwrite_translator("en", "menu", "Menu");

        $this->overwrite_translator("vi", "title_icon", "Biểu tượng tiêu đề");
        $this->overwrite_translator("en", "title_icon", "Title icon");

        $this->overwrite_translator("vi", "widget_title_icon_description", "Tên biểu tượng của Font Awesome, ví dụ: book, support,...");
        $this->overwrite_translator("en", "widget_title_icon_description", "Font Awesome icon's name, ex: book, support,...");

		$this->overwrite_translator("vi", "log_in", "Đăng nhập");
		$this->overwrite_translator("en", "log_in", "Log in");

        $this->overwrite_translator("vi", "your_order", "Đơn hàng của bạn");
        $this->overwrite_translator("en", "your_order", "Your order");

        $this->overwrite_translator("vi", "total", "Tổng cộng");
        $this->overwrite_translator("en", "total", "Total");
		
		$this->overwrite_translator("vi", "password", "Mật khẩu");
		$this->overwrite_translator("en", "password", "Password");

        $this->overwrite_translator("vi", "remove", "Xóa");
        $this->overwrite_translator("en", "remove", "Remove");
		
		$this->overwrite_translator("vi", "remember_me", "Nhớ đăng nhập");
		$this->overwrite_translator("en", "remember_me", "Remember me");
		
		$this->overwrite_translator("vi", "lost_your_password", "Quên mật khẩu");
		$this->overwrite_translator("en", "lost_your_password", "Lost your password");
		
		$this->overwrite_translator("vi", "back_to_home_page", "Quay lại trang chủ");
		$this->overwrite_translator("en", "back_to_home_page", "Back to home page");
		
		$this->overwrite_translator("vi", "register", "Đăng ký");
		$this->overwrite_translator("en", "register", "Register");
		
		$this->overwrite_translator("vi", "email", "Email");
		$this->overwrite_translator("en", "email", "E-mail");

        $this->overwrite_translator("vi", "email_address", "Địa chỉ email");
        $this->overwrite_translator("en", "email_address", "E-mail address");
		
		$this->overwrite_translator("vi", "you_are_now_logged_out", "Bạn đã đăng xuất khỏi hệ thống");
		$this->overwrite_translator("en", "you_are_now_logged_out", "You are now logged out");
		
		$this->overwrite_translator("vi", "get_new_password", "Nhận mật khẩu mới");
		$this->overwrite_translator("en", "get_new_password", "Get New Password");
		
		$this->overwrite_translator("vi", "username_or_email", "Tên tài khoản hoặc địa chỉ email");
		$this->overwrite_translator("en", "username_or_email", "Username or E-mail");
		
		$this->overwrite_translator("vi", "a_password_will_be_email_to_you", "Mật khẩu sẽ được chuyển đến email của bạn");
		$this->overwrite_translator("en", "a_password_will_be_email_to_you", "A password will be e-mailed to you");
		
		$this->overwrite_translator("vi", "password_length_must_be_at_least", "Mật khẩu ít nhất phải dài %s ký tự");
		$this->overwrite_translator("en", "password_length_must_be_at_least", "Password must be at least %s characters");
		
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
		
		$this->overwrite_translator("vi", "forums", "Diễn đàn");
		$this->overwrite_translator("en", "forums", "Forums");
		
		$this->overwrite_translator("vi", "topics", "Chủ đề");
		$this->overwrite_translator("en", "topics", "Topics");
		
		$this->overwrite_translator("vi", "says", "Nói");
		$this->overwrite_translator("en", "says", "Says");
		
		$this->overwrite_translator("vi", "at", "Lúc");
		$this->overwrite_translator("en", "at", "At");

        $this->overwrite_translator("vi", "on_date", "Vào ngày %s");
        $this->overwrite_translator("en", "on_date", "On %s");
		
		$this->overwrite_translator("vi", "comment_awaiting_moderation", "Bình luận của bạn đang được chờ để xét duyệt");
		$this->overwrite_translator("en", "comment_awaiting_moderation", "Your comment is awaiting moderation");
		
		$this->overwrite_translator("vi", "comment_closed", "Bình luận đã được đóng");
		$this->overwrite_translator("en", "comment_closed", "Comments are closed");
		
		$this->overwrite_translator("vi", "topic", "Chủ đề");
		$this->overwrite_translator("en", "topic", "Topic");
		
		$this->overwrite_translator("vi", "tags", "Thẻ");
		$this->overwrite_translator("en", "tags", "Tags");
		
		$this->overwrite_translator("vi", "favorite", "Yêu thích");
		$this->overwrite_translator("en", "favorite", "Favorite");
		
		$this->overwrite_translator("vi", "favorited", "Đã thích");
		$this->overwrite_translator("en", "favorited", "Favorited");
		
		$this->overwrite_translator("vi", "subscribe", "Theo dõi");
		$this->overwrite_translator("en", "subscribe", "Subscribe");
		
		$this->overwrite_translator("vi", "unsubscribe", "Bỏ theo dõi");
		$this->overwrite_translator("en", "unsubscribe", "Unsubscribe");
		
		$this->overwrite_translator("vi", "you_can_post_html_content", "Bạn có thể sử dụng thẻ HTML trong nội dung bài viết");
		$this->overwrite_translator("en", "you_can_post_html_content", "Your account has the ability to post unrestricted HTML content");
		
		$this->overwrite_translator("vi", "notify_follow_up_email", "Thông báo khi có bài viết mới qua email");
		$this->overwrite_translator("en", "notify_follow_up_email", "Notify me of follow-up replies via email");
		
		$this->overwrite_translator("vi", "freshness", "Hoạt động cuối");
		$this->overwrite_translator("en", "freshness", "Freshness");
		
		$this->overwrite_translator("vi", "search", "Tìm kiếm");
		$this->overwrite_translator("en", "search", "Search");
		
		$this->overwrite_translator("vi", "enter_keyword", "Nhập từ khóa");
		$this->overwrite_translator("en", "enter_keyword", "Enter your keyword");
		
		$this->overwrite_translator("vi", "started_by", "Được tạo bởi");
		$this->overwrite_translator("en", "started_by", "Started by");
		
		$this->overwrite_translator("vi", "posts", "Bài viết");
		$this->overwrite_translator("en", "posts", "Posts");
		
		$this->overwrite_translator("vi", "forum", "Diễn đàn");
		$this->overwrite_translator("en", "forum", "Forum");

        $this->overwrite_translator("vi", "product_description", "Mô tả sản phẩm");
        $this->overwrite_translator("en", "product_description", "Product description");

        $this->overwrite_translator("vi", "product_information", "Thông tin sản phẩm");
        $this->overwrite_translator("en", "product_information", "Product information");

        $this->overwrite_translator("vi", "your_review", "Đánh giá của bạn");
        $this->overwrite_translator("en", "your_review", "Your review");

        $this->overwrite_translator("vi", "submit", "Gửi");
        $this->overwrite_translator("en", "submit", "Submit");

        $this->overwrite_translator("vi", "phone", "Điện thoại");
        $this->overwrite_translator("en", "phone", "Phone");

        $this->overwrite_translator("vi", "street_address", "Địa chỉ nhà và tên đường");
        $this->overwrite_translator("en", "street_address", "Street address");

        $this->overwrite_translator("vi", "town_or_city", "Thị trấn / Thành phố");
        $this->overwrite_translator("en", "town_or_city", "Town / City");

        $this->overwrite_translator("vi", "ship_to_different_address", "Giao hàng tới địa chỉ khác");
        $this->overwrite_translator("en", "ship_to_different_address", "Ship to a different address");

        $this->overwrite_translator("vi", "billing_details", "Thông tin thanh toán");
        $this->overwrite_translator("en", "billing_details", "Billing details");

        $this->overwrite_translator("vi", "address", "Địa chỉ");
        $this->overwrite_translator("en", "address", "Address");

        $this->overwrite_translator("vi", "company_name", "Tên công ty");
        $this->overwrite_translator("en", "company_name", "Company name");

        $this->overwrite_translator("vi", "compare", "So sánh");
        $this->overwrite_translator("en", "compare", "Compare");

        $this->overwrite_translator("vi", "compare_products", "So sánh sản phẩm");
        $this->overwrite_translator("en", "compare_products", "Compare products");

        $this->overwrite_translator("vi", "add_to_cart", "Thêm vào giỏ");
        $this->overwrite_translator("en", "add_to_cart", "Add to cart");
		
		$this->overwrite_translator("vi", "account_information", "Thông tin tài khoản");
		$this->overwrite_translator("en", "account_information", "Your profiles");
		
		$this->overwrite_translator("vi", "lost_password", "Quên mật khẩu");
		$this->overwrite_translator("en", "lost_password", "Lost password");
		
		$this->overwrite_translator("vi", "registration_form", "Đăng ký tài khoản");
		$this->overwrite_translator("en", "registration_form", "Registration form");
		
		$this->overwrite_translator("vi", "maximum_length", "Độ dài tối đa");
		$this->overwrite_translator("en", "maximum_length", "Maximum length");
		
		$this->overwrite_translator("vi", "create_new_topic_in", "Tạo bài viết trong");
		$this->overwrite_translator("en", "create_new_topic_in", "Create new topic in");
		
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
		
		$this->overwrite_translator("vi", "author", "Tác giả");
		$this->overwrite_translator("en", "author", "Author");
		
		$this->overwrite_translator("vi", "reply", "Trả lời");
		$this->overwrite_translator("en", "reply", "Reply");
		
		$this->overwrite_translator("vi", "reply_to", "Trả lời cho");
		$this->overwrite_translator("en", "reply_to", "Reply to");
		
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
		
		$this->overwrite_translator("vi", "you_are_logged_in", "Bạn đã đăng nhập vào hệ thống");
		$this->overwrite_translator("en", "you_are_logged_in", "You are logged in");
		
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

        $this->overwrite_translator("vi", "input_url_or_upload_new_image", "Bạn có thể điền vào đường dẫn hoặc upload hình mới");
        $this->overwrite_translator("en", "input_url_or_upload_new_image", "You can type url or upload new image file");
		
		$this->overwrite_translator("vi", "input_url_or_upload_new_favicon", "Bạn có thể điền vào đường dẫn hoặc upload favicon mới");
		$this->overwrite_translator("en", "input_url_or_upload_new_favicon", "You can type url or upload new favicon file");
		
		$this->overwrite_translator("vi", "input_url_or_upload_new_banner", "Bạn có thể điền vào đường dẫn hoặc upload banner mới");
		$this->overwrite_translator("en", "input_url_or_upload_new_banner", "You can type url or upload new banner file");
		
		$this->overwrite_translator("vi", "theme_created_by_sbteam", "Giao diện được tạo bởi SB Team");
		$this->overwrite_translator("en", "theme_created_by_sbteam", "Theme is created by SB Team");

        $this->overwrite_translator("vi", "sale_off", "Đang khuyến mại");
        $this->overwrite_translator("en", "sale_off", "Sale off");

        $this->overwrite_translator("vi", "newest", "Mới nhất");
        $this->overwrite_translator("en", "newest", "Newest");

        $this->overwrite_translator("vi", "hottest", "Hot nhất");
        $this->overwrite_translator("en", "hottest", "Hottest");
		
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
		
		$this->overwrite_translator("vi", "need_an_account", "Bạn chưa có tài khoản? Hãy ghé sang trang đăng ký để tạo mới");
		$this->overwrite_translator("en", "need_an_account", "Need an account? Sign up for one now");
		
		$this->overwrite_translator("vi", "forgot_your_password", "Bạn quên đã quên mật khẩu");
		$this->overwrite_translator("en", "forgot_your_password", "Forgot your password");
		
		$this->overwrite_translator("vi", "theme_created_by", "Giao diện được tạo bởi");
		$this->overwrite_translator("en", "theme_created_by", "Theme is created by");

        $this->overwrite_translator("vi", "product_already_in_wishlist", "Sản phẩm đã thêm vào danh sách ưa thích");
        $this->overwrite_translator("en", "product_already_in_wishlist", "Product is already in the wishlist");

        $this->overwrite_translator("vi", "about_sb_framework", "Giới thiệu SB Framework");
		$this->overwrite_translator("en", "about_sb_framework", "About SB Framework");

        $this->overwrite_translator("vi", "browse_wishlist", "Danh sách ưu thích");
        $this->overwrite_translator("en", "browse_wishlist", "Browse wishlist");

        $this->overwrite_translator("vi", "product_detail", "Chi tiết sản phẩm");
        $this->overwrite_translator("en", "product_detail", "Product details");

        $this->overwrite_translator("vi", "reviews", "Đánh giá");
        $this->overwrite_translator("en", "reviews", "Reviews");

        $this->overwrite_translator("vi", "home", "Trang chủ");
        $this->overwrite_translator("en", "home", "Home");

        $this->overwrite_translator("vi", "products", "Sản phẩm");
        $this->overwrite_translator("en", "products", "Products");

        $this->overwrite_translator("vi", "order_note_description", "Ghi chú về đơn hàng của bạn");
        $this->overwrite_translator("en", "order_note_description", "Notes about your order, e.g. special notes for delivery");

        $this->overwrite_translator("vi", "shipping_fees", "Phí vận chuyển");
        $this->overwrite_translator("en", "shipping_fees", "Shipping fees");

        $this->overwrite_translator("vi", "product", "Sản phẩm");
        $this->overwrite_translator("en", "product", "Product");

        $this->overwrite_translator("vi", "added", "Đã thêm");
        $this->overwrite_translator("en", "added", "Added");

        $this->overwrite_translator("vi", "product_name", "Tên sản phẩm");
        $this->overwrite_translator("en", "product_name", "Product name");

        $this->overwrite_translator("vi", "order_notes", "Ghi chú đơn hàng");
        $this->overwrite_translator("en", "order_notes", "Order notes");

        $this->overwrite_translator("vi", "direct_bank_transfer", "Chuyển khoản qua ngân hàng");
        $this->overwrite_translator("en", "direct_bank_transfer", "Direct bank transfer");

        $this->overwrite_translator("vi", "order_total", "Tổng tiền phải thanh toán");
        $this->overwrite_translator("en", "order_total", "Order total");

        $this->overwrite_translator("vi", "place_order", "Gửi đơn hàng");
        $this->overwrite_translator("en", "place_order", "Place order");

        $this->overwrite_translator("vi", "coupon_code", "Mã giảm giá");
        $this->overwrite_translator("en", "coupon_code", "Coupon code");

        $this->overwrite_translator("vi", "apply_coupon", "Áp dụng mã giảm giá");
        $this->overwrite_translator("en", "apply_coupon", "Apply coupon");

        $this->overwrite_translator("vi", "update_cart", "Cập nhật giỏ hàng");
        $this->overwrite_translator("en", "update_cart", "Update cart");

        $this->overwrite_translator("vi", "cart_totals", "Tổng cộng giỏ hàng");
        $this->overwrite_translator("en", "cart_totals", "Cart totals");

        $this->overwrite_translator("vi", "cart", "Giỏ hàng");
        $this->overwrite_translator("en", "cart", "Cart");

        $this->overwrite_translator("vi", "more_address_description", "Phường, khu vực, quận, huyện,... (không bắt buộc)");
        $this->overwrite_translator("en", "more_address_description", "Apartment, suite, unit etc. (optional)");

        $this->overwrite_translator("vi", "save_address", "Lưu địa chỉ");
        $this->overwrite_translator("en", "save_address", "Save address");

        $this->overwrite_translator("vi", "confirm_new_password", "Nhập lại mật khẩu mới");
        $this->overwrite_translator("en", "confirm_new_password", "Confirm new password");

        $this->overwrite_translator("vi", "billing_address", "Địa chỉ thanh toán");
        $this->overwrite_translator("en", "billing_address", "Billing address");

        $this->overwrite_translator("vi", "is_a_required_field", "là thông tin bắt buộc");
        $this->overwrite_translator("en", "is_a_required_field", "is a required field");

        $this->overwrite_translator("vi", "my_address_description", "Thông tin địa chỉ bên dưới mặc định sẽ được sử dụng để thanh toán và nhận hàng");
        $this->overwrite_translator("en", "my_address_description", "The following addresses will be used on the checkout page by default");

        $this->overwrite_translator("vi", "my_addresses", "Địa chỉ của tôi");
        $this->overwrite_translator("en", "my_addresses", "My addresses");

        $this->overwrite_translator("vi", "wishlist", "Ưa thích");
        $this->overwrite_translator("en", "wishlist", "Wishlist");

        $this->overwrite_translator("vi", "in_stock", "Còn hàng");
        $this->overwrite_translator("en", "in_stock", "In stock");

        $this->overwrite_translator("vi", "out_of_stock", "Hết hàng");
        $this->overwrite_translator("en", "out_of_stock", "Out of stock");

        $this->overwrite_translator("vi", "unit_price", "Giá");
        $this->overwrite_translator("en", "unit_price", "Unit price");

        $this->overwrite_translator("vi", "write_product_review", "Viết nhận xét về sản phẩm");
        $this->overwrite_translator("en", "write_product_review", "Write review for product");

        $this->overwrite_translator("vi", "status", "Tình trạng");
        $this->overwrite_translator("en", "status", "Status");

        $this->overwrite_translator("vi", "customer_review", "Nhận xét từ khách hàng");
        $this->overwrite_translator("en", "customer_review", "Customer review");

        $this->overwrite_translator("vi", "out_of_5", 'Trên 5');
        $this->overwrite_translator("en", "out_of_5", 'Out of 5');

        $this->overwrite_translator("vi", "x_out_of_5", '%1$s trên 5');
        $this->overwrite_translator("en", "x_out_of_5", '%1$s out of 5');

        $this->overwrite_translator("vi", "rated_x_out_of_5", 'Được đánh giá %1$s trên 5');
        $this->overwrite_translator("en", "rated_x_out_of_5", 'Rated %1$s out of 5');

        $this->overwrite_translator("vi", "customer_reviews", "Nhận xét từ khách hàng");
        $this->overwrite_translator("en", "customer_reviews", "Customer reviews");

        $this->overwrite_translator("vi", "weight", "Trọng lượng");
        $this->overwrite_translator("en", "weight", "Weight");

        $this->overwrite_translator("vi", "brand", "Thương hiệu");
        $this->overwrite_translator("en", "brand", "Brand");

        $this->overwrite_translator("vi", "stock_status", "Tình trạng");
        $this->overwrite_translator("en", "stock_status", "Stock status");

        $this->overwrite_translator("vi", "share_on", "Chia sẻ");
        $this->overwrite_translator("en", "share_on", "Share on");

        $this->overwrite_translator("vi", "add_to_wisthlist", "Ưa thích");
        $this->overwrite_translator("en", "add_to_wishlist", "Add to wishlist");

        $this->overwrite_translator("vi", "my_wishlist_on", "Danh sách sản phẩm ưa thích trên %s");
        $this->overwrite_translator("en", "my_wishlist_on", "My wishlist on %s");

        $this->overwrite_translator("vi", "not_setup_this_type_address", "Bạn chưa thiết lập thông tin cho loại địa chỉ này");
        $this->overwrite_translator("en", "not_setup_this_type_address", "You have not set up this type of address yet");

        $this->overwrite_translator("vi", "account_dashboard_descrition", 'Từ bản điều khiển, bạn có thể xem những đơn hàng gần đây, quản lý thông tin địa chỉ nhận hàng, <a href="%s">sửa mật khẩu cũng và thông tin tài khoản</a>');
        $this->overwrite_translator("en", "account_dashboard_description", 'From your account dashboard you can view your recent orders, manage your shipping and billing addresses and <a href="%s">edit your password and account details</a>');

        $this->overwrite_translator("vi", "hello_account", 'Xin chào <strong>%1$s</strong> (không phải %1$s? <a href="%2$s">Thoát</a>)');
        $this->overwrite_translator("en", "hello_account", 'Hello <strong>%1$s</strong> (not %1$s? <a href="%2$s">Sign out</a>)');

        $this->overwrite_translator("vi", "save_changes", "Lưu thay đổi");
        $this->overwrite_translator("en", "save_changes", "Save changes");

        $this->overwrite_translator("vi", "password_with_description", "Mật khẩu (nếu không thay đổi thì để trống)");
        $this->overwrite_translator("en", "password_with_description", "Password (leave blank to leave unchanged)");

        $this->overwrite_translator("vi", "shipping_address", "Địa chỉ nhận hàng");
        $this->overwrite_translator("en", "shipping_address", "Shipping address");

        $this->overwrite_translator("vi", "proceed_to_checkout", "Tiến hành thanh toán");
        $this->overwrite_translator("en", "proceed_to_checkout", "Proceed to checkout");

        $this->overwrite_translator("vi", "price", "Giá");
        $this->overwrite_translator("en", "price", "Price");

        $this->overwrite_translator("vi", "quantity", "Số lượng");
        $this->overwrite_translator("en", "quantity", "Quantity");

        $this->overwrite_translator("vi", "shipping_free", "Giao hàng miễn phí");
        $this->overwrite_translator("en", "shipping_free", "Shipping free");

        $this->overwrite_translator("vi", "cart_subtotal", "Tổng tiền mua hàng");
        $this->overwrite_translator("en", "cart_subtotal", "Cart subtotal");

        $this->overwrite_translator("vi", "description", "Mô tả");
        $this->overwrite_translator("en", "description", "Description");

        $this->overwrite_translator("vi", "availability", "Tình trạng");
        $this->overwrite_translator("en", "availability", "Availability");

        $this->overwrite_translator("vi", "footer_sidebar_description", "Khung chứa các widget dưới footer");
        $this->overwrite_translator("en", "footer_sidebar_description", "Display widgets on footer");
		
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
		foreach($this->translators as $value) {
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
		if($this->phrase_exists($phrase)) {
			$text = $this->translators[$this->language][$phrase];
		} else {
            $text = $phrase;
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