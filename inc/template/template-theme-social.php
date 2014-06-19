<?php $class = "social"; ?>
<?php
global $social_class;
$class = trim($class.' '.$social_class);
?>
<ul class="<?php echo $class; ?>">
	<?php global $sbwp_options; ?>
	<?php if($sbwp_options['facebook']) : ?>
	<li class="social-item facebook no-padding-left"><a target="_blank" href="<?php echo $sbwp_options['facebook']; ?>"><i class="fa fa-facebook"></i></a></li>
	<?php endif; if($sbwp_options['youtube']) : ?>
	<li class="social-item youtube"><a target="_blank" href="<?php echo $sbwp_options['youtube']; ?>"><i class="fa fa-youtube"></i></a></li>
	<?php endif; if($sbwp_options['twitter']) : ?>
	<li class="social-item twitter"><a target="_blank" href="<?php echo $sbwp_options['twitter']; ?>"><i class="fa fa-twitter"></i></a></li>
	<?php endif; if($sbwp_options['google_plus']) : ?>
	<li class="social-item google-plus googleplus gplus"><a target="_blank" href="<?php echo $sbwp_options['google_plus']; ?>"><i class="fa fa-google-plus"></i></a></li>
	<?php endif; if($sbwp_options['linkedin']) : ?>
	<li class="social-item linkedin"><a target="_blank" href="<?php echo $sbwp_options['linkedin']; ?>"><i class="fa fa-linkedin"></i></a></li>
	<?php endif; if($sbwp_options['pinterest']) : ?>
	<li class="social-item pinterest"><a target="_blank" href="<?php echo $sbwp_options['pinterest']; ?>"><i class="fa fa-pinterest"></i></a></li>
	<?php endif; if($sbwp_options['rss']) : ?>
	<li class="social-item rss no-padding-right"><a target="_blank" href="<?php echo $sbwp_options['rss']; ?>"><i class="fa fa-rss"></i></a></li>
	<?php endif; ?>
</ul>