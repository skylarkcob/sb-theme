<?php
defined('ABSPATH') or die('Please do not pip me!');

global $post;
$post_id = $post->ID;
$lang = SB_Core::get_language();
SB_Theme::the_meta_box_nonce();

SB_Theme::meta_box_before('slider-items');

$value = SB_Post::get_slider_items($post_id, true);
$list_items = $value['items'];
$item_order = $value['order'];
$max_item_id = absint(SB_PHP::get_max_number($item_order));
?>
<div class="items-container">
    <ul id="list_slider_items" class="list-slider-items" data-post="<?php echo $post_id; ?>" data-items="<?php echo count($list_items); ?>" data-max-id="<?php echo $max_item_id; ?>">
        <?php foreach($item_order as $key => $item_id) :
            $item = isset($list_items[$item_id]) ? $list_items[$item_id] : array();
            if(!SB_PHP::is_array_has_value($item)) {
                unset($item_order[$key]);
                continue;
            }
            $title = isset($item['title']) ? $item['title'] : '';
            $link = isset($item['link']) ? $item['link'] : '';
            $description = isset($item['description']) ? $item['description'] : '';
            $image_url = isset($item['image_url']) ? $item['image_url'] : '';
            $image_id = isset($item['image_id']) ? $item['image_id'] : 0;
            if($image_id > 0) {
                $media_url = SB_Post::get_media_url($image_id);
                if(!empty($media_url)) {
                    $image_url = $media_url;
                }
            }
            if(empty($image_url)) {
                continue;
            }
            $title_text = ('vi' == $lang) ? 'Tiêu đề' : __('Title', 'sb-theme');
            $link_text = ('vi' == $lang) ? 'Đường dẫn đến trang đích' : __('The link for item', 'sb-theme');
            ?>
            <li data-item="<?php echo $item_id; ?>">
                <img class="item-image" src="<?php echo $image_url; ?>">
                <div class="item-info">
                    <input type="text" name="sbmb_slider_items[items][<?php echo $item_id; ?>][title]" class="item-title" value="<?php echo $title; ?>" placeholder="<?php echo $title_text; ?>">
                    <input type="url" name="sbmb_slider_items[items][<?php echo $item_id; ?>][link]" class="item-link" value="<?php echo $link; ?>" placeholder="<?php echo $link_text; ?>">
                    <textarea name="sbmb_slider_items[items][<?php echo $item_id; ?>][description]" class="item-description"><?php echo $description; ?></textarea>
                </div>
                <input type="hidden" class="item-image-url" value="<?php echo $image_url; ?>" name="sbmb_slider_items[items][<?php echo $item_id; ?>][image_url]">
                <input type="hidden" class="item-image-id" value="<?php echo $image_id; ?>" name="sbmb_slider_items[items][<?php echo $item_id; ?>][image_id]">
                <span class="item-icon icon-delete icon-sortable-ui" data-confirm-message="<?php echo SB_Message::get_confirm_delete_text(); ?>"></span>
                <span class="item-icon icon-drag icon-sortable-ui"></span>
            </li>
        <?php endforeach; ?>
    </ul>
    <input type="hidden" name="sbmb_slider_items[order]" value="<?php echo implode(',', $item_order); ?>" class="item-order" autocomplete="off">
</div>
<?php
SB_Field::button(array('text' => ('vi' == $lang) ? 'Thêm mới' : __('Add new', 'sb-theme'), 'field_class' => 'btn-add-item'));
SB_Theme::meta_box_after();