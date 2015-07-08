<?php
defined('ABSPATH') or die('Please do not pip me!');

if(post_password_required()) {
    return;
}

function sb_theme_custom_comment_nav() {
    if(get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
        <nav class="navigation comment-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php _e('Phân trang', 'sb-theme'); ?></h2>
            <div class="nav-links">
                <?php
                if($prev_link = get_previous_comments_link(__('Bình luận cũ', 'sb-theme'))) {
                    printf('<div class="nav-previous">%s</div>', $prev_link);
                }
                if($next_link = get_next_comments_link(__('Bình luận mới', 'sb-theme'))) {
                    printf('<div class="nav-next">%s</div>', $next_link);
                }
                ?>
            </div>
        </nav>
    <?php endif;
}
?>
<div id="comments" class="comments-area">
    <?php if(have_comments()) : ?>
        <h2 class="comments-title">
            <?php printf(_nx('1 bình luận cho bài viết &ldquo;%2$s&rdquo;', '%1$s bình luận cho bài viết &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'sb-theme'), number_format_i18n(get_comments_number()), get_the_title()); ?>
        </h2>
        <?php sb_theme_custom_comment_nav(); ?>
        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 56,
            ));
            ?>
        </ol>
        <?php sb_theme_custom_comment_nav(); ?>
    <?php endif; ?>
    <?php
    if(!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
        ?>
        <p class="no-comments"><?php _e('Bình luận đã được đóng.', 'sb-theme'); ?></p>
    <?php endif; ?>
    <?php comment_form(); ?>
</div>