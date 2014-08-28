<?php
if ( !defined( 'ABSPATH' ) ) exit;
if ( post_password_required() ) {
	return;
}
$sb_post = new SB_Post();
?>
<div id="comments" class="comments-area sb-comment-area">
	<?php if ( have_comments() ) : ?>
		<div class="comments-title">
			<?php
				$int_count = get_comments_number();
				printf( _n( '1 '.SB_PHP::lowercase(SB_WP::phrase('comment')), '%1$s '.SB_PHP::lowercase(SB_WP::phrase('comments')), $int_count, SB_DOMAIN ), number_format_i18n( $int_count ) );
			?>
			<span class="yours"><a href="<?php the_permalink(); ?>#leaveyourcomment"><?php _e(SB_WP::phrase('add_your_comment'), SB_DOMAIN); ?></a></span>
		</div>

		<?php SB_Theme::comment_navigation( 'above' ); ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'			=> 'ol',
					'short_ping'	=> true,
					'avatar_size'	=> 100,
					'reply_text'	=> SB_WP::phrase('reply'),
					'callback'		=> 'sbtheme_comment_callback'
				) );
			?>
		</ol>

		<?php SB_Theme::comment_navigation( 'below' ); ?>

	<?php endif; // have_comments() ?>

    <?php if ( ! $sb_post->is_comment_allowed() ) : ?>
        <p class="no-comments"><?php _e( SB_PHP::add_dotted(SB_WP::phrase('comment_closed')), SB_DOMAIN ); ?></p>
    <?php else : ?>
        <?php
        $user_can_post_comment = SB_WP::can_user_post_comment();
        if($user_can_post_comment) {
            comment_form( SB_Theme::comment_form_args() );
        }
        ?>
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
		<?php printf( __( '<cite class="fn inline-block"><strong>%s</strong></cite> <span class="says">'.SB_PHP::lowercase(SB_WP::phrase('says')).':</span>' ), get_comment_author_link() ); ?>
        <span class="org hide-text"><?php echo SB_WP::get_blog_name(); ?></span>
        <span class="role hide-text">Blogger</span>
	</div>
	<div class="comment-meta comment-metadata">
		<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
		<?php printf( __('%1$s '.SB_PHP::lowercase(SB_WP::phrase('at')).' %2$s'), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '('.SB_WP::phrase('edit').')', SB_DOMAIN ), '  ', '' ); ?>
	</div>
	<div class="comment-content">
		<?php comment_text(); ?>
	</div>
	<?php if ( $comment->comment_approved == '0' ) : ?>
		<em class="comment-awaiting-moderation"><?php _e( SB_WP::phrase('comment_awaiting_moderation'), SB_DOMAIN ); ?></em>
	<?php endif; ?>
	<div class="reply">
		<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
		</div>
	<?php endif;
}
?>