<?php
defined('ABSPATH') or die('Please do not pip me!');

?>
<div class="sb-login-section">
    <div class="sb-login-section-container">
        <p class="login-title"><?php _e('Một tài khoản dùng chung cho tất cả dịch vụ', 'sb-theme'); ?></p>
        <form class="sb-login-form">
            <div class="form-group">
                <h2 class="form-title"><?php _e('Đăng nhập', 'sb-theme'); ?></h2>
            </div>
            <div class="form-group">
                <?php $text = __('Địa chỉ email hoặc tên tài khoản', 'sb-theme'); ?>
                <label for="login-email"><?php echo $text; ?></label>
                <input type="text" class="form-control login-email" id="login-email" placeholder="<?php echo $text; ?>">
            </div>
            <div class="form-group">
                <?php $text = __('Mật khẩu', 'sb-theme'); ?>
                <label for="login-password"><?php echo $text; ?></label>
                <input type="password" class="form-control login-password" id="login-password" placeholder="<?php echo $text; ?>">
            </div>
            <?php do_action('sb_theme_login_page_login_form'); ?>
            <div class="form-group">
                <?php do_action('sb_login_page_field'); ?>
                <?php wp_nonce_field('sb-theme', 'security'); ?>
                <input type="hidden" class="redirect" value="<?php echo SB_User::get_login_redirect(); ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary login-submit btn-submit"><?php _e('Đăng nhập', 'sb-theme'); ?></button>
            </div>
            <div class="form-group cookies">
                <?php $text = __('Duy trì trạng thái đăng nhập', 'sb-theme'); ?>
                <label for="login-remember"><?php echo $text; ?></label>
                <input name="login-remember" type="checkbox" value="yes" class="login-remember" checked="checked">
                <span><?php echo $text; ?></span>
            </div>
            <div class="form-group">
                <div class="errors">
                    <?php
                    if(1 == SB_User::get_logged_in_fail_cookie()) {
                        _e('Bạn đã đăng nhập sai hơn 3 lần, xin vui lòng quay lại sau.', 'sb-theme');
                    } else {
                        _e('Đăng nhập không thành công, xin vui lòng thử lại.', 'sb-theme');
                    }
                    ?>
                </div>
            </div>
        </form>
        <div class="login-links">
            <a href="<?php echo SB_User::get_lost_password_url(); ?>"><?php _e('Quên mật khẩu', 'sb-theme'); ?></a>
            <?php if(SB_User::can_register()) : ?>
                <a href="<?php echo SB_User::get_register_url(); ?>"><?php _e('Tạo tài khoản', 'sb-theme'); ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>