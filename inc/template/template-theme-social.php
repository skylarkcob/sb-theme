<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<?php $class = "social"; ?>
<?php
global $social_class;
$class = trim($class.' '.$social_class);
?>
<ul class="<?php echo $class; ?>">
	<?php $option = new SB_Option(); ?>
	<?php $url = $option->get_facebook_uri(); ?>
	<?php if($url) : ?>
	<li class="social-item facebook no-padding-left"><a target="_blank" href="<?php echo $url; ?>"><i class="fa fa-facebook"></i></a></li>
	<?php endif; $url = $option->get_youtube_uri(); if($url) : ?>
	<li class="social-item youtube"><a target="_blank" href="<?php echo $url; ?>"><i class="fa fa-youtube"></i></a></li>
	<?php endif; $url = $option->get_twitter_uri(); if($url) : ?>
	<li class="social-item twitter"><a target="_blank" href="<?php echo $url; ?>"><i class="fa fa-twitter"></i></a></li>
	<?php endif; $url = $option->get_gplus_uri(); if($url) : ?>
	<li class="social-item google-plus googleplus gplus"><a target="_blank" href="<?php echo $url; ?>"><i class="fa fa-google-plus"></i></a></li>
	<?php endif; $url = $option->get_linkedin_uri(); if($url) : ?>
	<li class="social-item linkedin"><a target="_blank" href="<?php echo $url; ?>"><i class="fa fa-linkedin"></i></a></li>
	<?php endif; $url = $option->get_pinterest_uri(); if($url) : ?>
	<li class="social-item pinterest"><a target="_blank" href="<?php echo $url; ?>"><i class="fa fa-pinterest"></i></a></li>
	<?php endif; $url = $option->get_rss_uri(); if($url) : ?>
	<li class="social-item rss no-padding-right"><a target="_blank" href="<?php echo $url; ?>"><i class="fa fa-rss"></i></a></li>
	<?php endif; ?>
</ul>