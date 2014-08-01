<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<?php if(is_single()) : ?>
	<h1 class="entry-title post-title"><?php the_title(); ?></h1>
<?php else : ?>
	<h2 class="entry-title post-title">
		<a rel="bookmark" title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h2>
<?php endif; ?>