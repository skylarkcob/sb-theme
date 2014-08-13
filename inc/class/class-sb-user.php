<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SB_User extends WP_User {
	public $user;
	private $favorite_key = 'favorite_';
	public function __construct() {
		if(is_user_logged_in()) {
			$this->user = wp_get_current_user();
		}
	}
	
	public function init($user) {
		$this->user = $user;
	}
	
	public function set($user) {
		$this->init($user);
	}

    public function set_by_id($id) {
        $this->user = get_user_by("id", $id);
    }

    public function get_user($by, $value) {
        return get_user_by($by, $value);
    }
	
	public function add($user) {
		$this->init($user);
	}
	
	public function remove_role($role) {
		$this->user->remove_role($role);
	}
	
	public function add_role($role) {
		$this->user->add_role($role);
	}
	
	public function get_by($key, $value) {
		$this->init(get_user_by($key, $value));
	}
	
	public function get_meta($key) {
		return get_user_meta($this->user->ID, $key, true);
	}
	
	public function get_favorite($type) {
		return array_filter((array)$this->get_meta($this->favorite_key.$type));
	}
	
	public function update_meta($key, $value) {
		update_user_meta($this->user->ID, $key, $value);
	}
	
	public function add_favorite($type, $value) {
		$favorites = $this->get_favorite($type);
		array_push($favorites, $value);
		$favorites = array_unique($favorites);
		$this->update_meta($this->favorite_key.$type, $favorites);
	}
	
	public function remove_favorite($type, $value) {
		$favorites = $this->get_favorite($type);
		if(($key = array_search($value, $favorites)) !== false) {
			unset($favorites[$key]);
			$favorites = array_filter($favorites);
			$this->update_meta($this->favorite_key.$type, $favorites);
		}
	}
	
	public function get_favorite_post() {
		return $this->get_favorite('post');
	}
	
	public function add_favorite_post($value) {
		$this->add_favorite('post', $value);
	}
	
	public function remove_favorite_post($value) {
		$this->remove_favorite('post', $value);
	}
	
	public function get_favorite_manga() {
		return $this->get_favorite('manga');
	}
	
	public function add_favorite_manga($value) {
		$this->add_favorite('manga', $value);
	}
	
	public function remove_favorite_manga($value) {
		$this->remove_favorite('manga', $value);
	}
	
	public function get_favorite_product() {
		return $this->get_favorite('product');
	}
	
	public function add_favorite_product($value) {
		$this->add_favorite('product', $value);
	}
	
	public function remove_favorite_product($value) {
		$this->remove_favorite('product', $value);
	}
	
	public function get_role() {
		if ( !empty( $this->user->roles ) && is_array( $this->user->roles ) ) {
			return $this->user->roles;
		}
		return array();
	}

    public function get_point() {
        $point = intval($this->get_meta("point"));
        if(1 > $point) {
            $point = 0;
        }
        return $point;
    }

    public function set_point($point) {
        $this->update_meta("point", $point);
    }

    public function update_point($point) {
        $old_point = $this->get_point();
        $old_point += $point;
        $this->set_point($old_point);
    }

    public function minus_point($point) {
        $old_point = $this->get_point();
        $old_point -= $point;
        $this->set_point($old_point);
    }

    public function get_post_comment() {
        return (array)$this->get_meta("post_comment");
    }

    public function count_comment_on_this_post() {
        if(is_single()) {
            $post_comment = $this->get_post_comment();
            foreach($post_comment as $value) {
                if(isset($value["post"]) && $value["post"] == get_the_ID()) {
                    return $value["count"];
                }
            }
        }
        return 0;
    }

    public function receive_mail($subject, $message) {
        SB_WP::send_html_mail($this->get_email(), $subject, $message);
    }

    public function get_email() {
        return $this->user->user_email;
    }

    public function get_display_name() {
        return $this->user->display_name;
    }

    public function get_avatar($args = array()) {
        $size = 96;
        $default = '';
        $alt = '';
        extract($args, EXTR_OVERWRITE);
        return get_avatar($this->user->ID, $size, $default, $alt);
    }

    public function get_author_url() {
        return get_author_posts_url($this->user->ID);
    }

    public function receive_mail_post_have_comment($post) {
        $current_datetime = SB_WP::get_current_datetime(true);
        $subject = sprintf(__(SB_WP::phrase("your_post_name_has_a_new_comment"), SB_DOMAIN), $post->post_title);
        $subject .= ' '.sprintf(SB_PHP::lowercase(SB_WP::phrase("on_date")), $current_datetime);
        $message = sprintf(sprintf('<p>%s</p>', SB_PHP::add_commas(__(SB_WP::phrase("hi_user"), SB_DOMAIN))), $this->get_display_name());
        $message .= sprintf(sprintf('<p>%s</p>', SB_PHP::add_dotted(__(SB_WP::phrase("your_post_name_on_blog_name_has_new_comment"), SB_DOMAIN))), $post->post_title, SB_WP::get_blog_name());
        $message .= sprintf('<p><a href="%1$s">%1$s</a></p>', get_permalink($post->ID));
        $message .= sprintf(sprintf('<p>%s</p>', SB_PHP::add_dotted(SB_WP::phrase("comment_insert_on_date"))), $current_datetime);
        return self::receive_mail($subject, $message);
    }

    public function count_comment_on_post($post) {
        $post_comment = $this->get_post_comment();
        foreach($post_comment as $value) {
            if(isset($value["post"]) && $value["post"] == $post->ID) {
                return $value["count"];
            }
        }
        return 0;
    }

    public function remove_a_post_comment($post_comment, $post) {
        $kq = array();
        $count = count($post_comment);
        for($i = 0; $i < $count; $i++) {
            if((isset($post_comment[$i]["post"]) && $post_comment[$i]["post"] == $post->ID) || !isset($post_comment[$i]["post"])) {
                continue;
            } else {
                $item = array(
                    "post"      => $post_comment[$i]["post"],
                    "count"     => $post_comment[$i]["count"]
                );
                array_push($kq, $item);
            }
        }
        return $kq;
    }

    public function update_post_comment($comment) {
        if($comment) {
            $post = get_post($comment->comment_post_ID);
            if($post) {
                $post_comment = $this->get_post_comment();
                $count_comment_on_post = $this->count_comment_on_post($post);
                if(0 == $count_comment_on_post) {
                    $item = array(
                        "post"      => $post->ID,
                        "count"     => 1
                    );
                    array_push($post_comment, $item);
                    $this->update_meta("post_comment", $post_comment);
                } else {
                    $count_comment_on_post++;
                    $post_comment = $this->remove_a_post_comment($post_comment, $post);
                    $item = array(
                        "post"      => $post->ID,
                        "count"     => $count_comment_on_post
                    );
                    array_push($post_comment, $item);
                    $this->update_meta("post_comment", $post_comment);
                }
                $author = new SB_User();
                $author->set_by_id($post->post_author);
                $author->minus_point(SB_WP::get_user_comment_point());
            }
        }
    }

    public function is_valid() {
        if($this->user->ID > 0) {
            return true;
        }
        return false;
    }

    public function is_own_post($id = 0) {
        if(1 > $id) {
            $id = get_the_ID();
        }
        $post = get_post($id);
        if($post->post_author == $this->user->ID) {
            return true;
        }
        return false;
    }

    public function is_own_this_post() {
        return $this->is_own_post();
    }

    public function count_own_post_comment($comment) {
        if($this->is_own_post()) {
            $this->update_post_comment($comment);
        }
    }

    public function update_last_post_time($time = "") {
        if(empty($time)) {
            $time = SB_WP::current_time_mysql();
        }
        $this->update_meta("last_post_time", $time);
    }

    public function get_last_post_time() {
        return $this->get_meta("last_post_time");
    }

    public function get_last_post_minute_diff() {
        $last_post_time = $this->get_last_post_time();
        return SB_WP::get_human_minute_diff(strtotime($last_post_time));
    }

    public function set_next_post_time($time = "") {
        if(empty($time)) {
            $time = SB_WP::current_time_mysql();
        }

        $time_between_post = SB_WP::get_time_between_post();
        $this->update_meta("next_post_time", SB_PHP::date_plus_minute($time, $time_between_post));
    }

    public function get_next_post_time() {
        return $this->get_meta("next_post_time");
    }
}