<ul class="ls menu-right list-unstyled list-inline list-price-chat pull-right">
    <li id="live_chat" class="live-chat">
        <a class="text-flash various" title="Live chat" href="#live_chat_fancybox">Live Chat</a>
        <div class="sub-menu">
            <?php SB_Theme::get_custom_module('module-list-chat'); ?>
        </div>
    </li>
    <li class="price">
        <?php
        $pages = SB_Post::get_page_by_template('page-templates/shock-price.php');
        $page = array_shift($pages);
        $url = '';
        if(SB_Core::is_valid_object($page)) {
            $url = get_permalink($page);
        }
        ?>
        <a href="<?php echo $url; ?>">
            <span class="text-flash">Giá sốc mỗi ngày!!!</span>
        </a>
    </li>
</ul>