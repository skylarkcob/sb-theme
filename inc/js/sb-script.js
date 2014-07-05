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
	
	// SB Tab Widget
	$("body").find(".sb-tab-widget").each(function(){
		var aTab = $(this), listTab = aTab.find("ul.list-tab"), tabContent = aTab.find("div.tab-content");
		tabContent.children("section.tab-item").each(function(){
			var widgetId = $(this).attr("id");
			$(this).find('a.tab-title').attr("href", "#" + widgetId).wrap('<li></li>').parent().detach().appendTo(listTab);
		});
		listTab.find("li:first-child").addClass("active");
		tabContent.find("section:first-child").addClass("active");
		if(listTab.is(":empty")) {
			listTab.closest("div").find("div.tab-content").css("margin-top", 0);
		}
	});

});