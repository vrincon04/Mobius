$(document).ready(function () {

    $('input[type=checkbox].check-all').each(function () {
        var action = $(this).data('action');
        var actives = false;
        var unactives = false;
        $(this).closest('table').find('tbody tr td input[data-action='+action+']').not(':disabled').each(function () {
            if ($(this).is(':checked')) {
                actives = true;
            } else {
                unactives = true;
            }
        });

        if ( actives && unactives ) {
            $(this).prop('indeterminate', true);
        } else if( actives ) {
            $(this).prop('checked', true);
        }
    });

    $('input[type=checkbox].check-all').on('change', function () {
        var action = $(this).data('action');
        var check_input = $(this).is(':checked');
        $(this).closest('table').find('tbody tr td input[data-action='+action+']').not(':disabled').each(function () {
           if (check_input) {
               $(this).prop('checked', true);
           } else {
               $(this).removeProp('checked');
           }
        });
    });

    $('input[type=checkbox].check-action').on('change', function () {
        var action = $(this).data('action');
        var actives = false;
        var unactives = false;
        $(this).closest('tbody').find('tr td input[data-action='+action+']').not(':disabled').each(function () {
            if ($(this).is(':checked')) {
                actives = true;
            } else {
                unactives = true;
            }
        });

        var $check_all = $(this).closest('table').find('thead tr th input[data-action='+action+']').first();

        if ( actives && unactives ) {
            $check_all.removeProp('checked');
            $check_all.prop('indeterminate', true);
        } else if( actives ) {
            $check_all.removeProp('indeterminate');
            $check_all.prop('checked', true);
        } else {
            $check_all.removeProp('indeterminate');
            $check_all.removeProp('checked');
        }

    });

    if ( $('#cabecera').size() > 0 ) { $('#cabecera').sticky({topSpacing:70,zIndex:9}); }

    $('#cabecera th').each(function (index, element) {
        $(element).width($('table.table-striped').first().find('thead th').eq(index).width());
    });

});