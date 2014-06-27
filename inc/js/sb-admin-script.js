jQuery(document).ready(function($){
	var postWidget = $("div.sb-post-widget");
	$("body").find(postWidget).each(function(){
		var aPostWidget = $(this);
		aPostWidget.find("select.sb-post-type").on("change", function(){
			if("category" == $(this).val()) {
				aPostWidget.find("#sbPostCats").fadeIn();
			} else {
				aPostWidget.find("#sbPostCats").fadeOut();
			}
		});
	});	
});