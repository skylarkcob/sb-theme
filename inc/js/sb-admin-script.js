jQuery(document).ready(function($){
	// Biến field chứa đường dẫn image từ media
	var formField;
    var body = $("body");
    var fileFrame = null, newPostID = 0, oldPostID = null;

    oldPostID = wp.media.model.settings.post.id;

	/*
	 *	SB Post Widget
	 */
	 
	// Ẩn hoặc hiện danh sách chuyên mục cho SB Post Widget
	body.delegate("select.sb-post-type", "change", function(){
		var listCats = $(this).parent().parent().find("p.post-cat");
		if("category" == $(this).val()) {
			listCats.delay(10).fadeIn();
		} else {
			listCats.delay(10).fadeOut();
		}
	});
	
	body.delegate("select.sb-post-cat option", "click", function(){
		var taxonomy = $(this).attr("data-taxonomy"), inputTaxonomy = $(this).closest("div.sb-post-widget").find("input.taxonomy");
		inputTaxonomy.val(taxonomy);
	});
	
	body.delegate("input.sb-checkbox", "click", function(){
		var parentClass = $(this).parent().attr("class");
		switch(parentClass) {
			case 'only-thumbnail':
				if($(this).is(':checked')) {
					only_thumbnail_checked($(this), true);
				} else {
					only_thumbnail_checked($(this), false);
				}
				break;
			case 'show-excerpt':
				if($(this).is(':checked')) {
					$(this).parent().parent().find("p.excerpt-length").fadeIn();
				} else {
					$(this).parent().parent().find("p.excerpt-length").fadeOut();
				}
				break;
		}
	});
	
	function only_thumbnail_checked(selector, value) {
		var postWidget = selector.parent().parent();
		if(true == value) {
			postWidget.find("p.show-excerpt").fadeOut();
			postWidget.find("p.excerpt-length").fadeOut();
		} else {
			postWidget.find("p.show-excerpt").fadeIn();
			if(postWidget.find("p.show-excerpt input").is(":checked")) {
				postWidget.find("p.excerpt-length").fadeIn();
			}
		}
	}
	
	/*
	 *	SB Tab Widget
	 */
	
	// Xóa sidebar đang chứa SB Tab Widget
	var sidebar = $('div.widgets-sortables');
	$('body').find(sidebar).each(function(){
		var aSidebar = $(this);
		remove_parent_sidebar_in_tab(aSidebar);
		aSidebar.bind('DOMNodeInserted DOMNodeRemoved', function() {
			remove_parent_sidebar_in_tab(aSidebar);
		});
	});
	
	function remove_parent_sidebar_in_tab(selector) {
		var sidebarId = selector.attr("id");
		selector.find("p.list-sidebar").each(function(){
			var aListSidebar = $(this), sidebarOption = aListSidebar.find(".sb-list-sidebars option[value='" + sidebarId + "']");
			sidebarOption.remove();
		});
	}
	
	/*
	 *	SB Option Panel
	 */
	
	// Trang cài đặt, tùy chỉnh giao diện
	var sbOption = $("div.sb-option");
	if(sbOption.length) {

		sbOption.find("a.sb-insert-media").each(function(){
			var insertMediaButton = $(this);
			insertMediaButton.click(function(event){
                event.preventDefault();

                formField = $(this).closest("div.sbtheme-upload").find("input");

                if(fileFrame) {
                    fileFrame.uploader.uploader.param( 'post_id', newPostID );
                    fileFrame.open();
                    return;
                }
                fileFrame = wp.media({title: 'Insert Media', button:{text: 'Use this image'}, multiple: false});
                fileFrame.on("select", function(){
                    var mediaData = fileFrame.state().get("selection").first().toJSON();
                    if(formField) {
                        var imageSource = mediaData.url;
                        formField.val(imageSource);

                        var mediaThumbnailBox = formField.closest("div.sbtheme-media-image").find("div.sbtheme.media.image");
                        if(mediaThumbnailBox.length) {
                            mediaThumbnailBox.addClass("uploaded");
                            mediaThumbnailBox.html('<img src="' + imageSource + '">');
                        }

                        formField = '';
                    }
                    wp.media.model.settings.post.id = oldPostID;
                });
                fileFrame.open();
			});
		});
		
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
			
			aSectionTab.click(function(e){
				var currentSection = $(this).closest("li"), dataSection = $(this).attr("data-section");
				if('sbtheme_aboutsb_section' == dataSection) {
					sbOption.find("p.submit").css("display", "none");
				} else {
					sbOption.find("p.submit").css("display", "block");
				}
				if(currentSection.hasClass("active")) {
                    e.preventDefault();
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

	/*
	 *	SB Banner Widget
	 */
	
	// Xử lý nút upload hình ảnh trong widget
	body.delegate("div.sb-widget .sb-insert-media", "click", function(event){
        event.preventDefault();

		formField = $(this).parent().find("input");
        if(fileFrame) {
            fileFrame.uploader.uploader.param( 'post_id', newPostID );
            fileFrame.open();
            return;
        }
        fileFrame = wp.media({title: 'Insert Media', button:{text: 'Use this image'}, multiple: false});
        fileFrame.on("select", function(){
            var mediaData = fileFrame.state().get("selection").first().toJSON();
            if(formField) {
                var imageSource = mediaData.url;
                formField.val(imageSource);

                var mediaThumbnailBox = formField.closest("div.sbtheme-media-image").find("div.sbtheme.media.image");
                if(mediaThumbnailBox.length) {
                    mediaThumbnailBox.addClass("uploaded");
                    mediaThumbnailBox.html('<img src="' + imageSource + '">');
                }

                formField = '';
            }
            wp.media.model.settings.post.id = oldPostID;
        });
        fileFrame.open();
	});

});