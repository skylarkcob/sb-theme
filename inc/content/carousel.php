<?php
$id = '';
$container_class = 'slide';
$args = SB_Theme::get_carousel_argument();
$callback = '';
$count = 0;

extract($args, EXTR_OVERWRITE);
$show_control = isset($args['show_control']) ? $args['show_control'] : false;
$count = ceil(abs($count));
$sb_carousel_id = $id;
$container_class = SB_PHP::add_string_with_space_before($container_class, 'carousel');
if(empty($id) || empty($callback)) {
    return;
}
?>
<div data-ride="carousel" class="<?php echo $container_class; ?>" id="<?php echo $id; ?>">
    <?php if($count > 0) : ?>
        <ol class="carousel-indicators">
            <?php $indicator_class = 'carousel-paginate'; ?>
            <?php for($i = 0; $i < $count; $i++) : ?>
                <?php if(0 == $i) $indicator_class = SB_PHP::add_string_with_space_before($indicator_class, 'active'); ?>
                <li data-slide-to="<?php echo $i; ?>" data-target="#<?php echo $id; ?>" class="<?php echo $indicator_class; ?>"></li>
            <?php endfor; ?>
        </ol>
    <?php endif; ?>
    <div role="listbox" class="carousel-inner">
        <?php call_user_func($callback); ?>
    </div>
    <?php $id = $sb_carousel_id; ?>
    <?php if($count > 1 || $show_control) : ?>
        <a data-slide="prev" role="button" href="#<?php echo $id; ?>" class="left carousel-control">
            <span class="fa fa-chevron-left"></span>
            <span class="sr-only"><?php _e('Previous', 'sb-theme'); ?></span>
        </a>
        <a data-slide="next" role="button" href="#<?php echo $id; ?>" class="right carousel-control">
            <span class="fa fa-chevron-right"></span>
            <span class="sr-only"><?php _e('Next', 'sb-theme'); ?></span>
        </a>
    <?php endif; ?>
</div>