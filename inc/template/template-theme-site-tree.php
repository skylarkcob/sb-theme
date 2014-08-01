<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="sb-site-tree">
<?php do_action("sb_site_tree_before"); ?>
<h3 class="tree-title"><?php echo SB_PHP::add_colon(SB_WP::phrase('sitemap')); ?></h3>

<ul class="list-tree-node">
	<li class="tree-level-1 has-sub">
		<span class="arrow"></span>
		<a title="<?php bloginfo('name'); ?>" href="<?php echo home_url('/'); ?>"><?php echo SB_WP::phrase('home'); ?></a>
		<ul class="sub-tree">
			<?php
			$pages = get_pages(array("parent" => 0));
			if($pages) {			
			?>
				<?php foreach($pages as $page) : ?>
					<?php
					$class = 'tree-level-2';
					if(SB_WP::page_has_child($page->ID)) {
						$class .= ' has-sub';
					} else {
						$class .= ' no-sub';
					}
					?>
					<li class="<?php echo $class; ?>">
						<span class="arrow"></span>
						<a href="<?php echo get_page_link($page->ID); ?>" title="<?php echo $page->post_title; ?>"><?php echo $page->post_title; ?></a>
						<?php SB_WP::list_sub_page($page->ID); ?>
					</li>
				<?php endforeach; ?>
				<li class="tree-node-empty"></li>
			<?php
			}
			$cats = get_categories(array('hide_empty' => 0, "parent" => 0));
			if($cats) {
				foreach($cats as $cat) {
					$class = 'tree-level-2';
					if(SB_WP::category_has_child($cat->term_id)) {
						$class .= ' has-sub';
					} else {
						$class .= ' no-sub';
					}
					?>
					<li class="<?php echo $class; ?>">
						<span class="arrow"></span>
						<a href="<?php echo get_category_link($cat->term_id); ?>" title="<?php echo __(SB_WP::phrase('view_all_post_in_category'), SB_DOMAIN).' '.$cat->name; ?>"><?php echo $cat->name; ?></a>
						<?php SB_WP::list_sub_category($cat->term_id); ?>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</li>
</ul>
<?php
SB_WP::tag_cloud();
do_action("sb_site_tree_after");
?>
</div>