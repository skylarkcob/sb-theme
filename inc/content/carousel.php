<?php
$id = '';
$container_class = 'slide';
$args = SB_Theme::get_carousel_argument();
$callback = '';
$count = 0;

extract($args, EXTR_OVERWRITE);

$container_class = SB_PHP::add_string_with_space_before($container_class, 'carousel');
if(empty($id) || empty($callback) || !function_exists($callback)) {
    return;
}
?>
<div data-ride="carousel" class="<?php echo $container_class; ?>" id="<?php echo $id; ?>">
    <ol class="carousel-indicators">
        <?php $indicator_class = 'carousel-paginate'; ?>
        <?php for($i = 0; $i < $count; $i++) : ?>
            <?php if(0 == $i) $indicator_class = SB_PHP::add_string_with_space_before($indicator_class, 'active'); ?>
            <li data-slide-to="<?php echo $i; ?>" data-target="#<?php echo $id; ?>" class="<?php echo $indicator_class; ?>"></li>
        <?php endfor; ?>
    </ol>
    <div role="listbox" class="carousel-inner">
        <?php call_user_func($callback); ?>
    </div>
    <a data-slide="prev" role="button" href="#<?php echo $id; ?>" class="left carousel-control">
        <span class="fa fa-chevron-left"></span>
        <span class="sr-only"><?php _e('Previous', 'sb-theme'); ?></span>
    </a>
    <a data-slide="next" role="button" href="#<?php echo $id; ?>" class="right carousel-control">
        <span class="fa fa-chevron-right"></span>
        <span class="sr-only"><?php _e('Next', 'sb-theme'); ?></span>
    </a>
</div>