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
	
	public static function template($name) {
		$name = str_replace(".php", "", $name);
		$template = SB_TEMPLATE_PATH . "/" . $name . ".php";
		if(file_exists($template)) {
			include $template;
		}
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
	
	public static function title() {
		if(is_home()) {
			echo get_bloginfo('name') . ' - ' . get_bloginfo('description');
		} elseif(is_post_type_archive('product')) {
			echo 'Danh sách sản phẩm';
		} elseif(is_post_type_archive('forum')) {
			echo SB_WP::phrase('forums').' '.get_bloginfo('name');
		} elseif(is_singular('forum')) {
			echo get_the_title().' - '.get_bloginfo('name');
		} elseif(is_singular('topic')) {
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
		$sb_options = new SB_Option();
		return (array)$sb_options->get_all_option();
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
	
		<nav id="comment-nav-<?php echo $type; ?>" class="navigation comment-navigation" role="navigation">
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
		<div class="sb-tivi-outer text-center"><iframe id="sbTV" src="<?php echo $src; ?>" class="sb-tivi" data-number="1" scrolling="no"></iframe></div>
		<?php
		endif;
	}
	
	public static function phrase($phrase) {
		global $sb_language;
		if(null == $sb_language || empty($sb_language)) {
			$options = SB_WP::option();
			$lang = 'vi';
			if(isset($options['language'])) {
				$lang = $options['language'];
			}
			$sb_language = new SB_Language($lang);
		}
		return $sb_language->phrase($phrase);
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
		<span class="comments-link post-comment"><i class="fa fa-comments"></i> <?php comments_popup_link( __( '<span class="count">0</span> <span class="text">'.mb_strtolower(SB_WP::phrase('comment')).'</span>', SB_DOMAIN ), __( '1 '.mb_strtolower(SB_WP::phrase('comment')), SB_DOMAIN ), __( '% '.strtolower(SB_WP::phrase('comments')), SB_DOMAIN ) ); ?></span>
		<?php
		endif;
	}
	
	public static function post_date() {
		printf( '<span class="entry-date post-date"><i class="fa fa-clock-o"></i> <a href="%1$s" rel="bookmark"><time class="entry-date updated" datetime="%2$s" pubdate>%3$s</time></a></span>',
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
		$defaults = array(
			'size'		=> array(),
			'size_name'	=> 'thumbnail'
		);
		$args = wp_parse_args($args, $defaults);
		extract($args, EXTR_SKIP);
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
            'label_text'		=> __(SB_WP::phrase('search'), SB_DOMAIN),
            'placeholder_text'	=> __(SB_WP::phrase('enter_keyword').'...', SB_DOMAIN),
            'submit_text'		=> __(SB_WP::phrase('search'), SB_DOMAIN),
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
			'must_log_in'			=> '<p class="must-log-in">' . sprintf(__( 'Bạn phải <a href="%s">đăng nhập</a> trước khi gửi bình luận.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
			'logged_in_as'			=> '<p class="logged-in-as">' . sprintf(__( __(SB_WP::phrase('you_are_login_as'), SB_DOMAIN).' <a href="%1$s">%2$s</a>. <a href="%3$s" title="'.__(SB_WP::phrase("logout"), SB_DOMAIN).'">'.__(SB_WP::phrase("logout"), SB_DOMAIN).'?</a>' ), admin_url( 'profile.php' ), esc_attr( $user_identity ), wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )) . '</p>',
			'title_reply'			=> '<a name="leaveyourcomment"></a><div class="comment-title">'.__(SB_WP::phrase('leave_reply'), SB_DOMAIN).'</div>',
			'label_submit'			=> __(SB_WP::phrase("post_comment"), SB_DOMAIN),
			'title_reply_to'		=>  __( 'Trả lời bình luận của %s' ),
			'cancel_reply_link'		=> __(SB_WP::phrase('cancel_reply'), SB_DOMAIN)
		);
		return $args;
	}
	
	public static function get_image($name) {
		return SB_IMAGES_URI . "/" . $name;
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
	
}
?>