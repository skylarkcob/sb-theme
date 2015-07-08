<?php
defined('ABSPATH') or die('Please do not pip me!');

if(post_password_required()) {
    return;
}
$post_id = $GLOBALS['post']->ID;
if(!function_exists('sb_comment_callback')) {
    function sb_comment_callback($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);
        $comment_permalink = get_comment_link($comment);
        if('div' == $args['style']) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        } ?>
        <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent' ); ?> id="comment-<?php comment_ID() ?>">
        <?php if('div' != $args['style']) : ?>
            <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
        <?php endif; ?>
        <div class="comment-author vcard">
            <?php if($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>
            <?php printf('<cite class="fn inline-block"><strong>%1$s</strong> - <span class="time-ago">' . SB_Comment::get_human_time_diff($comment->comment_ID) . '</span></cite> <span class="says">%2$s</span>', get_comment_author_link(), __('nói:', 'sb-theme')); ?>
            <?php edit_comment_link('('.__('Sửa', 'sb-theme').')', '', '' ); ?>
        </div>
        <?php
        $show_avatar = get_option('show_avatars');
        $style = '';
        if(!(bool)$show_avatar) {
            $style = 'margin-left:0;';
        }
        ?>
        <div class="comment-content" style="<?php echo $style; ?>">
            <?php comment_text(); ?>
        </div>
        <?php if($comment->comment_approved == '0') : ?>
            <em class="comment-awaiting-moderation"><?php _e('Bình luận của bạn đang được chờ để xét duyệt.', 'sb-theme'); ?></em>
        <?php endif; ?>
        <div class="comment-tools" data-comment="<?php echo $comment->comment_ID; ?>" data-url="<?php echo $comment_permalink; ?>">
            <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
            <?php if(SB_Comment::enable_comment_tools()) : ?>
                <?php
                $class = 'comment-like';
                $session_comment_liked_key = 'comment_' . $comment->comment_ID . '_likes';
                $liked = intval(SB_PHP::get_session($session_comment_liked_key));
                if($liked == 1) {
                    $class = SB_PHP::add_string_with_space_before($class, 'disable');
                }
                ?>
                <a class="<?php echo $class; ?>" href="javascript:;" data-session-liked-key="<?php echo $session_comment_liked_key; ?>"><span class="text"><?php _e('Thích', 'sb-theme'); ?></span><i class="fa fa-thumbs-o-up icon-right"></i> <span class="sep-dot">.</span> <span class="count"><?php echo SB_Comment::get_likes($comment->comment_ID); ?></span></a>
                <a class="comment-report" href="javascript:;"><?php _e('Báo cáo vi phạm', 'sb-theme'); ?></a>
                <a class="comment-share" href="javascript:;">
                    <span class="text">
                        <?php _e('Chia sẻ', 'sb-theme'); ?>
                    </span>
                        <i class="fa fa-angle-down icon-right"></i>
                    <span class="list-share">
                        <?php $url = SB_Core::get_social_share_url(array('social_name' => 'facebook', 'permalink' => $comment_permalink)); ?>
                        <i class="fa fa-facebook facebook" data-url="<?php echo $url; ?>"></i>
                        <i class="fa fa-google-plus google" data-url="<?php echo SB_Core::get_social_share_url(array('social_name' => 'googleplus', 'permalink' => $comment_permalink)); ?>"></i>
                        <i class="fa fa-twitter twitter" data-url="<?php echo SB_Core::get_social_share_url(array('social_name' => 'twitter', 'permalink' => $comment_permalink)); ?>"></i>
                    </span>
                </a>
            <?php endif; ?>
        </div>
        <?php if('div' != $args['style'] ) : ?>
            </div>
        <?php endif;
    }
}
?>
<div id="comments" class="comments-area sb-comment-area sb-comment-template">
    <?php if(comments_open() || get_comments_number()) : ?>
        <div class="comments-title">
            <span class="comment-count">
                <?php
                $int_count = SB_Post::get_comment_number($post_id);
                $comment_title = sprintf(_n('1 bình luận', '%1$s bình luận', $int_count, 'sb-theme' ), number_format_i18n($int_count));
                $comment_title = apply_filters('sb_theme_comment_title', $comment_title, $int_count);
                echo $comment_title;
                ?>
            </span>
            <span class="yours"><a href="<?php the_permalink(); ?>#leaveyourcomment"><?php _e('Thêm bình luận', 'sb-theme'); ?></a></span>
        </div>
        <?php sb_comment_navigation('above'); ?>
        <ol class="comment-list">
            <?php
            $args = array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 45,
                'reply_text' => '<i class="fa fa-reply icon-left"></i><span class="text">' . __('Trả lời', 'sb-theme') . '</span>',
                'callback' => 'sb_comment_callback',
                'max_depth' => 3
            );
            $comments = SB_Post::get_comments(get_the_ID());
            wp_list_comments($args, $comments);
            ?>
        </ol>
        <?php sb_comment_navigation('below'); ?>
    <?php endif; ?>
    <?php if(!sb_comment_allowed()) : ?>
        <p class="no-comments"><?php _e('Bình luận đã được đóng.', 'sb-theme'); ?></p>
    <?php else : ?>
        <?php
        $user_can_post_comment = sb_user_can_post_comment();
        if($user_can_post_comment) {
            comment_form(sb_comment_form_args());
        }
        ?>
    <?php endif; ?>
</div>