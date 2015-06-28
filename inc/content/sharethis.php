<?php
$publisher_key = SB_Option::get_theme_option_single_key('sharethis_pub_key');
if(empty($publisher_key)) {
    $publisher_key = '2867e452-48fd-4b6a-a950-268a03d1179b';
}
echo '<script type="text/javascript" async>var switchTo5x=true;</script>';
echo '<script type="text/javascript" src="' . SB_THEME_LIB_URL . '/sharethis/buttons.js" async></script>';
echo '<script type="text/javascript" async>stLight.options({publisher: "' . $publisher_key . '", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>';