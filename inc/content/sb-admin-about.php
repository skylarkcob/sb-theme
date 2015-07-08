<?php
defined('ABSPATH') or die('Please do not pip me!');

?>
<div class="sbtheme-about about-sb-text">
    <?php $logo_url = SB_CORE_URL . '/images/sb-framework-logo-300.png'; ?>
    <div class="sb-logo"><img alt="" src="<?php echo $logo_url; ?>"></div>
    <p class="sb-version"><?php _e('Phiên bản:', 'sb-theme'); ?> <?php echo SB_CORE_VERSION; ?></p>
    <p><?php printf(__('SB Framework là bộ mã nguồn PHP được thực hiện bởi SB Team, mục đích của mã nguồn này là để giúp xây dựng giao diện cho WordPress dễ dàng hơn. Bạn có thể tải phiên bản mới nhất tại %s.', 'sb-theme'), '<a target="_blank" href="https://github.com/skylarkcob/sb-core">SB Core\'s GitHub Repository</a>'); ?></p>
    <p><?php printf(__('SB Framework được viết bởi %1$s và %2$s, nếu bạn có bất kỳ thắc mắc hoặc câu hỏi nào muốn đặt ra, xin vui lòng truy cập và website Học WordPress hoặc gửi thông qua địa chỉ email.', 'sb-theme'), '<a target="_blank" href="https://github.com/skylarkcob">skylarkcob</a>', '<a target="_blank" href="https://github.com/flyenuol">flyenuol</a>'); ?></p>
    <p><?php printf(__('Nếu bạn thấy mã nguồn này hữu ích, bạn có thể ủng hộ cho các lập trình viên bằng cách gửi một cốc bia hoặc một tách cà phê thông qua cổng thanh toán PayPal. Điều này sẽ giúp SB Team có được chi phí và động lực để phát triển nhiều tính năng hơn nữa cho SB Framework. Bạn có thể ghé thăm trang %s để biết thêm thông tin chi tiết.', 'sb-theme'), sprintf('<a target="_blank" href="http://hocwp.net/donate">%s</a>', __('ủng hộ', 'sb-theme'))); ?></p>
    <p class="sb-donate"><?php echo '<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WQSLEH5EPHJ7E"><img alt="" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" ></a>'; ?></p>
</div>