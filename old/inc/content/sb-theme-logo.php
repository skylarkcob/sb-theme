<?php
if(is_admin() || !method_exists('SB_Option', 'get_logo_type') || !method_exists('SB_Option', 'get_logo_text')) {
    return;
}
$logo_url = SB_Option::get_logo_url();
$logo_type = SB_Option::get_logo_type();
$logo_text = SB_Option::get_logo_text();
$lang = SB_PHP::get_session('current_language');
$home_url = esc_url(home_url('/'));
if(!empty($lang)) {
    $home_url = add_query_arg('lang', $lang, $home_url);
}
?>
<?php if(empty($logo_url)) : ?>
    <h1 class="sb-logo site-logo">
        <a href="<?php echo $home_url; ?>" style="text-indent: 0;"><?php echo $logo_text; ?></a>
    </h1>
<?php else : ?>
    <?php if($logo_type == 'text') : ?>
        <h1 class="sb-logo site-logo">
            <a href="<?php echo $home_url; ?>" style="text-indent: 0;"><?php echo $logo_text; ?></a>
        </h1>
    <?php else : ?>
    <h1 class="sb-logo site-logo image">
        <?php if($logo_type == 'background') : ?>
            <a href="<?php echo $home_url; ?>" style="background-image: url(<?php echo $logo_url; ?>);"><?php echo SB_PHP::get_domain_name(home_url()); ?></a>
        <?php else : ?>
            <a class="image-link" href="<?php echo $home_url; ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>">
                <?php SB_Theme::the_logo_image_html(); ?>
            </a>
        <?php endif; ?>
    </h1>
    <?php endif; ?>
<?php endif; ?>