<?php
$user = SB_User::get_current();
$user_id = $user->ID;
$logout_url = SB_User::get_logout_url();
$avatar_large_url = SB_User::get_avatar_url($user_id, 290);
$avatar_small = SB_User::get_avatar_image($user_id, 73);
$user_login = $user->user_login;
$account_page_url = SB_User::get_profile_url();
$deactivate_url = add_query_arg(array('setting' => 'deactivate'), $account_page_url);
$setting_page = isset($_REQUEST['setting']) ? trim($_REQUEST['setting']) : '';
$gender = SB_User::get_meta($user_id, 'gender');
$lang = SB_Option::get_default_language();
$birthday = SB_User::get_birthday_timestamp($user_id);
$birth_day = intval(date('d', $birthday));
$birth_month = intval(date('m', $birthday));
$birth_year = intval(date('Y', $birthday));
$year_max = intval(date('Y')) - 13;
$year_min = $year_max - 150;
?>
<div class="sb-user-profile">
    <div class="sb-user-profile-container">
        <div class="dashboard">
            <div class="module user-avatar-box">
                <a class="bg-dashboard-avatar" href="javascript:;" style="background-image: url(&quot;<?php echo $avatar_large_url; ?>&quot;)"></a>
                <div class="profile-avatar">
                    <a class="user-avatar" href="javascript:;">
                        <?php echo $avatar_small; ?>
                    </a>
                    <div class="user-display-name">
                        <span class="name"><?php echo $user->display_name; ?></span>
                    </div>
                </div>
            </div>
            <div class="module profile-tools">
                <ul class="profile-list">
                    <li><a href="<?php echo $account_page_url; ?>"><?php _e('Thông tin tài khoản', 'sb-theme'); ?></a></li>
                    <li><a href="<?php echo add_query_arg(array('setting' => 'password'), $account_page_url); ?>"><?php _e('Mật khẩu', 'sb-theme'); ?></a></li>
                    <li><a href="<?php echo add_query_arg(array('setting' => 'contact'), $account_page_url); ?>"><?php _e('Thông tin cá nhân', 'sb-theme'); ?></a></li>
                    <li><a href="<?php echo $logout_url; ?>"><?php _e('Thoát', 'sb-theme'); ?></a></li>
                </ul>
            </div>
        </div>
        <div class="content-main">
            <?php if('contact' == $setting_page) : ?>
                <div class="module">
                    <div class="header">
                        <h2><?php _e('Thông tin cá nhân', 'sb-theme'); ?></h2>
                        <p class="description"><?php _e('Thay đổi thông tin cá nhân và thông tin liên hệ cho tài khoản của bạn', 'sb-theme'); ?></p>
                    </div>
                    <div class="content-inner">
                        <div class="control-group name">
                            <label for="user-name"><?php _e('Họ và tên', 'sb-theme'); ?></label>
                            <div class="controls">
                                <p class="notification"></p>
                                <input type="text" id="user-name" class="user-name" name="user-name" value="<?php echo $user->display_name; ?>" autocomplete="off">
                                <p class="description"><?php _e('Họ và tên thật của bạn.', 'sb-theme'); ?></p>
                            </div>
                        </div>
                        <div class="control-group gender">
                            <label for="user-gender"><?php _e('Giới tính', 'sb-theme'); ?></label>
                            <div class="controls">
                                <p class="notification"></p>
                                <select class="user-gender" name="user-gender" autocomplete="off">
                                    <option value="0" <?php selected(0, $gender); ?>><?php _e('Nam', 'sb-theme'); ?></option>
                                    <option value="1" <?php selected(1, $gender); ?>><?php _e('Nữ', 'sb-theme'); ?></option>
                                </select>
                                <p class="description"><?php _e('Chọn thông tin giới tính của bạn.', 'sb-theme'); ?></p>
                            </div>
                        </div>
                        <div class="control-group birthday">
                            <label for="user-birthday"><?php _e('Ngày sinh', 'sb-theme'); ?></label>
                            <div class="controls birthday">
                                <p class="notification"></p>
                                <?php if('vi' == $lang) : ?>
                                    <select class="user-birth-day" name="user-birth-day" autocomplete="off">
                                        <?php for($i = 1; $i <= 31; $i++) : ?>
                                            <option value="<?php echo $i; ?>" <?php selected($birth_day, $i); ?>><?php printf('%02s', $i);; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <span class="sep">/</span>
                                    <select class="user-birth-month" name="user-birth-month" autocomplete="off">
                                        <?php for($i = 1; $i <= 12; $i++) : ?>
                                            <option value="<?php echo $i; ?>" <?php selected($birth_month, $i); ?>><?php printf('%02s', $i);; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <span class="sep">/</span>
                                    <select class="user-birth-year" name="user-birth-year" autocomplete="off">
                                        <?php for($i = $year_max; $i >= $year_min; $i--) : ?>
                                            <option value="<?php echo $i; ?>" <?php selected($birth_year, $i); ?>><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                        <?php if($birth_year < $year_min || $birth_year > $year_max) : ?>
                                            <option value="<?php echo $birth_year; ?>" selected><?php echo $birth_year; ?></option>
                                        <?php endif; ?>
                                    </select>
                                <?php else : ?>
                                    <select class="user-birth-year" name="user-birth-year" autocomplete="off">
                                        <?php for($i = $year_max; $i >= $year_min; $i--) : ?>
                                            <option value="<?php echo $i; ?>" <?php selected($birth_year, $i); ?>><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                        <?php if($birth_year < $year_min || $birth_year > $year_max) : ?>
                                            <option value="<?php echo $birth_year; ?>" selected><?php echo $birth_year; ?></option>
                                        <?php endif; ?>
                                    </select>
                                    <span class="sep">/</span>
                                    <select class="user-birth-month" name="user-birth-month" autocomplete="off">
                                        <?php for($i = 1; $i <= 12; $i++) : ?>
                                            <option value="<?php echo $i; ?>" <?php selected($birth_month, $i); ?>><?php printf('%02s', $i);; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <span class="sep">/</span>
                                    <select class="user-birth-day" name="user-birth-day" autocomplete="off">
                                        <?php for($i = 1; $i <= 31; $i++) : ?>
                                            <option value="<?php echo $i; ?>" <?php selected($birth_day, $i); ?>><?php printf('%02s', $i);; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                <?php endif; ?>
                                <p class="description">
                                    <?php
                                    if('vi' == $lang) {
                                        _e('Chọn thông tin ngày sinh của bạn theo định dạng d/m/Y.', 'sb-theme');
                                    } else {
                                        _e('Chọn thông tin ngày sinh của bạn theo định dạng Y/m/d.', 'sb-theme');
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="control-group phone">
                            <label for="user-phone"><?php _e('Số điện thoại', 'sb-theme'); ?></label>
                            <div class="controls">
                                <p class="notification"></p>
                                <input type="text" id="user-phone" class="user-phone" name="user-phone" value="<?php echo SB_User::get_meta($user->ID, 'phone'); ?>" autocomplete="off">
                                <p class="description"><?php _e('Số điện thoại của bạn đang sử dụng.', 'sb-theme'); ?></p>
                            </div>
                        </div>
                        <div class="control-group identity">
                            <label for="user-identity"><?php _e('CMND', 'sb-theme'); ?></label>
                            <div class="controls">
                                <p class="notification"></p>
                                <input type="text" id="user-identity" class="user-identity" name="user-identity" value="<?php echo SB_User::get_meta($user->ID, 'identity'); ?>" autocomplete="off">
                                <p class="description"><?php _e('Số chứng minh nhân dân của bạn.', 'sb-theme'); ?></p>
                            </div>
                        </div>
                        <div class="control-group address">
                            <label for="user-address"><?php _e('Địa chỉ', 'sb-theme'); ?></label>
                            <div class="controls">
                                <p class="notification"></p>
                                <input type="text" id="user-address" class="user-address medium" name="user-address" value="<?php echo SB_User::get_meta($user->ID, 'address'); ?>" autocomplete="off">
                                <p class="description"><?php _e('Địa chỉ nơi bạn đang ở.', 'sb-theme'); ?></p>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-save save-personal-info" disabled="disabled" data-id="<?php echo $user_id; ?>"><?php _e('Lưu thay đổi', 'sb-theme'); ?></button>
                            <span class="spinner-small settings-save-spinner"></span>
                        </div>
                    </div>
                </div>
            <?php elseif('password' == $setting_page) : ?>
                <div class="module">
                    <div class="header">
                        <h2><?php _e('Mật khẩu', 'sb-theme'); ?></h2>
                        <p class="description"><?php _e('Quản lý và thay đổi mật khẩu của bạn', 'sb-theme'); ?></p>
                    </div>
                    <div class="content-inner">
                        <div class="control-group current-password">
                            <label for="user-current-password"><?php _e('Mật khẩu hiện tại', 'sb-theme'); ?></label>
                            <div class="controls">
                                <p class="notification"></p>
                                <input type="password" id="user-current-password" class="user-current-password" name="user-current-password" value="" autocomplete="off">
                                <p class="description"><?php _e('Nhập vào mật khẩu hiện tại của bạn để thay đổi mật khẩu mới.', 'sb-theme'); ?></p>
                            </div>
                        </div>
                        <div class="control-group password">
                            <label for="user-password"><?php _e('Mật khẩu mới', 'sb-theme'); ?></label>
                            <div class="controls">
                                <p class="notification"></p>
                                <input type="password" id="user-password" class="user-password password-1 main-password" name="user-password" value="" autocomplete="off" disabled>
                                <p class="description"><?php _e('Nhập mật khẩu mới.', 'sb-theme'); ?></p>
                            </div>
                        </div>
                        <div class="control-group password-2">
                            <label for="user-password-2"><?php _e('Xác nhận mật khẩu', 'sb-theme'); ?></label>
                            <div class="controls">
                                <p class="notification"></p>
                                <input type="password" id="user-password-2" class="user-password-2 password-2 re-password" name="user-password-2" value="" autocomplete="off" disabled>
                                <p class="description"><?php _e('Nhập lại mật khẩu của bạn thêm lần nữa.', 'sb-theme'); ?></p>
                            </div>
                        </div>
                        <div class="control-group strength-group">
                            <label for="password-strength"></label>
                            <div class="controls">
                                <?php SB_Core::the_strength_indicator(); ?>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-save btn-save-password" disabled="disabled" data-id="<?php echo $user_id; ?>"><?php _e('Lưu thay đổi', 'sb-theme'); ?></button>
                            <span class="spinner-small settings-save-spinner"></span>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="module">
                    <div class="header">
                        <h2><?php _e('Thông tin tài khoản', 'sb-theme'); ?></h2>
                        <p class="description"><?php _e('Thay đổi thông tin cá nhân và tài khoản của bạn', 'sb-theme'); ?></p>
                    </div>
                    <div class="content-inner">
                        <div class="control-group username">
                            <label for="user-login"><?php _e('Tên tài khoản', 'sb-theme'); ?></label>
                            <div class="controls">
                                <p class="notification"></p>
                                <input type="text" id="user-login" class="user-login" name="user-login" value="<?php echo $user_login; ?>" readonly autocomplete="off" disabled="disabled">
                                <p class="description"><?php _e('Tài khoản của bạn là duy nhất và không thể thay đổi.', 'sb-theme'); ?></p>
                            </div>
                        </div>
                        <div class="control-group email">
                            <label for="user-login"><?php _e('Địa chỉ email', 'sb-theme'); ?></label>
                            <div class="controls">
                                <p class="notification"></p>
                                <input type="text" id="user-email" class="user-email" name="user-email" value="<?php echo $user->user_email; ?>" autocomplete="off">
                                <p class="description"><?php _e('Địa chỉ email của bạn sẽ được giữ bí mật.', 'sb-theme'); ?></p>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-save save-account" disabled="disabled" data-id="<?php echo $user_id; ?>"><?php _e('Lưu thay đổi', 'sb-theme'); ?></button>
                            <span class="spinner-small settings-save-spinner"></span>
                        </div>
                        <?php if(sb_login_page_can_deactivate_account()) : ?>
                            <hr class="full-width">
                            <div class="control-group deactivate">
                                <div class="controls">
                                    <a href="<?php echo $deactivate_url; ?>"><?php _e('Xóa tài khoản của tôi', 'sb-theme'); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php SB_Core::the_ajax_security_nonce(); ?>
        </div>
    </div>
</div>