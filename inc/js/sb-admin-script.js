jQuery(document).ready(function($){
	// Ẩn hoặc hiện danh sách chuyên mục cho SB Post Widget
	var postWidget = $("div.sb-post-widget");
	$("body").find(postWidget).each(function(){
		var aPostWidget = $(this);
		aPostWidget.find("select.sb-post-type").on("change", function(){
			var chooseType = $(this);
			var currentPostWidget = chooseType.closest("div.sb-post-widget");
			var listCats = currentPostWidget.find("#sbPostCats");
			if("category" == chooseType.val()) {
				listCats.delay(100).fadeIn();
			} else {
				listCats.delay(100).fadeOut();
			}
			
		});
		
		aPostWidget.find("select.sb-post-cat option").click(function(){
			var currentCatOption = $(this);
			var currentPostWidget = currentCatOption.closest("div.sb-post-widget");
			currentPostWidget.find("input.taxonomy").val(currentCatOption.attr("data-taxonomy"));
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
	
	// Trang cài đặt, tùy chỉnh giao diện
	var sbOption = $("div.sb-option");
	if(sbOption.length) {
		var uploadCaller = null;
		var formField;
		sbOption.find("a.insert-media").each(function(){
			var insertMediaButton = $(this);
			insertMediaButton.click(function(){
				uploadCaller = $(this).closest("div.sbtheme-upload").find("input");
				formField = uploadCaller.attr("name");
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
				return false;
			});
		});
		
		window.original_send_to_editor = window.send_to_editor;
		window.send_to_editor = function(html) {
			if(formField) {
				var imageUrl = $('img',html).attr('src');
				uploadCaller.val(imageUrl);
				tb_remove();
				var mediaThumbnailBox = uploadCaller.closest("div.sbtheme-media-image").find("div.sbtheme.media.image");
				mediaThumbnailBox.addClass("uploaded");
				mediaThumbnailBox.html('<img src="' + imageUrl + '">');
				formField = '';
			} else {
				window.original_send_to_editor(html);
			}
		}
		
		sbOption.find("div.sbtheme-media-image").each(function(){
			var mediaGroup = $(this);
			var mediaUrl = mediaGroup.find("div.sbtheme-upload input").attr("value");
			var imageHTML = mediaGroup.find("div.sbtheme.media.image");

			if("" == mediaUrl && imageHTML.html().length > 1) {
				imageHTML.removeClass("uploaded");
				imageHTML.empty();
			}
			
		});

		sbOption.find("a.sbtheme-group-tab").each(function(){
			var aSectionTab = $(this);
			
			aSectionTab.click(function(){
				var currentSection = $(this).closest("li"), dataSection = $(this).attr("data-section");
				if('sbtheme_aboutsb_section' == dataSection) {
					sbOption.find("p.submit").css("display", "none");
				} else {
					sbOption.find("p.submit").css("display", "block");
				}
				if(currentSection.hasClass("active")) {
					return false;
				}
				$(this).closest("ul.sbtheme-list-section").find("li").removeClass("active");
				currentSection.addClass("active");
				
				sbOption.find("div.sbtheme-option-section").each(function(){
					$(this).removeClass("active");
				});
				
				var currentSectionContent = sbOption.find("#" + dataSection);

				if(currentSectionContent.length) {
					currentSectionContent.addClass("active");
				}
				$.post(sbAdminAjax.url, {action: 'my_action', data_section: dataSection}, function(response){
				});
			});
		});
		
		sbOption.find("label.switch-button").each(function(){
			var aSwitchButton = $(this);
			aSwitchButton.click(function(){
				var dataSwitch = "on", switchValue = 0, currentDataSwitch = $(this).attr('data-switch');
				if(dataSwitch == currentDataSwitch) {
					dataSwitch = 'off';
					switchValue = 1;
				}
				var otherButton = $(this).closest('div.switch-options').find("[data-switch='" + dataSwitch + "']");
				otherButton.removeClass("active");
				$(this).addClass("active");
				$(this).closest('div.switch-options').find("input").val(switchValue);
			});
		});
		
		var currentTab = sbOption.find("div.sbtheme-option-section.active");
		if(currentTab.length && "sbtheme_aboutsb_section" == currentTab.attr("id")) {
			sbOption.find("p.submit").css("display", "none");
		}
		
	}
	
	var sbWidget = $("body").find("div.sb-widget");
	if(sbWidget.length) {
		var formField;
		sbWidget.find("a.insert-media").each(function(){
			var uploadCaller = null;
			
			var uploadButton = $(this);
			uploadCaller = uploadButton.closest("p");
			
			uploadButton.click(function(){
				formField = $(this).parent().find("input");
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
				return false;
			});
		});
		//window.original_send_to_editor = window.send_to_editor;
		window.send_to_editor = function(html) {
			if(formField) {
				var imageUrl = $('img', html).attr('src');
				formField.val(imageUrl);
				formField = '';
			} else {
				//window.original_send_to_editor(html);
			}
			tb_remove();
		}
	}
});

jQuery( document ).ajaxComplete( function( event, XMLHttpRequest, ajaxOptions ) {
    
});