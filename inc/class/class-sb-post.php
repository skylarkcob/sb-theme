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
		$this->thumbnail_default = SB_IMAGES_URI.'/no-thumbnail.png';
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
	
	public function comment_link() {
		SB_Theme::post_comment_link();
	}
	
	public function title($head = "h2") {
		if ( is_single() || is_page() ) {
			the_title( "<$head class='entry-title'>", "</$head>");
		} else {
			the_title( '<'.$head.' class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></'.$head.'>' );
		}
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
	
	public function meta() {
		printf( '<span class="entry-date"><a href="%1$s" rel="bookmark"><time class="entry-date updated" datetime="%2$s">%3$s</time></a></span> <span class="byline"><span class="author vcard"><a class="url fn n" href="%4$s" rel="author">%5$s</a></span></span>',
			esc_url( $this->get_permalink() ),
			esc_attr( $this->get_date( 'c' ) ),
			esc_html( $this->get_date() ),
			esc_url( $this->get_author_post_url() ),
			$this->get_author_name()
		);
	}
	
	public function thumbnail($size = "") {
		if('television' == $this->post->post_type) {
			$title = trim(strip_tags($this->get_meta('wpcf-tivi-title')));
		} else {
			$title = trim(strip_tags($this->post->post_title));
		}
		?>
		<div class="post-thumbnail">
			<a href="<?php $this->permalink(); ?>" title="<?php echo $title; ?>"><?php echo $this->get_thumbnail($size); ?></a>
		</div>
		<?php
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
}
?>