<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SB_Post {
	public $post;
	private $thumbnail_size = "thumbnail_crop";
	private $thumbnail_default;
	
	public function __construct() {
		$this->post = get_post();
		$this->thumbnail_default = SB_WP::get_no_thumbnail_url();
	}

    public function is_valid() {
        if($this->post->ID > 0) {
            return true;
        }
        return false;
    }

	public function init($post) {
		$this->post = $post;
	}
	
	public function set($post) {
		$this->init($post);
	}

    public function set_by_id($post_id) {
        $this->set(get_post($post_id));
    }
	
	public function get_by_id($id) {
		$this->post = get_post($id);
	}
	
	public function get_meta($key) {
		return get_post_meta($this->post->ID, $key, true);
	}
	
	public function update_meta($key, $value) {
		update_post_meta($this->post->ID, $key, $value);
	}
	
	public function get_views() {
		$views = 0;
		if($this->get_meta('views')) {
			$views = $this->get_meta('views');
		}
		return $views;
	}
	
	public function get_likes() {
		$result = 0;
		if($this->get_meta('likes')) {
			$result = $this->get_meta('likes');
		}
		return $result;
	}
	
	public function update_likes($value) {
		$likes = $this->get_likes();
		if(0 == $value && $likes > 0) {
			$likes--;
		} else {
			$likes++;
		}
		$this->update_meta('likes', $likes);
	}
	
	public function is_in_favorite() {
		$kq = false;
		if(is_user_logged_in()) {
			$user = new SB_User();
			$favorites = $user->get_favorite_post();
			if(in_array($this->post->ID, $favorites)) {
				$kq = true;
			}
		}
		return $kq;
	}
	
	public function is_comment_allowed() {
		return (bool) comments_open($this->post->ID);
	}
	
	public function get_permalink() {
		return get_permalink($this->post->ID);
	}
	
	public function permalink() {
		echo $this->get_permalink();
	}
	
	public function has_thumbnail() {
		return has_post_thumbnail($this->post->ID);
	}
	
	public function get_thumbnail($size = "") {
		if(empty($size)) {
			$size = $this->thumbnail_size;
		}
		if($this->has_thumbnail()) {
			if('television' == $this->post->post_type) {
				return get_the_post_thumbnail($this->post->ID, $size, array('alt' => trim(strip_tags($this->get_meta('wpcf-tivi-title'))), 'title' => trim(strip_tags($this->get_meta('wpcf-tivi-title')))));
			} else {
				return get_the_post_thumbnail($this->post->ID, $size);
			}
		}
		return '<img class="no-thumbnail wp-post-image" src="'.$this->thumbnail_default.'">';
	}

    public function the_comment() {
        if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            ?>
            <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'twentyfourteen' ), __( '1 Comment', 'twentyfourteen' ), __( '% Comments', 'twentyfourteen' ) ); ?></span>
            <?php
        }
    }
	
	public function comment_link() {
		SB_Theme::post_comment_link();
	}

	public function title($head = "h2") {
        the_title( '<'.$head.' class="entry-title fancy post-title"><a href="' . esc_url( get_permalink($this->get_id()) ) . '">', '</a></'.$head.'>' );
	}
	
	public function get_date($d = "") {
		if(empty($d)) {
			return get_the_date();
		}
		return get_the_date($d);
	}
	
	public function get_author_post_url() {
		return get_author_posts_url( get_the_author_meta( 'ID' ) );
	}
	
	public function get_author_name() {
		return get_the_author();
	}

    public function the_meta_text() {
        if(is_page()) {
            printf(
                sprintf('<div class="meta sb-post-meta">%1$s</div>', __(SB_PHP::add_dotted(SB_WP::phrase('posted_on_date_by_author')), SB_DOMAIN)),
                $this->get_date(),
                sprintf('<a href="%1$s" title="">%2$s</a>', $this->get_author_post_url(), $this->get_author_name())
            );
        } else {
            printf(
                sprintf('<div class="meta sb-post-meta">%1$s</div>', __(SB_PHP::add_dotted(SB_WP::phrase('posted_on_date_by_author_filed_under_category')), SB_DOMAIN)),
                $this->get_date(),
                sprintf('<a href="%1$s" title="">%2$s</a>', $this->get_author_post_url(), $this->get_author_name()),
                $this->get_the_category()
            );
        }
    }

	public function meta() {
		printf( '<span class="entry-date"><a href="%1$s" rel="bookmark"><time class="entry-date updated" datetime="%2$s">%3$s</time></a></span> <span class="byline"><span class="author vcard"><a class="url fn n" href="%4$s" rel="author">%5$s</a></span></span>',
			esc_url( $this->get_permalink() ),
			esc_attr( $this->get_date( 'c' ) ),
			esc_html( $this->get_date() ),
			esc_url( $this->get_author_post_url() ),
			$this->get_author_name()
		);
	}

    public function count_comment() {
        return get_comments_number($this->post->ID);
    }

    public function get_author() {
        $user = new SB_User();
        $user->set_by_id($this->post->post_author);
        return $user;
    }

    public function get_title() {
        return $this->post->post_title;
    }

	public function thumbnail($args = array()) {
        $title = $this->get_title();
		?>
		<div class="post-thumbnail">
			<a href="<?php $this->permalink(); ?>" title="<?php echo $title; ?>"><?php echo SB_WP::get_post_thumbnail($args); ?></a>
		</div>
		<?php
	}

    public function the_human_time_diff() {
        echo $this->human_time_diff();
    }

    public function human_time_diff() {
        if(!$this->is_valid()) {
            global $post;
        } else {
            $post = $this->post;
        }

        $date = get_post_time('G', true, $post);
        $langs = array(
            SB_PHP::lowercase(SB_WP::phrase('second')),
            SB_PHP::lowercase(SB_WP::phrase('minute')),
            SB_PHP::lowercase(SB_WP::phrase('hour')),
            SB_PHP::lowercase(SB_WP::phrase('day')),
            SB_PHP::lowercase(SB_WP::phrase('week')),
            SB_PHP::lowercase(SB_WP::phrase('month')),
            SB_PHP::lowercase(SB_WP::phrase('year')),
            SB_PHP::lowercase(SB_WP::phrase('seconds')),
            SB_PHP::lowercase(SB_WP::phrase('minutes')),
            SB_PHP::lowercase(SB_WP::phrase('hours')),
            SB_PHP::lowercase(SB_WP::phrase('days')),
            SB_PHP::lowercase(SB_WP::phrase('weeks')),
            SB_PHP::lowercase(SB_WP::phrase('months')),
            SB_PHP::lowercase(SB_WP::phrase('years'))
        );
        $chunks = array(
            array( 60 * 60 * 24 * 365 , __( $langs[6], SB_DOMAIN ), __( $langs[13], SB_DOMAIN ) ),
            array( 60 * 60 * 24 * 30 , __( $langs[5], SB_DOMAIN ), __( $langs[12], SB_DOMAIN ) ),
            array( 60 * 60 * 24 * 7, __( $langs[4], SB_DOMAIN ), __( $langs[11], SB_DOMAIN ) ),
            array( 60 * 60 * 24 , __( $langs[3], SB_DOMAIN ), __( $langs[10], SB_DOMAIN ) ),
            array( 60 * 60 , __( $langs[2], SB_DOMAIN ), __( $langs[9], SB_DOMAIN ) ),
            array( 60 , __( $langs[1], SB_DOMAIN ), __( $langs[8], SB_DOMAIN ) ),
            array( 1, __( $langs[0], SB_DOMAIN ), __( $langs[7], SB_DOMAIN ) )
        );
        if ( !is_numeric( $date ) ) {
            $time_chunks = explode( ':', str_replace( ' ', ':', $date ) );
            $date_chunks = explode( '-', str_replace( ' ', '-', $date ) );
            $date = gmmktime( (int)$time_chunks[1], (int)$time_chunks[2], (int)$time_chunks[3], (int)$date_chunks[1], (int)$date_chunks[2], (int)$date_chunks[0] );
        }
        $current_time = SB_WP::current_time_mysql();
        $newer_date = ( !$newer_date ) ? strtotime( $current_time ) : $newer_date;
        $since = $newer_date - $date;
        if ( 0 > $since )
            return __( SB_WP::phrase("recently"), SB_DOMAIN );
        for ( $i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            if ( ( $count = floor($since / $seconds) ) != 0 )
                break;
        }
        $output = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];
        if ( !(int)trim($output) ){
            $output = '0 ' . __( $langs[0], SB_DOMAIN );
        }
        $output .= __(' '.SB_PHP::lowercase(SB_WP::phrase("ago")), SB_DOMAIN);
        return $output;
    }

    public function get_id() {
        return $this->post->ID;
    }

    public function get_related_post($args = array()) {
        $args['post_id'] = $this->get_id();
        return SB_WP::get_related_post($args);
    }

    public function the_related_post($args = array()) {
        $title = __(SB_WP::phrase('related_posts'), SB_DOMAIN);
        echo '<div class="sb-related-post">';
        if(SB_WP::is_yarpp_installed()) {
            SB_WP::related_post();
        } else {
            $related_posts = $this->get_related_post($args);
            if($related_posts) {
                extract($args, EXTR_OVERWRITE);
                printf('<h3 class="title">%s</h3>', $title);
                echo '<ol class="sb-list-post">';
                foreach($related_posts as $post) {
                    printf('<li><a href="%1$s" title="">%2$s</a></li>', get_permalink($post->ID), $post->post_title);
                }
                echo '</ol>';
            }
        }
        echo '</div>';
    }

    public function get_all_category_id() {
        return wp_get_post_categories($this->get_id());
    }

    public function get_category() {
        $cat_id = wp_get_post_categories($this->get_id());
        $result = array();
        foreach($cat_id as $id) {
            array_push($result, get_category($id));
        }
        return $result;
    }

    public function get_all_tag_id() {
        $tags = (array)wp_get_post_tags($this->get_id(), array('fields' => 'ids'));
        return $tags;
    }

    public function get_tag() {
        $tag_id = wp_get_post_tags($this->get_id());
        $result = array();
        foreach($tag_id as $id) {
            array_push($result, get_tag($id));
        }
        return $result;
    }

    public function has_tag() {
        $tags = $this->get_tag();
        if(count($tags) > 0) {
            return true;
        }
        return false;
    }

    public function count_image() {
        return SB_WP::count_image_in_post($this->get_id());
    }

    public function the_tag($separator = ', ') {
        $tags = $this->get_tag();
        $result = "";
        foreach($tags as $tag) {
            $result .= sprintf('<a href="%1$s" title="">%2$s</a>', get_tag_link($tag), $tag->name).$separator;
        }
        $result = trim($result, $separator);
        echo $result;
    }

    public function the_category($separator = ', ') {
        echo $this->get_the_category($separator);
    }

    public function get_the_category($separator = ', ') {
        $cats = $this->get_category();
        $result = "";
        foreach($cats as $cat) {
            $result .= sprintf('<a href="%1$s" title="">%2$s</a>', get_category_link($cat), $cat->name).$separator;
        }
        $result = trim($result, $separator);
        return $result;
    }

    public function get_comment($args = array()) {
        $args["post_id"] = $this->get_id();
        $args["status"] = 'approve';
        return get_comments($args);
    }

    public function has_comment() {
        $comments = $this->get_comment();
        if(count($comments) > 0) {
            return true;
        }
        return false;
    }

    public function the_list_comment_author($args = array()) {
        $comments = $this->get_comment($args);
        $result = "";
        foreach($comments as $comment) {
            $result .= sprintf('<a href="%1$s" title="">%2$s</a>, ', get_comment_link($comment), $comment->comment_author);
        }
        $result = trim($result, ', ');
        echo $result;
    }

    public function the_views() {
        echo $this->get_views();
    }
}
?>