<div class="sbtheme-about about-sb-text">
    <?php
    $logo_url = '';
    if(defined("SB_THEME_VERSION")) {
        $logo_url = get_template_directory_uri() . "/sb/admin/images/sb-framework-logo-300.png";
    } else {
        $logo_url = plugins_url('images/sb-framework-logo-300.png', __FILE__);
    }
    ?>
    <div class="sb-logo"><img src="<?php echo $logo_url; ?>"></div>
    <p class="sb-version"><?php _e("Version:", "sbteam"); ?> <?php echo SB_VERSION; ?></p>
    <p><?php echo 'SB Framework is a PHP framework that created by SB Team, the purposes of this framework are to help coding WordPress more easier. You can download the latest version from <a target="_blank" href="https://github.com/skylarkcob/sb">SB\'s GitHub Repository</a>.'; ?></p>
    <p><?php echo 'SB Framework is writen by <a target="_blank" href="https://github.com/skylarkcob">skylarkcob</a> and <a target="_blank" href="https://github.com/flyenuol">flyenuol</a>, if you have any questions please send mail via the email address below this panel or write a post on learn WordPress forum.'; ?></p>
    <p><?php echo 'If you feel this framework helpful, you can use the PayPal donate button below to send a beer or a coffee cup to these founders. It will help us to create more functional on this framework. You can visit <a target="_blank" href="http://hocwp.net/donate">the donation page</a> for more information.'; ?></p>
    <p class="sb-donate"><?php echo '<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WQSLEH5EPHJ7E"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" ></a>'; ?></p>
</div>