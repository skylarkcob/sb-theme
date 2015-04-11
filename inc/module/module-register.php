<?php
$error_message = '';
$has_error = false;
$error_class = 'errors';
$user_inserted = false;
$required_fields = sb_login_page_signup_required_fields();
$recaptcha_response = isset($_REQUEST['g-recaptcha-response']) ? $_REQUEST['g-recaptcha-response'] : '';
$recaptcha_result = SB_Core::check_recaptcha_response(SB_Option::get_recaptcha_secret_key(), $recaptcha_response, SB_Core::get_visitor_ip());
$email = isset($_POST['signup-email']) ? trim($_POST['signup-email']) : '';
$phone = isset($_POST['signup-phone']) ? trim($_POST['signup-phone']) : '';
$name = isset($_POST['signup-fullname']) ? trim($_POST['signup-fullname']) : '';
$password = isset($_POST['signup-password']) ? trim($_POST['signup-password']) : '';
$re_password = isset($_POST['signup-password-2']) ? trim($_POST['signup-password-2']) : '';
$address = isset($_POST['signup-address']) ? trim($_POST['signup-address']) : '';
if(isset($_POST['signup-submit'])) {
    if(SB_User::exists($email)) {
        $has_error = true;
        $error_message = __('Địa chỉ email của bạn đã tồn tại.', 'sb-theme');
    } elseif(sb_login_page_signup_captcha()) {
        $captcha = isset($_POST['signup-captcha']) ? $_POST['signup-captcha'] : '';
        if(!SB_Core::check_captcha($captcha)) {
            $has_error = true;
            $error_message = __('Mã bảo mật bạn nhập không đúng', 'sb-theme');
        }
    }
}
if($has_error) {
    $error_class = SB_PHP::add_string_with_space_before($error_class, 'active');
}
if(!$has_error) {
    $args = array(
        'username' => $email,
        'email' => $email,
        'password' => $password
    );
    $user_id = SB_User::add($args);
    //$user_id = 0;
    if($user_id > 0) {
        $user_inserted = true;
        $user = SB_User::get_by('id', $user_id);
        SB_User::update_status($user, 6);
        SB_User::generate_activation_code($user);
        $name_arr = explode(' ', $name);
        $first_name = array_pop($name_arr);
        $last_name = trim(implode(' ', $name_arr));
        $nice_name = SB_PHP::remove_vietnamese($name);
        $nice_name = str_replace(' ', '-', $nice_name);
        $user_data = array(
            'user_nicename' => $nice_name,
            'display_name' => $name,
            'first_name' => $first_name,
            'last_name' => $last_name
        );
        SB_User::update($user, $user_data);
        SB_User::update_meta($user_id, 'phone', $phone);
        SB_User::update_meta($user_id, 'address', $address);
        SB_User::send_signup_verify_email($user);
    }
}
?>
<div class="sb-signup-page">
    <?php if($user_inserted) : ?>
        <div class="inserted-message bg-success sb-message-box"><?php _e('Tài khoản của bạn đã được tạo, xin vui lòng kiểm tra email để kích hoạt.', 'sb-theme'); ?></div>
    <?php else : ?>
        <div class="col-right">
            <div class="sb-signup">
                <div class="sb-signup-container">
                    <form class="sb-signup-form" action="" name="sb-signup-form" method="post">
                        <div class="form-group">
                            <h2 class="form-title"><?php _e('Đăng ký tài khoản', 'sb-theme'); ?></h2>
                        </div>
                        <div class="form-group">
                            <?php
                            $text = __('Địa chỉ email', 'sb-theme');
                            $class = 'form-control';
                            $key = 'email';
                            $class = SB_PHP::add_string_with_space_before($class, 'signup-email');
                            if(in_array($key, $required_fields)) {
                                $class = SB_PHP::add_string_with_space_before($class, 'must-have');
                            }
                            ?>
                            <label for="signup-email"><?php echo $text; ?></label>
                            <input type="text" class="<?php echo $class; ?>" id="signup-email" placeholder="<?php echo $text; ?>" value="<?php echo $email; ?>" name="signup-email">
                        </div>
                        <div class="form-group">
                            <?php
                            $text = __('Họ và tên', 'sb-theme');
                            $class = 'form-control';
                            $key = 'name';
                            $class = SB_PHP::add_string_with_space_before($class, 'signup-fullname');
                            if(in_array($key, $required_fields)) {
                                $class = SB_PHP::add_string_with_space_before($class, 'must-have');
                            }
                            ?>
                            <label for="signup-fullname"><?php echo $text; ?></label>
                            <input type="text" class="<?php echo $class; ?>" id="signup-fullname" placeholder="<?php echo $text; ?>" value="<?php echo $name; ?>" name="signup-fullname">
                        </div>
                        <div class="form-group">
                            <?php
                            $text = __('Số điện thoại', 'sb-theme');
                            $class = 'form-control';
                            $key = 'phone';
                            $class = SB_PHP::add_string_with_space_before($class, 'signup-phone');
                            if(in_array($key, $required_fields)) {
                                $class = SB_PHP::add_string_with_space_before($class, 'must-have');
                            }
                            ?>
                            <label for="signup-phone"><?php echo $text; ?></label>
                            <input type="text" class="<?php echo $class; ?>" id="signup-phone" placeholder="<?php echo $text; ?>" value="<?php echo $phone; ?>" name="signup-phone">
                        </div>
                        <div class="form-group">
                            <?php
                            $text = __('Địa chỉ', 'sb-theme');
                            $class = 'form-control';
                            $key = 'address';
                            $class = SB_PHP::add_string_with_space_before($class, 'signup-address');
                            if(in_array($key, $required_fields)) {
                                $class = SB_PHP::add_string_with_space_before($class, 'must-have');
                            }
                            ?>
                            <label for="signup-address"><?php echo $text; ?></label>
                            <input type="text" class="<?php echo $class; ?>" id="signup-address" placeholder="<?php echo $text; ?>" value="<?php echo $address; ?>" name="signup-address">
                        </div>
                        <div class="form-group">
                            <?php
                            $text = __('Mật khẩu', 'sb-theme');
                            $class = 'form-control';
                            $key = 'password';
                            $class = SB_PHP::add_string_with_space_before($class, 'signup-password');
                            if(in_array($key, $required_fields)) {
                                $class = SB_PHP::add_string_with_space_before($class, 'must-have');
                            }
                            ?>
                            <label for="signup-password"><?php echo $text; ?></label>
                            <input type="password" class="<?php echo $class; ?>" id="signup-password" placeholder="<?php echo $text; ?>" value="<?php echo $password; ?>" name="signup-password">
                        </div>
                        <div class="form-group">
                            <?php
                            $text = __('Xác nhận mật khẩu', 'sb-theme');
                            $class = 'form-control';
                            $key = 'password-2';
                            $class = SB_PHP::add_string_with_space_before($class, 'signup-password-2');
                            if(in_array($key, $required_fields)) {
                                $class = SB_PHP::add_string_with_space_before($class, 'required');
                            }
                            ?>
                            <label for="signup-password-2"><?php echo $text; ?></label>
                            <input type="password" class="<?php echo $class; ?>" id="signup-password-2" placeholder="<?php echo $text; ?>" name="signup-password-2" value="<?php echo $re_password; ?>">
                        </div>
                        <?php if(sb_login_page_signup_captcha()) : ?>
                            <div class="form-group">
                                <?php SB_Core::the_captcha(); ?>
                            </div>
                            <div class="form-group">
                                <?php
                                $text = __('Mã bảo mật', 'sb-theme');
                                $class = 'form-control must-have';
                                $key = 'captcha';
                                $class = SB_PHP::add_string_with_space_before($class, 'signup-captcha');
                                ?>
                                <label for="signup-captcha"><?php echo $text; ?></label>
                                <input type="text" class="<?php echo $class; ?>" id="signup-captcha" placeholder="<?php echo $text; ?>" value="" name="signup-captcha">
                            </div>
                        <?php endif; ?>
                        <div class="form-group hidden-fields">
                            <?php wp_nonce_field('sb-signup-page', 'security'); ?>
                            <input type="hidden" value="1" name="signup-submit">
                        </div>
                        <button type="submit" class="btn btn-primary signup-submit btn-submit"><?php _e('Tạo tài khoản', 'sb-theme'); ?></button>
                        <div class="<?php echo $error_class; ?>">
                            <?php echo $error_message; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-left">
            <?php do_action('sb_login_page_register_left'); ?>
        </div>
    <?php endif; ?>
</div>