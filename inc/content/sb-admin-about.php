<?php
defined('ABSPATH') or die('Please do not pip me!');
$lang = SB_Core::get_language();
?>
<div class="sbtheme-about about-sb-text">
    <?php $logo_url = SB_CORE_URL . '/images/sb-framework-logo-300.png'; ?>
    <div class="sb-logo"><img alt="" src="<?php echo $logo_url; ?>"></div>
    <?php if('vi' == $lang) : ?>
        <p class="sb-version">Phiên bản: 2.5.1</p>
        <p>SB Framework là bộ mã nguồn PHP được thực hiện bởi SB Team, mục đích của mã nguồn này là để giúp xây dựng giao diện cho WordPress dễ dàng hơn. Bạn có thể tải phiên bản mới nhất tại <a target="_blank" href="https://github.com/skylarkcob/sb-theme">SB Theme's GitHub Repository</a>.</p>
        <p>SB Framework được viết bởi <a target="_blank" href="https://github.com/skylarkcob">skylarkcob</a> và <a target="_blank" href="https://github.com/flyenuol">flyenuol</a>, nếu bạn có bất kỳ thắc mắc hoặc câu hỏi nào muốn đặt ra, xin vui lòng truy cập vào website Học WordPress hoặc gửi mail thông qua địa chỉ email bên dưới.</p>
        <p>Nếu bạn thấy mã nguồn này hữu ích, bạn có thể ủng hộ cho các lập trình viên bằng cách gửi một cốc bia hoặc một tách cà phê thông qua cổng thanh toán PayPal. Điều này sẽ giúp SB Team có được chi phí và động lực để phát triển nhiều tính năng hơn nữa cho SB Framework. Bạn có thể ghé thăm trang <a target="_blank" href="http://hocwp.net/donate">ủng hộ</a> để biết thêm thông tin chi tiết.</p>
    <?php else : ?>
        <p class="sb-version"><?php _e('Version:', 'sb-theme'); ?> <?php echo SB_CORE_VERSION; ?></p>
        <p><?php printf(__('SB is a PHP source which is created by SB Team, the purpose of this source code is to help coding WordPress theme easier. You can download the latest version at %s.', 'sb-theme'), '<a target="_blank" href="https://github.com/skylarkcob/sb-theme">SB Theme\'s GitHub Repository</a>'); ?></p>
        <p><?php printf(__('SB is created by %1$s and %2$s, if you have any questions, please visit HocWP website or send mail to the email address below.', 'sb-theme'), '<a target="_blank" href="https://github.com/skylarkcob">skylarkcob</a>', '<a target="_blank" href="https://github.com/flyenuol">flyenuol</a>'); ?></p>
        <p><?php printf(__('If you find this source code is useful, you can support the developers by sending a beer or a cup of coffee through PayPal payment gateway. This will help SB Team get more costs and motivation to develop more features for SB. You can visit %s for more information.', 'sb-theme'), sprintf('<a target="_blank" href="http://hocwp.net/donate">%s</a>', __('donation page', 'sb-theme'))); ?></p>
    <?php endif; ?>
    <p class="sb-donate"><?php echo '<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WQSLEH5EPHJ7E"><img alt="" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" ></a>'; ?></p>
</div>