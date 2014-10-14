(function($){
    (function(){
        $('#sortable-list-cat, #sortable-list-cat-active').sortable({
            connectWith: '.connectedSortable',
            placeholder: 'ui-state-highlight',
            sort: function(event, ui) {
                var that = $(this);
                that.find('.ui-state-highlight').css({'height': ui.item.height()});
            },
            stop: function(event, ui) {
                var data = '';

                $('#sortable-list-cat-active li').each(function(i, el){
                    var p = $(el).attr('data-category');
                    data += p + ',';
                });
                data = data.slice(0, -1);
                $('div.sb-sortable-list input').val(data);
            }
        }).disableSelection();
    })();
})(jQuery);