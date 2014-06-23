jQuery(document).ready(function($){
	
	var sfMenu = $('ul.sf-menu');
	if(sfMenu.length) {
		$("body").find(sfMenu).each(function(){
			$(this).superfish();
		});
	}
	
	var bootstrapCarousel = $('.carousel');
	if(bootstrapCarousel.length) {
		$("body").find(bootstrapCarousel).each(function(){
			$(this).carousel();
		});
	}
	
	var wooProductImage = $(".wp-post-image");
	if(wooProductImage.length) {
		$("body").find(wooProductImage).each(function(){
			$(this).on('click', function(){
				setTimeout(function(){
					$(".pp_pic_holder.pp_woocommerce").find(".ppt").removeAttr('style');
				}, 500);
			});
		});
	}
	


});