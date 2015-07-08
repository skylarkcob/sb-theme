<?php
defined('ABSPATH') or die('Please do not pip me!');

$step = isset($_REQUEST['step']) ? trim($_REQUEST['step']) : '';
$code = isset($_REQUEST['code']) ? trim($_REQUEST['code']) : '';
$user_id = isset($_REQUEST['user_id']) ? trim($_REQUEST['user_id']) : 0;
$form_class = 'sb-lost-password-form';
if(!empty($step)) {
    $form_class = SB_PHP::add_string_with_space_before($form_class, $step);
}
?>
<div class="sb-login-section sb-lost-password-section">
    <div class="sb-login-section-container">
        <p class="login-title"><?php _e('Một tài khoản dùng chung cho tất cả dịch vụ', 'sb-theme'); ?></p>
        <form class="<?php echo $form_class; ?>">
            <div class="form-group">
                <h2 class="form-title"><?php _e('Quên mật khẩu', 'sb-theme'); ?></h2>
            </div>
            <div class="form-group">
                <?php if('verify' == $step) : ?>
                    <?php $text = __('Mã xác nhận', 'sb-theme'); ?>
                    <label for="activation-code"><?php echo $text; ?></label>
                    <input type="text" class="form-control activation-code" id="activation-code" placeholder="<?php echo $text; ?>" value="<?php echo $code; ?>">
                <?php elseif('reset' == $step) : ?>
                    <?php $text = __('Nhập mật khẩu mới', 'sb-theme'); ?>
                    <label for="reset-password"><?php echo $text; ?></label>
                    <input type="password" class="form-control reset-password" id="reset-password" placeholder="<?php echo $text; ?>" value="">
                    <?php $text = __('Nhập lại mật khẩu', 'sb-theme'); ?>
                    <label for="reset-password-2"><?php echo $text; ?></label>
                    <input type="password" class="form-control reset-password-2" id="reset-password-2" placeholder="<?php echo $text; ?>" value="">
                    <?php SB_Core::the_strength_indicator('reset-password-strength'); ?>
                <?php else : ?>
                    <?php $text = __('Địa chỉ email hoặc tên tài khoản', 'sb-theme'); ?>
                    <label for="login-email"><?php echo $text; ?></label>
                    <input type="text" class="form-control login-email" id="login-email" placeholder="<?php echo $text; ?>">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <?php wp_nonce_field('sb-lost-password-page', 'security'); ?>
                <input type="hidden" class="redirect" value="<?php echo SB_User::get_login_redirect(); ?>">
                <input type="hidden" class="user-id" value="<?php echo $user_id; ?>">
                <input type="hidden" class="query-code" value="<?php echo $code; ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary login-submit btn-submit"><?php _e('Xác nhận', 'sb-theme'); ?></button>
            </div>
            <div class="form-group">
                <div class="errors">
                    <?php
                        if(!empty($step)) {
                            _e('Mã xác nhận của bạn nhập không đúng.', 'sb-theme');
                        } else {
                            _e('Tài khoản hoặc địa chỉ email không tồn tại.', 'sb-theme');
                        }
                    ?>
                </div>
            </div>
        </form>
        <div class="login-links">
            <a href="<?php echo SB_User::get_login_url(); ?>"><?php _e('Đăng nhập', 'sb-theme'); ?></a>
            <?php if(SB_User::can_register()) : ?>
                <a href="<?php echo SB_User::get_register_url(); ?>"><?php _e('Tạo tài khoản', 'sb-theme'); ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>