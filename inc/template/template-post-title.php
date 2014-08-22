<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<?php if(is_single()) : ?>
	<h1 class="sb-post-title post-title"><?php the_title(); ?></h1>
<?php else : ?>
	<h2 class="sb-post-title post-title">
		<a rel="bookmark" title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h2>
<?php endif; ?>