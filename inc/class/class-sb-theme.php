<?php
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
	
	public static function title() {
		if(is_home()) {
			echo get_bloginfo('name') . ' - ' . get_bloginfo('description');
		} elseif(is_post_type_archive('product')) {
			echo 'Danh sách sản phẩm';
		} elseif(is_tax()) {
			single_term_title();
		} else {
			wp_title('');
		}
	}
	
	private function float_ads_left() {
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
	
	private function float_ads_right() {
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
	
	private function leaderboard_ads() {
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
	
	public static function widget_field_checkbox($args = array()) {
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
		extract($args, EXTR_SKIP);
		$input_class = trim($input_class." sb-checkbox");
		?>
		<p class="<?php echo $paragraph_class; ?>">
			<input id="<?php echo esc_attr( $id ); ?>" class="<?php echo $input_class; ?>" name="<?php echo esc_attr( $name ); ?>" type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( $value, 1, true ); ?>>
			<label for="<?php echo esc_attr( $id ); ?>"><?php _e( $description, SB_DOMAIN ); ?></label>
		</p>
		<?php
	}
	
	public static function widget_field_number($args = array()) {
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
		extract($args, EXTR_SKIP);
		$input_class = trim($input_class." sb-number");
		?>
		<p class="<?php echo $paragraph_class; ?>"<?php if(!$display) echo ' style="display:none"'; ?>>
			<label for="<?php echo esc_attr( $id ); ?>"><?php _e( $description, SB_DOMAIN ); ?></label>
			<input id="<?php echo esc_attr( $id ); ?>" class="<?php echo $input_class; ?>" name="<?php echo esc_attr( $name ); ?>" type="number" value="<?php echo esc_attr( $value ); ?>">
		</p>
		<?php
	}
	
	public static function widget_field_image_size($args = array()) {
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
		extract($args, EXTR_SKIP);
		$input_class = trim($input_class." sb-number image-size");
		?>
		<p class="<?php echo $paragraph_class; ?>"<?php if(!$display) echo ' style="display:none"'; ?>>
			<label for="<?php echo esc_attr( $id ); ?>"><?php _e( $description, SB_DOMAIN ); ?></label>
			<input id="<?php echo esc_attr( $id_width ); ?>" class="<?php echo $input_class; ?>" name="<?php echo esc_attr( $name_width ); ?>" type="number" value="<?php echo esc_attr( $value[0] ); ?>">
			<span>x</span>
			<input id="<?php echo esc_attr( $id_height ); ?>" class="<?php echo $input_class; ?>" name="<?php echo esc_attr( $name_height ); ?>" type="number" value="<?php echo esc_attr( $value[1] ); ?>">
		</p>
		<?php
	}
	
	public static function addthis_share_button() {
		echo '<div class="addthis_native_toolbox"></div>';
	}
	
	public static function option() {
		global $sb_options;
		if(!is_array($sb_options) || (is_array($sb_options) && count($sb_options) < 1)) {
			$sb_options = new SB_Option();
		}
		return (array)$sb_options;
	}

    public static function breadcrumb() {
        if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb('<div class="breadcrumb">', '</div>');
        }
    }
	
	public static function comment_navigation( $type ) {
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	
		<nav id="comment-nav-<?php echo $type; ?>" class="navigation comment-navigation" role="navigation">
			<h4 class="screen-reader-text"><?php _e( 'Phân trang bình luận', SB_DOMAIN ); ?></h4>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Cũ hơn', SB_DOMAIN ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Mới hơn &rarr;', SB_DOMAIN ) ); ?></div>
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
		<div class="sb-tivi-outer text-center"><iframe id="sbTV" src="<?php echo $src; ?>" class="sb-tivi" data-number="1" scrolling="no"></iframe></div>
		<?php
		endif;
	}
	
	public static function phrase($phrase) {
		global $sb_language;
		if(empty($sb_language)) {
			$sb_language = new SB_Language();
		}
		return $sb_language->phrase($phrase);
	}
	
	public static function copyright() {
		include SB_TEMPLATE_PATH . "/template-theme-copyright.php";
	}
	
    public static function menu($args = null) {
        $defaults = array(
            'theme_location'	=> '',
            'menu_class'		=> ''
        );
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_SKIP);
        $menu_class .= ' sf-menu '.$theme_location;
        $menu_class = trim($menu_class);
        wp_nav_menu(array('theme_location' => $theme_location, 'menu_class' => $menu_class));
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
			get_sidebar('float-ads-left');
			get_sidebar('float-ads-right');
		}
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

    public static function search_form($args = array()) {
        $defaults = array(
            'label_text'		=> 'Tìm kiếm:',
            'placeholder_text'	=> 'Nhập từ khóa...',
            'submit_text'		=> 'Tìm kiếm',
            'form_class'		=> ''
        );
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_SKIP);
        $form_class = trim($form_class.' search-form');
        ?>
        <form role="search" method="get" class="<?php echo $form_class; ?>" action="<?php echo esc_url(home_url('/')); ?>">
            <label>
                <span class="screen-reader-text"><?php echo $label_text; ?></span>
                <input type="search" class="search-field" placeholder="<?php echo $placeholder_text; ?>" value="" name="s">
            </label>
            <input type="submit" class="search-submit" value="<?php echo $submit_text; ?>">
        </form>
    <?php
    }

    public static function feed_form($args = array()) {
        self::feedburner_form($args);
    }

    public static function feedburner_form($args = array()) {
        $defaults = array(
            'name'				=> '',
            'label_text'		=> 'Nhập địa chỉ email:',
            'placeholder_text'	=> 'Nhập email...',
            'submit_text'		=> 'Gửi',
            'form_class'		=> ''
        );
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_SKIP);
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
		echo 'Giao diện được tạo bởi <a href="http://sauhi.com">SB Team</a>';
	}
	
	public static function author_box() {
		$author = new SB_Author();
		$description = $author->get_description();
		if(!empty($description)) :
	?>
		<div class="author-wrap">
			<div class="author-gravatar">
				<?php echo $author->get_avatar( 100 ); ?>
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
						<a href="<?php echo $author->get_post_url(); ?>">Xem tất cả bài viết của <?php echo $author->get_username(); ?> <span class="meta-nav">→</span></a>
					</li>
					<li class="website">
						<a target="_blank" class="ext-link" rel="external nofollow" href="<?php echo $author->get_url(); ?>" title="Ghé thăm trang chủ của <?php echo $author->get_username(); ?>">Blog</a>
					</li>

					<li class="facebook">
						<a target="_blank" class="ext-link" href="<?php echo $author->get_facebook_url(); ?>" title="Theo dõi <?php echo $author->get_username(); ?> trên Facebook" rel="external nofollow">Facebook</a>
					</li>
					<li class="googleplus">
						<a target="_blank" class="ext-link" href="<?php echo $author->get_gplus_url(); ?>" title="Theo dõi <?php echo $author->get_username(); ?> trên Goolge Plus" rel="external nofollow">Google Plus</a>
					</li>
					<li class="twitter"><a rel="external" title="Theo dõi <?php echo $author->get_username(); ?> trên Twitter" href="<?php echo $author->get_twitter_url(); ?>">Twitter</a></li>
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
												'author' => '<p class="comment-form-author">' .
													'<input id="author" placeholder="Tên *" name="author" type="text" value="' .
													esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />'.
													'<label for="author">' . __( '' ) . '</label> ' .
													( $req ? '<span class="required"></span>' : '' )  .
													'</p>',
												'email' => '<p class="comment-form-email">' .
													'<input id="email" placeholder="Email *" name="email" type="text" value="' .
													esc_attr(  $commenter['comment_author_email'] ) .
													'" size="30"' . $aria_req . ' />'  .
													'<label for="email">' . __( '' ) .
													'</label> ' .
													( $req ? '<span class="required"></span>' : '' ) .
													'</p>',
												'url' => '<p class="comment-form-url">' .
													'<input id="url" name="url" placeholder="Website" type="text" value="' .
													esc_attr( $commenter['comment_author_url'] ) .
													'" size="30" /> ' .
													'<label for="url">' .
													__( '', SB_DOMAIN ) .
													'</label>' .
												   '</p>'
											)
			),
			'comment_field'			=> '<p class="comment-form-comment">' .
											'<label for="comment">' . __( '' ) . '</label>' .
											'<textarea id="comment" name="comment" placeholder="Nội dung bình luận..." aria-required="true"></textarea>' .
											'</p>',
			'comment_notes_before'	=> '<p class="comment-notes before">' .
											__( 'Địa chỉ email của bạn sẽ được giữ bí mật.' ) .
											( $req ? ' Những mục đánh dấu (*) là bắt buộc.' : '' ) .
											'</p>',
			'comment_notes_after'	=> '<p class="form-allowed-tags comment-notes after">' .
											sprintf( 
												__( 'Bạn có thể sử dụng các thẻ <abbr title="Các thẻ HTML được phép sử dụng">HTML</abbr> như: %s' ),
												' <code>' .
												allowed_tags() .
												'</code>' ) .
												'</p>',
			'must_log_in'			=> '<p class="must-log-in">' .
											sprintf(
												__( 'Bạn phải <a href="%s">đăng nhập</a> trước khi gửi bình luận.' ),
												wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
											) .
											'</p>',

			'logged_in_as'			=> '<p class="logged-in-as">' .
											sprintf(
												__( 'Bạn đang đăng nhập với tên tài khoản <a href="%1$s">%2$s</a>. <a href="%3$s" title="Thoát khỏi tài khoản">Đăng xuất?</a>' ),
												admin_url( 'profile.php' ),
												esc_attr( $user_identity ),
												wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
											) .
											'</p>',
			'title_reply'			=> '<div class="comment-title">Bình luận</div>',
			'label_submit'			=> 'Gửi bình luận',
			'title_reply_to'		=>  __( 'Trả lời bình luận của %s' ),
			'cancel_reply_link'		=> 'Hủy trả lời'
		);
		return $args;
	}
	
	public static function get_image($name) {
		return SB_IMAGES_URI . "/" . $name;
	}
	
	public static function paypal_donate($value = 5) {
		switch($value) {
			case 5:
				echo '<a target="_blank" href="'.SB_PAYPAL_DONATE_5_URL.'"><img src="'.SB_PAYPAL_DONATE_BUTTON_CARD_URL.'" ></a>';
				break;
		}
	}
	
}
?>