<?php
$profile_id = SB_Option::get_theme_option(array('keys' => array('addthis_profile_id')));
if(empty($profile_id)) {
    $profile_id = 'ra-4e8109ea4780ac8d';
}
?>
<script type="text/javascript">
    var addthis_config = {"data_track_clickback":false,"data_track_addressbar":false,"data_track_textcopy":false,"ui_atversion":"300"};
</script>
<script src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $profile_id; ?>" type="text/javascript"></script>