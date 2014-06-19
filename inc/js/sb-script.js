jQuery(document).ready(function($){
	$('ul.sf-menu').superfish();
	$('.carousel').carousel();	
	$(".wp-post-image").on('click', function(){
		setTimeout(function(){
			$(".pp_pic_holder.pp_woocommerce").find(".ppt").removeAttr('style');
		}, 500);
	});	
});