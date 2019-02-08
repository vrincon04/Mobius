$(function () {
    var $purchase_orderTable = $('#purchase_orders-table'),
        $purchase_orderForm = $('#purchase_order-form');

    // Validamos si existe una tabla en la vista
    if ( $purchase_orderTable.size() > 0 )
    {
        $purchase_orderTable.removeAttr('width').DataTable($.extend(true, {}, $.LeonSoft.options.DATATABLE_TEMPLATE, {
            ajax: {
                url: `${$.LeonSoft.options.URL}/purchase_order/datatable_json`
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
                    data: 'id',
                    width: '18%',
                    render: function(value, type, obj, meta) {
                        return `OC${value.padStart(6, "0")}`
                    }
                },
                { 
                    data: 'date',
                    width: '10%',
                    render: function(value, type, obj, meta) {
                        return $.LeonSoft.helpers.niceDate(value);
                    }
                },
                { 
                    data: function(data) {
                        return $.LeonSoft.helpers.shortName(data.first_name, data.last_name);
                    },
                    width: '15%',
                    orderable: false,
                    searchable: false
                },
                { 
                    data: 'status',
                    render: function(value, type, obj, meta) {
                        if (value == 'draft')
                            return `<span class="col-red">${$.Language.message[value]}</span>`;
                        
                        return $.Language.message[value];
                    },
                    width: '10%',
                    orderable: false,
                    searchable: false
                },
                {
                    data: function(data) {
                        return $.LeonSoft.templates.progress(data, '10px')
                    },
                    width: '10%',
                    orderable: false,
                    searchable: false
                },
                { 
                    data: 'expected_at',
                    render: function(value, type, obj, meta) {
                        return $.LeonSoft.helpers.niceDate(value);
                    },
                    searchable: false,
                    width: '14%'
                },
                { 
                    data: 'total',
                    width: '12%',
                    class: 'text-right',
                    render: function(value) {
                        return $.LeonSoft.helpers.formmatterCurrency(value)
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: function(data) {
                        return `
                            <div class="btn-group font-10">
                                <a href="${$.LeonSoft.options.URL}/purchase_order/view/${data.id}" class="btn btn-success btn-xs waves-effect" data-toggle="tooltip" data-original-title="${$.Language.message.view}">
                                    <i class="material-icons">remove_red_eye</i>
                                </a>
                            </div>
                        `;
                    },
                    width: '7%',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'first_name',
                    visible: false
                },
                {
                    data: 'last_name',
                    visible: false
                },
            ]
        }));
    }

    // Validamos si existe un formulario en la vista.
    if ( $purchase_orderForm.size() > 0 ) {
        //Textarea auto growth
        autosize($('textarea.auto-growth'));

        // Datepicker
        $('.input-datepicker').datepicker({
            format: "dd MM yyyy",
            weekStart: 0,
            maxViewMode: 0,
            language: $.Language.lang,
            autoclose: true,
            todayHighlight: true,
            container: '#bs_datepicker_container'
        });

        $(".select2").select2({
            theme: 'bootstrap',
            language: $.Language.lang,
            placeholder: $.Language.message.choose_product,
            width: '100%',
            minimumInputLength: 2,
            ajax: {
                delay: 250, // wait 250 milliseconds before triggering the request
                url: `${$.LeonSoft.options.URL}/provider/get_by_name_json`,
                dataType: "json",
                cache: "true",
                type: "get",
                data: function (param) {
                    var query = {
                        term: param.term,
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
            }
        });

        if ($('#productsForm').size() > 0) {
            $('#productsForm').sheepIt({
                separator: '',
                allowRemoveLast: false,
                allowRemoveCurrent: true,
                allowRemoveAll: false,
                allowAdd: true,
                allowAddN: false,
                minFormsCount: 1,
                iniFormsCount: 1,
                data: $('#productsPre').data('products'),
                afterAdd: function(source, clone) {
                    var data = source.getOption('data'),
                        index = clone.getPosition(),
                        obj = (data[index - 1] !== void 0) ? data[index - 1] : { product_id: '-1', text: 'Choose An Option' },
                        newOption = new Option(obj.name, obj.product_id, false, false);
                    
                    // Number and Currency format.
                    $('.currency').maskMoney($.formatCurrency.regions[$.Language.region].currency);
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
                            return data.text;
                        }
                    }).on('change', function(e){
                        var $selected = $('option:selected',this),
                            $this = $(this),
                            $stockSpan = $this.closest('tr').find('#item-in-stock');
                        // Set a stock
                        $stockSpan.text($.LeonSoft.methods.numberFormat($selected.data('in_stock')));
                    }).append(newOption);
                    // Calculate total
                    if ( obj.product_id != "-1" )
                    {
                        $(clone).find('[id~=item-in-stock]').text($.LeonSoft.methods.numberFormat(obj.stock));
                        $.LeonSoft.methods.getTotalProduct(obj.quantity.replace(/[^\d.-]/g,''), obj.cost.replace(/[^\d.-]/g,''), $(clone).find('[id~=item-total]'));
                        $.LeonSoft.methods.getSubTotal('[id~=item-total]', $('#main-total'));
                    }
                    
                    // Quantity and Amoun event
                    $(clone).on('focusout', "[id$='_quantity'], [id$='_cost']", function(){
                        var $tr = $(this).closest('tr'),
                            $quantity = $tr.find("[id$='_quantity']"),
                            $cost = $tr.find("[id$='_cost']"),
                            $element = $tr.find('#item-total');

                        $.LeonSoft.methods.getTotalProduct($quantity.val().replace(/[^\d.-]/g,''), $cost.val().replace(/[^\d.-]/g,''), $element);
                        $.LeonSoft.methods.getSubTotal('[id~=item-total]', $('#main-total'));
                    });
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
                            $.LeonSoft.methods.getSubTotal('[id~=item-total]', $('#main-total'));
                        }
                    );
        
                    return false;
                }
            });
        }
    }

    if ( $('#purchase_order_receive-form').size() > 0 ) {
        $('.number').maskMoney($.formatCurrency.regions[$.Language.region].number);
    }
});