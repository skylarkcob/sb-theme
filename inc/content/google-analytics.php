<?php
defined('ABSPATH') or die('Please do not pip me!');

$ga_id = SB_Option::get_theme_option_single_key('google_analytics');
if(empty($ga_id)) {
    return;
}
?>
<script type="text/javascript">
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.defer=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', '<?php echo $ga_id; ?>', 'auto');
    ga('send', 'pageview');
</script>