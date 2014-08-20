(function($){
	// Biến field chứa đường dẫn image từ media
	var formField,
        body = $("body"),
        fileFrame = null,
        newPostID = 0,
        oldPostID = wp.media.model.settings.post.id;

	/*
	 *	SB Post Widget
	 */
    (function(){
        // Ẩn hoặc hiện danh sách chuyên mục cho SB Post Widget
        body.delegate("select.sb-post-type", "change", function(){
            var that = $(this),
                listCats = that.parent().parent().find("p.post-cat");

            if("category" == that.val()) {
                listCats.delay(10).fadeIn();
            } else {
                listCats.delay(10).fadeOut();
            }
        });
    })();

    (function(){
        body.delegate("select.sb-post-cat option", "click", function(){
            var that = $(this),
                taxonomy = that.attr("data-taxonomy"),
                inputTaxonomy = that.closest("div.sb-post-widget").find("input.taxonomy");

            inputTaxonomy.val(taxonomy);
        });
    })();

    (function(){
        function onlyThumbnailCheck(selector, value) {
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
        body.delegate("input.sb-checkbox", "click", function(){
            var that = $(this),
                parentClass = that.parent().attr("class");

            switch(parentClass) {
                case 'only-thumbnail':
                    if(that.is(':checked')) {
                        onlyThumbnailCheck(that, true);
                    } else {
                        onlyThumbnailCheck(that, false);
                    }
                    break;
                case 'show-excerpt':
                    if(that.is(':checked')) {
                        that.parent().parent().find("p.excerpt-length").fadeIn();
                    } else {
                        that.parent().parent().find("p.excerpt-length").fadeOut();
                    }
                    break;
            }
        });
    })();

	

	
	/*
	 *	SB Tab Widget
	 */

    (function(){
        // Xóa sidebar đang chứa SB Tab Widget
        var sidebar = $('div.widgets-sortables');
        body.find(sidebar).each(function(){
            var that = $(this);

            removeParentSidebarInTab(that);
            that.bind('DOMNodeInserted DOMNodeRemoved', function() {
                removeParentSidebarInTab(that);
            });
        });

        function removeParentSidebarInTab(selector) {
            var sidebarId = selector.attr("id");

            selector.find("p.list-sidebar").each(function(){
                var that = $(this),
                    sidebarOption = that.find(".sb-list-sidebars option[value='" + sidebarId + "']");

                sidebarOption.remove();
            });
        }
    })();

	
	/*
	 *	SB Option Panel
	 */
	
    (function(){
        // Trang cài đặt, tùy chỉnh giao diện
        var sbOption = $("div.sb-option");
        if(sbOption.length) {

            var currentTab = sbOption.find("div.sbtheme-option-section.active");

            sbOption.find("a.sb-insert-media").each(function(){
                var that = $(this);
                that.click(function(event){
                    that = $(this);
                    event.preventDefault();

                    formField = that.closest("div.sbtheme-upload").find("input");

                    if(fileFrame) {
                        fileFrame.uploader.uploader.param( 'post_id', newPostID );
                        fileFrame.open();
                        return;
                    }
                    fileFrame = wp.media({title: 'Insert Media', button:{text: 'Use this image'}, multiple: false});
                    fileFrame.on("select", function(){
                        sbSetImageUpload(fileFrame, formField);
                        formField = '';
                    });
                    fileFrame.open();
                });
            });

            sbOption.find("div.sbtheme-media-image").each(function(){
                var that = $(this),
                    mediaUrl = that.find("div.sbtheme-upload input").attr("value"),
                    imageHTML = that.find("div.sbtheme.media.image");

                if("" == mediaUrl && imageHTML.html().length > 1) {
                    imageHTML.removeClass("uploaded");
                    imageHTML.empty();
                }

            });

            sbOption.find("a.sbtheme-group-tab").each(function(){
                var that = $(this);

                that.click(function(event){
                    that = $(this);
                    var currentSection = $(this).closest("li"), dataSection = $(this).attr("data-section");
                    if('sbtheme_aboutsb_section' == dataSection) {
                        sbOption.find("p.submit").css("display", "none");
                    } else {
                        sbOption.find("p.submit").css("display", "block");
                    }
                    if(currentSection.hasClass("active")) {
                        event.preventDefault();
                    }
                    that.closest("ul.sbtheme-list-section").find("li").removeClass("active");
                    currentSection.addClass("active");

                    sbOption.find("div.sbtheme-option-section").each(function(){
                        that = $(this);
                        that.removeClass("active");
                    });

                    var currentSectionContent = sbOption.find("#" + dataSection);

                    if(currentSectionContent.length) {
                        currentSectionContent.addClass("active");
                    }
                    $.post(sbAdminAjax.url, {action: 'sb_option_action', data_section: dataSection}, function(response){
                    });
                });
            });

            sbOption.find("label.switch-button").each(function(){
                var that = $(this);
                that.click(function(){
                    that = $(this);
                    var dataSwitch = "on",
                        switchValue = 0,
                        currentDataSwitch = that.attr('data-switch'),
                        otherButton;

                    if(dataSwitch == currentDataSwitch) {
                        dataSwitch = 'off';
                        switchValue = 1;
                    }

                    otherButton = that.closest('div.switch-options').find("[data-switch='" + dataSwitch + "']");
                    otherButton.removeClass("active");
                    that.addClass("active");
                    that.closest('div.switch-options').find("input").val(switchValue);
                });
            });

            if(currentTab.length && "sbtheme_aboutsb_section" == currentTab.attr("id")) {
                sbOption.find("p.submit").css("display", "none");
            }

        }
    })();

	/*
	 *	SB Banner Widget
	 */
	
    (function(){
        // Xử lý nút upload hình ảnh trong widget
        body.delegate("div.sb-widget .sb-insert-media", "click", function(event){
            event.preventDefault();
            var that = $(this);
            formField = that.parent().find("input");
            if(fileFrame) {
                fileFrame.uploader.uploader.param( 'post_id', newPostID );
                fileFrame.open();
                return;
            }
            fileFrame = wp.media({title: 'Insert Media', button:{text: 'Use this image'}, multiple: false});
            fileFrame.on("select", function(){
                sbSetImageUpload(fileFrame, formField);
                formField = '';
            });
            fileFrame.open();
        });
    })();

    function sbSetImageUpload(fileFrame, formField) {
        var mediaData = fileFrame.state().get("selection").first().toJSON();
        if(formField) {
            var imageSource = mediaData.url,
                mediaThumbnailBox = formField.closest("div.sbtheme-media-image").find("div.sbtheme.media.image");

            formField.val(imageSource);

            if(mediaThumbnailBox.length) {
                mediaThumbnailBox.addClass("uploaded");
                mediaThumbnailBox.html('<img src="' + imageSource + '">');
            }

        }
        wp.media.model.settings.post.id = oldPostID;
    }

})(jQuery);