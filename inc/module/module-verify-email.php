<?php
$code = isset($_REQUEST['code']) ? trim($_REQUEST['code']) : '';
$user_id = isset($_REQUEST['user_id']) ? trim($_REQUEST['user_id']) : 0;
?>
<div class="sb-login-section">
    <div class="sb-login-section-container">
        <p class="login-title"><?php _e('Một tài khoản dùng chung cho tất cả dịch vụ', 'sb-theme'); ?></p>
        <form class="sb-verify-email-form sb-form">
            <div class="form-group">
                <h2 class="form-title"><?php _e('Xác thực tài khoản', 'sb-theme'); ?></h2>
            </div>
            <div class="form-group">
                <?php $text = __('Mã xác nhận', 'sb-theme'); ?>
                <label for="activation-code"><?php echo $text; ?></label>
                <input type="text" class="form-control activation-code" id="activation-code" placeholder="<?php echo $text; ?>" value="<?php echo $code; ?>">
            </div>
            <div class="form-group hidden-fields">
                <?php wp_nonce_field('sb-verify-email-page', 'security'); ?>
                <input type="hidden" class="redirect" value="<?php echo SB_User::get_login_redirect(); ?>">
                <input type="hidden" class="user-id" value="<?php echo $user_id; ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary login-submit btn-submit"><?php _e('Xác nhận', 'sb-theme'); ?></button>
            </div>
            <div class="form-group">
                <div class="errors">
                    <?php _e('Mã xác nhận của bạn nhập không đúng.', 'sb-theme'); ?>
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