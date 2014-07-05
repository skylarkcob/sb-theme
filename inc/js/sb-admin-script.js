jQuery(document).ready(function($){
	// Ẩn hoặc hiện danh sách chuyên mục cho SB Post Widget
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
	
	// Xóa sidebar đang chứa SB Tab Widget
	var sidebar = $('div.widgets-sortables');
	$('body').find(sidebar).each(function(){
		var aSidebar = $(this), sidebarId = aSidebar.attr("id");
		remove_parent_sidebar_in_tab(aSidebar);
		aSidebar.bind('DOMNodeInserted DOMNodeRemoved', function() {
			remove_parent_sidebar_in_tab(aSidebar);
		});
	});
	
	function remove_parent_sidebar_in_tab(selector) {
		var sidebarId = selector.attr("id");
		selector.find("#listSidebars").each(function(){
			var aListSidebar = $(this), sidebarOption = aListSidebar.find(".sb-list-sidebars option[value='" + sidebarId + "']");
			sidebarOption.remove();
		});
	}
});