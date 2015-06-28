<?php
do_action('sb_theme_site_container_after');
echo '</div>'; // End .sb-site-container
echo '</div>'; // End .sb-site
do_action('sb_theme_footer_before');
wp_footer();
do_action('sb_theme_body_after');
echo '</body>';
echo '</html>';