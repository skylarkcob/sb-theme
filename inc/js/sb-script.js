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
	
	var embedBox = $("div.file-embed");
	$("body").find(embedBox).each(function(){
		$(this).bind("mousewheel", function(){
			return false;
		});
	});
	
	// Nút trở về đầu trang
	var scrollToTop = $( "#scroll-to-top" );
	
	if ( scrollToTop.length ) {
		var scrollPositionShown = 100;
		
		function show_scroll_to_top() {
			scrollToTop.fadeIn();
		}
		
		if ( $( window ).scrollTop() > scrollPositionShown ) {
			show_scroll_to_top();
		}
		
		$( window ).scroll( function() {
			if ( $( this ).scrollTop() > scrollPositionShown ) {
				show_scroll_to_top();
			} else {
				scrollToTop.fadeOut();
			}
		});
		
		scrollToTop.click(function(){
			$('html, body').animate({scrollTop : 0},800);
			return false;
		});
		
	}

});