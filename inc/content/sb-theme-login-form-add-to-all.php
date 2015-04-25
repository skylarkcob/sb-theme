<div class="sb-theme-extra">
    <?php
    $use_captcha = SB_Option::use_login_captcha();
    if($use_captcha) {
        $args = array(
            'id' => 'sb_theme_login_captcha',
            'name' => 'captcha_code'
        );
        SB_Field::captcha($args);
    }
    ?>
</div>