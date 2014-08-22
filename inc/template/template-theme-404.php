<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="sb-not-found">
	<img class="not-found-image" src="<?php echo SB_Theme::get_image('page-not-found.png'); ?>" alt="" style="text-align:center">
	<h2 class="not-found-title"><?php echo SB_WP::phrase('page_not_found'); ?></h2>
	<p><?php echo SB_PHP::add_dotted(SB_WP::phrase('page_not_found_description')); ?></p>
	<?php
	SB_Theme::search_form();
	SB_WP::tag_cloud();
	SB_WP::all_category();
	?>
</div>