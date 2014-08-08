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

    public function count_own_post_comment($comment) {
        if($this->is_own_post()) {
            $this->update_post_comment($comment);
        }
    }

}