<?php $logo_url = SB_Option::get_logo_url(); ?>
<?php if(empty($logo_url)) : ?>
    <h1 class="sb-logo site-logo">
        <a href="<?php echo esc_url(home_url('/')); ?>" style="text-indent: 1px;"><?php bloginfo('name'); ?></a>
    </h1>
<?php else : ?>
    <?php $logo_type = SB_Option::get_logo_type(); ?>
    <h1 class="sb-logo site-logo image">
        <?php if($logo_type == 'background') : ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" style="background-image: url(<?php echo $logo_url; ?>);"><?php echo SB_PHP::get_domain_name(home_url()); ?></a>
        <?php else : ?>
            <a class="image-link" href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>">
                <?php SB_Theme::the_logo_image_html(); ?>
            </a>
        <?php endif; ?>
    </h1>
<?php endif; ?>