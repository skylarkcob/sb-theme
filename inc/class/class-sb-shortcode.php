<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SB_Shortcode {
    private $shortcodes = null;
	public function __construct() {
		$this->init();
        $this->create();
	}

    public function init() {
        $this->shortcodes = array();
        $sb_for_hocwp_blog = SB_WP::is_hocwp_blog();
        if($sb_for_hocwp_blog) {
            $this->add_to_list('postintro', 'sb_post_intro_func');
            $this->add_to_list('postcolumn', 'sb_post_intro_func');
            $this->add_to_list('column', 'sb_post_column_func');
            $this->add_to_list('subscribe', 'sb_subscribe_box_func');
            $this->add_to_list('sauarchive', 'sb_archive_func');
            $this->add_to_list('buttonlink', 'sb_button_link_func');
            $this->add_to_list('download', 'sb_download_func');
            $this->add_to_list('oldpost', 'sb_old_post_func');
            $this->add_to_list('sitelink', 'sb_old_post_func');
            $this->add_to_list('theme', 'sb_theme_func');
            $this->add_to_list('saugist', 'sb_gist_func');
            $this->add_to_list('titleline', 'sb_title_line_func');
            $this->add_to_list('hostentry', 'sb_host_post_func');
            $this->add_to_list('listpost', 'sb_list_post_func');
            $this->add_to_list('postitem', 'sb_list_post_item_func');
        }
    }

    public function create() {
        foreach($this->shortcodes as $shortcode_name => $shortcode_function_name) {
            if(!shortcode_exists($shortcode_name)) {
                add_shortcode($shortcode_name, array($this, $shortcode_function_name));
            }
        }
    }

    public function add_to_list($shortcode_name, $shortcode_function_name) {
        if(is_array($this->shortcodes) && !array_key_exists($shortcode_name, $this->shortcodes)) {
            $this->shortcodes[$shortcode_name] = $shortcode_function_name;
        }
    }

    public function sb_subscribe_box_func( $atts = array(), $content = null ) {
        extract(shortcode_atts(array('title' => 'Không muốn bỏ lỡ bài viết Học WordPress', 'email_text' => 'Không muốn bỏ lỡ bài viết Học WordPress', 'feedburner' => 'bloghocwp', 'subscribe_intro' => 'Điền vào ô bên dưới địa chỉ email của bạn để nhận bài viết Học WordPress mới nhất hàng ngày.', 'input_description' => 'Nhập địa chỉ email...', 'submit_text' => 'Subscribe'), $atts));
        return '<div class="subscribe"><h3>'.$title.'</h3><div class="subscribe-box"><div class="email-box"><h4>'.$email_text.' <a target="_blank" href="http://feedburner.google.com/fb/a/mailverify?uri='.$feedburner.'&loc=en_US">Email</a> | <a target="_blank" href="http://feeds.feedburner.com/'.$feedburner.'">RSS</a></h4><span class="sub-icon"></span><form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open(\'http://feedburner.google.com/fb/a/mailverify?uri='.$feedburner.'\', \'popupwindow\', \'scrollbars=yes,width=550,height=520\');return true"><p>'.$subscribe_intro.'</p><p><input class="txt" type="text" placeholder="'.$input_description.'" name="email"/></p><input type="hidden" value="'.$feedburner.'" name="uri"/><input type="hidden" name="loc" value="en_US"/><input class="scr" type="submit" value="'.$submit_text.'" /></form></div></div></div>';
    }

    public function sb_post_column_func($atts = array(), $content = null) {
        extract(shortcode_atts(array('float' => 'left', 'size' => 'large'), $atts));
        if(SB_PHP::is_string_contain($size, 'small')) {
            $size = 'small';
        } elseif(SB_PHP::is_string_contain($size, 'medium')) {
            $size = 'medium';
        } else {
            $size = 'large';
        }
        if(SB_PHP::is_string_contain($float, 'left')) {
            $float = 'left';
        } else {
            $float = 'right';
        }
        $float = 'pull-'.$float;
        $class = $size.' column '.$float;
        $class = trim($class);
        return '<div class="'.$class.'">'.do_shortcode($content).'</div>';
    }

    public function sb_post_intro_func($atts = array(), $content = null) {
        $kq = '';
        if(!empty($content)) {
            $kq .= '<div class="post-intro">';
            $kq .= do_shortcode($content);
            $kq .= '</div><div style="clear: both"></div>';
        }
        return $kq;
    }

    public function sb_list_post_item_func($atts = array(), $content = null) {
        $kq = '';
        if(!empty($content)) {
            $kq .= '<li class="post-item">';
            $kq .= do_shortcode($content);
            $kq .= '</li>';
        }
        return $kq;
    }

    public function sb_list_post_func($atts = array(), $content = null) {
        $kq = '';
        if(!empty($content)) {
            $kq .= '<ol class="guides list-post">';
            $kq .= do_shortcode($content);
            $kq .= '</ol>';
        }
        return $kq;
    }

    public function sb_old_post_func( $atts = array(), $content = null ) {
        extract(shortcode_atts(array('link' => '', 'href' => '', 'url' => '', 'title' => '', 'text' => ''), $atts));
        if(empty($content)) {
            $content = $text;
        }
        $href = SB_PHP::get_one_in_many_if_empty($href, array($link, $url));
        if(empty($content) || empty($href)) {
            return '';
        }
        $domain = SB_WP::get_site_domain();
        if(!SB_PHP::is_string_contain($href, $domain)) {
            $href = home_url('/'.$href);
        }
        $link = new SB_HTML('a');
        $link->set_attribute("href", $href);
        $link->set_attribute("text", $content);
        return $link->build();
    }

    public function sb_host_post_func( $atts = array(), $content = null ) {
        extract(shortcode_atts(array('title' => '', 'link' => '', 'banner' => ''), $atts));
        if(empty($content) || empty($title) || empty($link) || empty($banner)) {
            return '';
        }
        $hosting = '<div class="hostsentry"><div class="hoststhumb"><a rel="nofollow" target="_blank" title="'.$title.'" href="'.$link.'"><img width="96" height="96" title="'.$title.'" alt="'.$title.'" class="hosting-banner" src="'.$banner.'"></a><div class="post-ratings rating"><div title="5 out of 5 stars" class="ratingstars"></div></div></div><div class="hostsinfo"><div class="hoststitle"><a rel="nofollow" target="_blank" title="'.$title.'" href="'.$link.'">'.$title.'</a></div><div class="clear"></div>'.$content.'</div></div>';
        return $hosting;
    }

    public function sb_title_line_func($atts = array(), $content = null) {
        extract(shortcode_atts(array('border' => false, 'border_color' => '#D2D2D2', 'border_type' => 'dashed', 'headline' => 'h3'), $atts));
        if(empty($content)) {
            return '';
        }
        $style = ' style="margin-bottom: 20px; padding-bottom: 5px; ';
        if($border) {
            $style .= 'border-bottom: 1px '.$border_type.' '.$border_color;
        }
        $style .= '"';
        if(empty($headline)) {
            $headline = 'h3';
        }
        $title = '<'.$headline.' class="title-line"'.$style.'>'.$content.'</'.$headline.'>';
        return $title;
    }

    public function sb_code_func($atts = array(), $content = null) {
        if(empty($content)) {
            return '';
        }
        return '<pre>'.$content.'</pre>';
    }

    public function sb_gist_func($atts = array()) {
        extract(shortcode_atts(array('id' => ''), $atts));
        if(empty($id)) {
            return '';
        }
        return '<script src="https://gist.github.com/'.$id.'.js"></script>';
    }

    public function sb_download_list_item_func($atts = array(), $content = null) {
        $kq = '';
        if(!empty($content)) {
            $kq .= '<li class="download-item">';
            $kq .= do_shortcode($content);
            $kq .= '</li>';
        }
        return $kq;
    }

    public function sb_download_list_func($atts = array(), $content = null) {
        $kq = '';
        if(!empty($content)) {
            $kq .= '<div class="list-download"><span class="download-button"></span><div class="download-area"><ul class="downloads">';
            $kq .= do_shortcode($content);
            $kq .= '</ul></div></div>';
        }
        return $kq;
    }

    public function sb_download_func($atts = array(), $content = null) {
        extract(shortcode_atts(array('link' => home_url('/'), 'text' => 'Download'), $atts));
        return '<div class="sau-theme">'.$this->sb_download($link, $text).'</div>';
    }

    public function sb_url_func($atts = array(), $content = null) {
        $kq = '';
        if(empty($content)) {
            return $kq;
        }
        extract(shortcode_atts(array('link' => '', 'url' => '', 'href' => '', 'title' => ''), $atts));
        if(empty($link)) {
            $link = $url;
        }
        if(empty($link)) {
            $link = $href;
        }
        if(empty($link)) {
            return $kq;
        }
        if(!empty($title)) {
            $title = ' title="'.$title.'"';
        }
        $kq = '<a rel="external nofollow" target="_blank" href="'.$link.'"'.$title.'>'.$content.'</a>';
        return $kq;
    }

    public function sb_download($link, $text) {
        return '<span class="button download-button"><a rel="external nofollow" target="_blank" href="'.$link.'">'.$text.'</a></span>';
    }

    public function sb_theme_func($atts = array(), $content = null) {
        extract(shortcode_atts(array('download' => home_url('/'), 'demo' => home_url('/')), $atts));
        return '<div class="sau-theme"><span class="button download-button"><a rel="external nofollow" target="_blank" href="'.$download.'">Download</a></span><span class="button demo-button"><a rel="external nofollow" target="_blank" href="'.$demo.'" class="thickbox">Demo</a></span></div>';
    }

    public function sb_button_link_func($atts = array(), $content = null) {
        extract(shortcode_atts(array('href' => '', 'rel' => '', 'target' => '', 'type' => 'warning', 'link' => '', 'url' => '', 'text' => ''), $atts));
        if(empty($content)) {
            $content = $text;
        }
        if(empty($href)) {
            $href = $link;
        }
        if(empty($href)) {
            $href = $url;
        }
        if(empty($href) || empty($content)) {
            return '';
        }
        if(!empty($type)) {
            $type = "btn-".$type;
        } else {
            $type = "btn-warning";
        }
        $class = "btn ".$type;
        $class = trim($class);
        $result = new SB_HTML('a');
        $result->set_attribute("text", $content);
        $result->set_attribute("href", $href);
        $result->set_attribute("class", $class);
        return $result->build();
    }

    public function sb_smallcard_func($atts = array(), $content = null) {
        return '<div class="thanh-toan"><span class="small-card-payment"><i class="small-card"></i></span></div>';
    }

    public function sb_card_item_func($atts = array(), $content = null) {
        extract(shortcode_atts(array( "id" => ''), $atts));
        if(empty($id)) return '';
        return '<span><i class="card-icon card-icon-'.$id.'"></i></span>';
    }

    public function sb_phone_card_func($atts = array(), $content = null) {
        $kq .= '<div class="thanh-toan"><div class="row card-row">';
        $kq .= '<div class="doi-tac"></div>';
        $kq .= do_shortcode($content).'</div></div>';
        return $kq;
    }

    public function sb_bank_logo_item_func($atts = array(), $content = null) {
        extract(shortcode_atts(array( "id" => ''), $atts));
        if(empty($id)) return '';
        return '<span><i class="bank-icon bank-icon-'.$id.'"></i></span>';
    }

    public function sb_bank_logo_func($atts = array(), $content = null) {
        $kq .= '<div class="row logo-row">';
        $kq .= '<div class="doi-tac"></div>';
        $kq .= do_shortcode($content).'</div>';
        return $kq;
    }

    public function sb_bank_func($atts = array(), $content = null) {
        $kq = '<div class="thanh-toan" id="thanhtoan">';
        $kq .= '<div class="row title-row">';
        $kq .= '<div class="col-md-3">';
        $kq .= 'Tên ngân hàng';
        $kq .= '</div>';
        $kq .= '<div class="col-md-3">';
        $kq .= 'Chủ tài khoản';
        $kq .= '</div>';
        $kq .= '<div class="col-md-3">';
        $kq .= 'Số tài khoản';
        $kq .= '</div></div>';
        $kq .= do_shortcode($content).'</div>';
        return $kq;
    }

    public function sb_bank_item_func($atts = array(), $content = null) {
        extract(shortcode_atts(array( "name" => '', 'user' => '', 'account' => ''), $atts));
        $kq = '<div class="row bank-row">';
        $kq .= '<div class="col-md-3">';
        $kq .= $name;
        $kq .= '</div>';
        $kq .= '<div class="col-md-3">';
        $kq .= $user;
        $kq .= '</div>';
        $kq .= '<div class="col-md-3">';
        $kq .= $account;
        $kq .= '</div></div>';
        return $kq;
    }

	public function stl_func($atts = array(), $content = null) {
		extract(shortcode_atts(array('src' => '', 'provider' => '123dapp'), $atts));
		$kq = '';
		if(empty($src) && !empty($content)) {
			$src = $content;
		}
		if(!empty($src)) {
			$blob = 'blob/';
			if(false !== strpos($src, 'github')) {
				$src = str_replace($blob, '', $src);
				$provider = 'github';
			}
			if('github' == strtolower($provider)) {
				$src = SB_PHP::remove_http($src);
				$src = str_replace('github.com', 'https://raw.githubusercontent.com', $src);
				$src = 'https://render.githubusercontent.com/view/3d?url='.$src;
			} else {
				$src = 'http://apps.123dapp.com/embeddedview/?url='.$src.'&amp;showthumbnail=true';
			}
			$kq = '<div onmousewheel="return false;" class="file-embed">';
			$kq .= '<iframe src="'.$src.'" frameborder="0"></iframe>';
			$kq .= '</div>';
		}
		return $kq;
	}

    public function sb_archive_func($atts = array(), $content = null) {
        $kq = '';
        if(function_exists('compact_archive')) {
            $kq .= '<h3>Tìm bài viết theo ngày</h3>';
            $kq .= '<ul class="compactarchives sb-archive-date list-unstyled">';
            $kq .= '<li>';
            $kq .= do_shortcode('[compact_archive style="block"]');
            $kq .= '</li>';
            $kq .= '</ul>';
        }
        $cats = SB_WP::get_all_category_list_item();
        if(!empty($cats)) {
            $kq .= '<h3>Xem bài viết theo chuyên mục</h3>';
            $kq .= '<ul class="sb-archive-category">';
            $kq .= $cats;
            $kq .= '</ul>';
        }
        $tags = SB_WP::get_all_tag_list_item();
        if(!empty($tags)) {
            $kq .= '<h3>Xem bài viết theo từ khóa</h3>';
            $kq .= '<ul class="sb-archive-tag">';
            $kq .= $tags;
            $kq .= '</ul>';
        }
        return $kq;
    }
}