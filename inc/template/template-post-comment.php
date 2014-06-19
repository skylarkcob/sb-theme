<span class="post-comment">
	<?php global $comment_class; $comment_class .= ' comment'; $comment_class = trim($comment_class); ?>
	<a class="<?php echo $comment_class; ?>" href="<?php echo comments_link(); ?>"><?php comments_number( '0 bình luận', '1 bình luận', '% bình luận' ); ?></a>
</span>