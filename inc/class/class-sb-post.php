<?php
class SB_Post {
	public $post;
	private $thumbnail_size = "thumbnail_crop";
	private $thumbnail_default;
	
	public function __construct() {
		$this->post = get_post();
		$this->thumbnail_default = SB_IMAGES_URI.'/no-thumbnail.png';
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
			return get_the_post_thumbnail($this->post->ID, $size);
		}
		return '<img class="no-thumbnail wp-post-image" src="'.$this->thumbnail_default.'">';
	}
	
	public function comment_link() {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
		?>
		<span class="comments-link"><?php comments_popup_link( __( '0 bình luận', SB_DOMAIN ), __( '1 bình luận', SB_DOMAIN ), __( '% bình luận', SB_DOMAIN ) ); ?></span>
		<?php
		endif;
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
		printf( '<span class="entry-date"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s">%3$s</time></a></span> <span class="byline"><span class="author vcard"><a class="url fn n" href="%4$s" rel="author">%5$s</a></span></span>',
			esc_url( $this->get_permalink() ),
			esc_attr( $this->get_date( 'c' ) ),
			esc_html( $this->get_date() ),
			esc_url( $this->get_author_post_url() ),
			$this->get_author_name()
		);
	}
	
	public function thumbnail($size = "") {
		?>
		<div class="post-thumbnail">
			<a href="<?php $this->permalink(); ?>"><?php echo $this->get_thumbnail($size); ?></a>
		</div>
		<?php
	}
}
?>