<?php
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
<script type="text/javascript" src="<?php echo SB_THEME_LIB_URL; ?>/addthis/addthis_widget.js#async=1&amp;pubid=<?php echo $profile_id; ?>" async defer></script>