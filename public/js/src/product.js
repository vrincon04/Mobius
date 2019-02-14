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
                        var active = (obj.is_active == 1) ? "col-green" : "col-red",
                            icon = (obj.is_stock == 1) ? "boxes" : "concierge-bell";
                        return `<a href="${$.LeonSoft.options.URL}/product/view/${obj.id}" target="_blank"><i class='fa fa-${icon} ${active} font-18' aria-hidden='true' style='vertical-align: middle;'></i> ${value}</a>`;
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
                /*{
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
                },*/
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
        var $stock_switch = $('#stock-switch'),
            $composed_switch = $('#composed-switch'),
            $stock_header = $('#stock_header'),
            $stock_body = $('#stock_body'),
            $composed_header = $('#composed_header'),
            $composed_body = $('#composed_body'),
            $cost = $('input[name="cost"]');
        //Textarea auto growth.
        autosize($('textarea.auto-growth'));
        // Mascara para los input tipo moneda.
        $('.currency').maskMoney($.formatCurrency.regions[$.Language.region].currency);
        // Mascara para los input tipo numerico.
        $('.number').maskMoney($.formatCurrency.regions[$.Language.region].number);
        //
        $('#is_stock').on('click', function (e) {
            var $this = $(this);
            if ( $this.is(':checked') ) {
                $stock_header.show();
                $stock_body.show();
                $composed_switch.hide();
            } else {
                $stock_header.hide();
                $stock_body.hide();
                $composed_switch.show();
            }
        })

        $('#is_composed').on('click', function (e) {
            var $this = $(this);

            if ( $this.is(':checked') ) {
                $composed_header.show();
                $composed_body.show();
                $stock_switch.hide();
                $cost.prop('disabled', true);
            } else {
                $composed_header.hide();
                $composed_body.hide();
                $stock_switch.show();
                $cost.prop('disabled', false);
            }
        });

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

        if ($('#composedsForm').size() > 0) {
            $('#composedsForm').sheepIt({
                separator: '',
                allowRemoveLast: false,
                allowRemoveCurrent: true,
                allowRemoveAll: false,
                allowAdd: true,
                allowAddN: false,
                minFormsCount: 1,
                iniFormsCount: 1,
                data: $('#compoundsPre').data('compounds'),
                afterAdd: function(source, clone) {
                    var data = source.getOption('data'),
                        index = clone.getPosition(),
                        obj = (data[index - 1] !== void 0) ? data[index - 1] : { product_id: '-1', text: 'Choose An Option' },
                        newOption = new Option(obj.name, obj.product_id, false, false);
                    // Number and Currency format.
                    $('.number').maskMoney($.formatCurrency.regions[$.Language.region].number);
                    // Ajax Select.
                    $(".select2_product").select2({
                        theme: 'bootstrap',
                        language: $.Language.lang,
                        placeholder: $.Language.message.choose_product,
                        width: '100%',
                        minimumInputLength: 2,
                        ajax: {
                            delay: 250, // wait 250 milliseconds before triggering the request
                            url: `${$.LeonSoft.options.URL}/product/get_by_name_or_code_json`,
                            dataType: "json",
                            cache: "true",
                            type: "get",
                            data: function (param) {
                                var query = {
                                    term: param.term,
                                    is_stock: 1,
                                    page: param.page
                                }
            
                                return query;
                            },
                            processResults: function (elements) {
                                return {
                                    results: $.map(elements.data, function(element) {
                                        return {
                                            id: element.id, 
                                            text: element.name,
                                            obj: element
                                        }
                                    })
                                }
                            }
                        },
                        // Specify format function for dropdown item
                        templateResult: $.LeonSoft.templates.purchaseOrderItems,
                        templateSelection: function (data, container) {
                            if (typeof data.obj === 'undefined') {
                                return data.text;
                            }
                            var obj = data.obj;
                            var stock = obj.stocks.reduce(function (total, item) {
                                return parseFloat(total) + parseFloat(item.count)
                              }, 0)
                            // Add custom attributes to the <option> tag for the selected option
                            $(data.element).attr('data-in_stock', stock);
                            $(data.element).attr('data-cost', obj.cost);
                            $(data.element).attr('data-sale', obj.sale);
                            return data.text;
                        }
                    }).on('change', function(e){
                        var $selected = $('option:selected',this).data(),
                            $this = $(this),
                            $tr = $this.closest('tr'),
                            $costSpan = $tr.find('#item-total-cost'),
                            $quantityInput = $tr.find('input[id$="_quantity"]'),
                            $costInput = $tr.find('input[id$="_cost"]');
                        // Set a cost
                        $costSpan.text($.LeonSoft.helpers.formmatterCurrency($selected.cost));
                        // Set quantity
                        $quantityInput.val(1);
                        // Set cost
                        debugger;
                        $costInput.val($selected.cost);
                        $.LeonSoft.methods.getSubTotal('[id~=item-total-cost]', $('#main-total'));
                    }).append(newOption);
                    // Quantity event
                    $(clone).on('focusout', "[id$='_quantity']", function(){
                        var $tr = $(this).closest('tr'),
                            $quantity = $tr.find("[id$='_quantity']"),
                            $opticion = $tr.find("select option:checked").data(),
                            $element = $tr.find('#item-total-cost');

                        $.LeonSoft.methods.getTotalProduct($quantity.val().replace(/[^\d.-]/g,''), $opticion.cost, $element);
                        $.LeonSoft.methods.getSubTotal('[id~=item-total-cost]', $('#main-total'));
                    });
                },
                beforeRemoveCurrent: function(source, form) {
                    swal(
                        {
                            title: "Eliminar Componente",
                            text: "¿Está seguro que desea eliminar este componente?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Si, eliminar!",
                            closeOnConfirm: true
                        },
                        function(){
                            source.removeCurrentForm(form);
                            $.LeonSoft.methods.getSubTotal('[id~=item-total-cost]', $('#main-total'));
                        }
                    );
        
                    return false;
                }
            });
        }
    }
});
