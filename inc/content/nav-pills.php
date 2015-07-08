<?php
defined('ABSPATH') or die('Please do not pip me!');

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
$has_active = false;
foreach($tabs as $tab) {
    if(isset($tab['active']) && (bool)$tab['active']) {
        $has_active = true;
        break;
    }
}
?>
<div class="sb-tab-content">
    <ul class="nav nav-pills">
        <?php foreach($tabs as $tab) :
            $id = isset($tab['id']) ? $tab['id'] : '';
            $text = isset($tab['text']) ? $tab['text'] : '';
            $class = isset($tab['class']) ? $tab['class'] : '';
            $class = SB_PHP::add_string_with_space_before($class, 'tab-item nav-tab');
            if(empty($tab) || empty($text)) {
                continue;
            }
            if((isset($tab['active']) && (bool)$tab['active']) || (0 == $count && !$has_active)) {
                $class = SB_PHP::add_string_with_space_before($class, 'active');
            } ?>
            <li class="<?php echo $class; ?>"><a data-toggle="tab" href="#<?php echo $id; ?>"><?php echo $text; ?></a></li>
        <?php $count++; endforeach; ?>
    </ul>
    <div class="tab-content margin-top-20">
        <?php call_user_func($callback); ?>
    </div>
</div>