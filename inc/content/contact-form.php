<?php
SB_Core::prevent_direct_access();

$args = SB_Theme::get_contact_form_arg();
$title = isset($args['title']) ? $args['title'] : __('Thông tin liên hệ', 'sb-theme');
$departments = SB_Theme::get_contact_form_departments();
$name = '';
$email = '';
if(SB_User::is_logged_in()) {
    $user = SB_User::get_current();
    $user_id = $user->ID;
    $name = $user->display_name;
    $email = $user->user_email;
}
?>
<div class="sb-theme-contact-container">
    <?php do_action('sb_theme_contact_form_before'); ?>
    <p>Những mục được đánh dấu <span class="required">(*)</span> là bắt buộc.</p>
    <h3 class="form-title"><?php echo $title; ?></h3>
    <form class="sb-theme-contact-form" action="" method="post">
        <div class="form-group">
            <label for="your_name">Họ và tên: <span class="required">*</label>
            <input type="text" id="your_name" name="your_name" value="<?php echo $name; ?>" class="form-control your-name" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="your_email">Địa chỉ email: <span class="required">*</label>
            <input type="email" id="your_email" name="your_email" value="<?php echo $email; ?>" class="form-control your-email" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="type">Thể loại: <span class="required">*</label>
            <select id="type" name="type" class="form-control type">
                <option value="">- Vui lòng chọn -</option>
                <?php foreach($departments as $key => $value) : ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="subject">Tiêu đề: <span class="required">*</label>
            <input type="text" id="subject" name="subject" value="" class="form-control subject" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="message">Nội dung: <span class="required">*</label>
            <textarea id="message" name="message" class="form-control message"></textarea>
        </div>
        <div class="form-group security-group">
            <label for="form_captcha">Mã bảo mật</label>
            <p class="description">Xin vui lòng nhập mã trong hình ảnh vào ô trống.</p>
            <?php
            $field_args = array(
                'id' => 'form_captcha',
                'name' => 'form_captcha',
                'field_class' => 'form-control'
            );
            SB_Field::captcha($field_args);
            ?>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success">Gửi tin nhắn</button>
        </div>
    </form>
</div>