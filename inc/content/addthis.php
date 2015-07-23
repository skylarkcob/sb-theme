<?php
defined('ABSPATH') or die('Please do not pip me!');

$profile_id = SB_Option::get_theme_option(array('keys' => array('addthis_profile_id')));
if(empty($profile_id)) {
    $profile_id = 'ra-4e8109ea4780ac8d';
}
?>
<script type="text/javascript">
    var addthis_config = addthis_config || {};
    addthis_config.ui_language = 'vi';
    addthis_config.data_track_addressbar = false;
    addthis_config.data_track_clickback = false;
    addthis_config.data_track_textcopy = false;
    addthis_config.ui_atversion = 300;
    addthis_config.pubid = <?php echo $profile_id; ?>;
    addthis_config.async = 1;
</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $profile_id; ?>" async="async"></script>