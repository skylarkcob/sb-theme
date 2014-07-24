<section class="no-results not-found sb-no-post">
	<header class="page-header">
		<h2 class="page-title"><?php _e( SB_WP::phrase('nothing_found'), SB_DOMAIN ); ?></h2>
	</header>
	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p><?php printf( __( SB_PHP::add_punctuation(SB_WP::phrase('ready_publish_first_post'), '?').' <a href="%1$s">'.SB_WP::phrase('get_started_here').'</a>.', SB_DOMAIN ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
		<?php elseif ( is_search() ) : ?>
			<p><?php _e( SB_PHP::add_dotted(SB_WP::phrase('no_search_result')), SB_DOMAIN ); ?></p>
			<?php SB_Theme::search_form(); ?>
		<?php else : ?>
			<p><?php _e( SB_PHP::add_dotted(SB_WP::phrase('no_post_found')), SB_DOMAIN ); ?></p>
			<?php SB_Theme::search_form(); ?>
		<?php endif; ?>
	</div>
</section>