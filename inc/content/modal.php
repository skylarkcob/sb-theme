<?php
$id = '';
$title = '';
$container_class = '';
$args = SB_Theme::get_modal_argument();
$callback = '';
$buttons = array();
$close_text = __('Close', 'sb-theme');

extract($args, EXTR_OVERWRITE);

$container_class .= ' modal fade';
$container_class = trim($container_class);
if(empty($id) || empty($title) || empty($callback)) {
    return;
}
?>
<div class="<?php echo $container_class; ?>" id="<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $id; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $close_text; ?></span></button>
                <h4 class="modal-title"><?php echo $title; ?></h4>
            </div>
            <div class="modal-body">
                <?php call_user_func($callback); ?>
            </div>
            <div class="modal-footer">
                <?php foreach($buttons as $button) : ?>
                    <?php
                    $ajax_loading = '';
                    if(isset($button['loading_image']) || (bool)$button['loading_image']) {
                        $ajax_loading = SB_Theme::get_loading_image();
                    }
                    ?>
                    <button type="button" class="btn <?php echo isset($button['class']) ? $button['class'] : ''; ?>"><span class="text"><?php echo isset($button['text']) ? $button['text'] : ''; ?></span><?php echo $ajax_loading; ?></button>
                <?php endforeach; ?>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $close_text; ?></button>
            </div>
        </div>
    </div>
</div>