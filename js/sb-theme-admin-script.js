(function($){
    (function(){
        $( '#sortable-list-cat, #sortable-list-cat-active' ).sortable({
            connectWith: '.connectedSortable',
            placeholder: 'ui-state-highlight',
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