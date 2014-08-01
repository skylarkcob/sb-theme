<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<?php if(has_post_thumbnail()) : ?>
	<div class="post-thumbnail">
		<?php global $thumbnail_size; ?>
		<?php $size = 'thumbnail_crop'; ?>
		<?php if(!empty($thumbnail_size)) $size = $thumbnail_size; ?>
		<?php if(is_single()) : ?>
			<?php the_post_thumbnail($size); ?>
		<?php else : ?>
			<a class="no-transition" href="<?php the_permalink(); ?>"><?php the_post_thumbnail($size); ?></a>
		<?php endif; ?>
	</div>											
<?php endif; ?>