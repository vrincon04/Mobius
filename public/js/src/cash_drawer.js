$(function () {
    var $cash_drawerTable = $('#cash_drawers-table'),
        $cash_drawerForm = $('#cash_drawer-form');

    // Validamos si existe una tabla en la vista
    if ( $cash_drawerTable.size() > 0 ) {
        $cash_drawerTable.DataTable($.extend(true, {}, $.LeonSoft.options.DATATABLE_TEMPLATE, {
            ajax: {
                url: `${$.LeonSoft.options.URL}/cash_drawer/datatable_json`
            },
            columnDefs: [
                {
                    orderable: true,
                    targets: 0
                }
            ],
            order: [ 
                [0, 'desc'] 
            ],
            columns: [
                { 
                    data: 'id' ,
                    render: function(value, type, obj, meta) {
                        return `<a href="${$.LeonSoft.options.URL}/cash_drawer/view/${obj.id}" target="_blank">CR${value.padStart(6, "0")}</a>`;
                    }
                },
                { data: 'name' },
                { data: 'open_name' },
                { data: 'close_name' },
                { 
                    data: 'status',
                    render: function(value, type, obj, meta) {
                        return `<span class="${value}">${$.Language.message[value]}</span>`;
                    }
                 },
                { 
                    data: 'opened_at',
                    render: function(value, type, obj, meta) {
                        return $.LeonSoft.helpers.niceDateTime(value);
                    }
                },
                { data: 'closed_at'}
            ]
        }));
    
    }

    // Validamos si existe un formulario en la vista.
    if ( $cash_drawerForm.size() > 0 ) {
        // Mascara para los input tipo moneda.
        $('.currency').maskMoney($.formatCurrency.regions[$.Language.region].currency);
    }
});
