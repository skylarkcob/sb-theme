<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="sb-site-name-description">
    <div class="title-area">
        <h1 class="site-title">
            <?php
                $option = new SB_Option();
                $logo = $option->get_logo_uri();
            ?>
            <a title="<?php bloginfo('name'); ?>" href="<?php echo esc_url(home_url('/')); ?>"><?php if(!empty($logo)) : ?><img src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?>" /><?php else : bloginfo('name'); endif; ?></a>
        </h1>
    </div>
    <h2 class="tagline"><?php bloginfo('description'); ?></h2>
</div>