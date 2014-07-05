<?php

if ( post_password_required() ) {
	return;
}

$sb_post = new SB_Post();
?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>

		<h3 class="comments-title">
			<?php
				$int_count = get_comments_number();
				printf( _n( '1 bình luận', '%1$s bình luận', $int_count, SB_DOMAIN ), number_format_i18n( $int_count ) );
			?>
		</h3>

		<?php SB_Theme::comment_navigation( 'above' ); ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'			=> 'ol',
					'short_ping'	=> true,
					'avatar_size'	=> 100,
					'reply_text'	=> 'Trả lời',
					'callback'		=> 'sbtheme_comment_callback'
				) );
			?>
		</ol><!-- .comment-list -->

		<?php SB_Theme::comment_navigation( 'below' ); ?>

	<?php endif; // have_comments() ?>
	
	<?php if ( ! $sb_post->is_comment_allowed() ) : ?>

			<p class="no-comments"><h5><?php _e( 'Bình luận đã được đóng.', SB_DOMAIN ); ?><h5></p>
	
	<?php else : ?>

		<?php comment_form( SB_Theme::comment_form_args() ); ?>
		
	<?php endif; ?>	

</div><!-- #comments -->
<?php
function sbtheme_comment_callback( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	} ?>
	
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	
	<?php if ( 'div' != $args['style'] ) : ?>
	
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
		
	<?php endif; ?>
	
	<div class="comment-author vcard">
	
		<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		
		<?php printf( __( '<cite class="fn"><strong>%s</strong></cite> <span class="says">nói:</span>' ), get_comment_author_link() ); ?>
	</div>

	<div class="comment-meta comment-metadata">
		<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
		<?php printf( __('%1$s lúc %2$s'), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Sửa)' ), '  ', '' ); ?>
	</div>
	<div class="comment-content">
	
		<?php comment_text(); ?>
	
	</div>
	
	<?php if ( $comment->comment_approved == '0' ) : ?>
	
		<em class="comment-awaiting-moderation"><?php _e( 'Bình luận của bạn đang được chờ để xét duyệt.' ); ?></em>
		
	<?php endif; ?>

	<div class="reply">
		<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
	
	<?php if ( 'div' != $args['style'] ) : ?>
	
		</div>
		
	<?php endif;
	
}
?>