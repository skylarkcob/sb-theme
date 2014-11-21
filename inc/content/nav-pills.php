<?php
$args = SB_Theme::get_nav_pills_argument();
if(!is_array($args)) {
    return;
}
$callback = isset($args['callback']) ? $args['callback'] : '';
if(empty($callback)) {
    return;
}
$tabs = isset($args['tabs']) ? $args['tabs'] : array();
$contents = isset($args['contents']) ? $args['contents'] : array();
$count = 0;
?>
<div class="sb-tab-content">
    <ul class="nav nav-pills">
        <?php foreach($tabs as $tab) :
            $class = 'nav-tab';
            $id = isset($tab['id']) ? $tab['id'] : '';
            $text = isset($tab['text']) ? $tab['text'] : '';
            if(empty($tab) || empty($text)) {
                continue;
            }
            if(0 == $count) {
                $class = SB_PHP::add_string_with_space_before($class, 'active');
            } ?>
            <li class="<?php echo $class; ?>"><a data-toggle="tab" href="#<?php echo $id; ?>"><?php echo $text; ?></a></li>
        <?php $count++; endforeach; ?>
    </ul>
    <div class="tab-content margin-top-20">
        <?php call_user_func($callback); ?>
    </div>
</div>