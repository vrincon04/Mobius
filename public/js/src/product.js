$(function () {
    var $productTable = $('#products-table'),
        $productForm = $('#product-form');

    // Validamos si existe una tabla en la vista
    if ( $productTable.size() > 0 ) {
        $productTable.DataTable($.extend(true, {}, $.LeonSoft.options.DATATABLE_TEMPLATE, {
            ajax: {
                url: `${$.LeonSoft.options.URL}/product/datatable_json`
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
                { data: 'id' },
                { 
                    data: 'code',
                    orderable: false,
                },
                {
                    data: 'name',
                    render: function(value, type, obj, meta) {
                        var active = (obj.is_active) ? "col-green" : "col-red";
                        return `<a href="${$.LeonSoft.options.URL}/product/view/${obj.id}" target="_blank"><i class='material-icons ${active} font-18' aria-hidden='true' style='vertical-align: middle;'>album</i> ${value}</a>`;
                    }
                },
                { 
                    data: 'category' ,
                    render: function(value, type, obj, meta) {
                        return `<a href="${$.LeonSoft.options.URL}/category/view/${obj.category_id}" target="_blank">${value}</a>`;
                    }
                },
                {
                    data: 'sale',
                    render: function(value, type, obj, meta) {
                        return $.LeonSoft.helpers.formmatterCurrency(value)
                    },
                    class: 'text-right',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'wholesale_price',
                    render: function(value, type, obj, meta) {
                        return $.LeonSoft.helpers.formmatterCurrency(value)
                    },
                    class: 'text-right',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'quantity_wholesale',
                    render: function(value, type, obj, meta) {
                        return $.LeonSoft.helpers.formmatterNumber(value)
                    },
                    class: 'text-right',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'cost',
                    render: function(value, type, obj, meta) {
                        return $.LeonSoft.helpers.formmatterCurrency(value)
                    },
                    class: 'text-right',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'stock',
                    render: function(value, type, obj, meta) {
                        return $.LeonSoft.helpers.formmatterNumber(value)
                    },
                    class: 'text-right',
                    orderable: false,
                    searchable: false
                },
                {
                    data: function(data) {
                        return `
                            <div class="btn-group font-10">
                                <a href="${$.LeonSoft.options.URL}/product/edit/${data.id}" target="_blank" class="btn btn-info btn-xs waves-effect" data-toggle="tooltip" data-original-title="${$.Language.message.edit}">
                                    <i class="material-icons">edit</i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-danger btn-xs delete-btn waves-effect" data-id="${data.id}" data-controller="product" data-toggle="tooltip" data-original-title="${$.Language.message.eliminate}">
                                    <i class="material-icons">delete</i>
                                </a>
                            </div>
                        `;
                    },
                    orderable: false,
                    searchable: false
                }
            ]
        }));
    
    }

    // Validamos si existe un formulario en la vista.
    if ( $productForm.size() > 0 ) {
        //Textarea auto growth
        autosize($('textarea.auto-growth'));

        $('.currency').maskMoney($.formatCurrency.regions[$.Language.region].currency);
        $('.number').maskMoney($.formatCurrency.regions[$.Language.region].number);

        if ($('#warehousesForm').size() > 0) {
            $('#warehousesForm').sheepIt({
                separator: '',
                allowRemoveLast: false,
                allowRemoveCurrent: true,
                allowRemoveAll: false,
                allowAdd: true,
                allowAddN: false,
                minFormsCount: 1,
                iniFormsCount: 1,
                data: $('#warehousesPre').data('warehouses'),
                afterAdd: function(source, clone) {
                    $('.currency').maskMoney($.formatCurrency.regions[$.Language.region].currency);
                    $('.number').maskMoney($.formatCurrency.regions[$.Language.region].number);
                },
                beforeRemoveCurrent: function(source, form) {
                    swal(
                        {
                            title: "Eliminar Contacto",
                            text: "¿Está seguro que desea eliminar este contacto?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Si, eliminar!",
                            closeOnConfirm: true
                        },
                        function(){
                            source.removeCurrentForm(form);
                        }
                    );
        
                    return false;
                }
            });
        }
    }
});
