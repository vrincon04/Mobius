$(function () {
    var $orderForm = $('#order-form');

    // Validamos si existe un formulario en la vista.
    if ( $orderForm.size() > 0 ) {
        //Textarea auto growth
        autosize($('textarea.auto-growth'));

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
                        obj = (data[index - 1] !== void 0) ? data[index - 1] : { product_id: '-1', text: $.Language.message.choose_product },
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
                                    page: param.page,
                                    is_stock: 1,
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
                        templateResult: $.LeonSoft.templates.orderSaleItems,
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
                            $(data.element).attr('data-sale', obj.sale);
                            return data.text;
                        }
                    }).on('change', function(e){
                        var $selected = $('option:selected',this).data(),
                            $this = $(this),
                            $tr = $this.closest('tr'),
                            $priceSpan = $tr.find('#item-price'),
                            $totalSpan = $tr.find('#item-total'),
                            $saletInput = $tr.find('[id$="_sale"]'),
                            $quantityInput = $tr.find('[id$="_quantity"]');
                        // Set a price
                        $priceSpan.text($.LeonSoft.helpers.formmatterCurrency($selected.sale));
                        $totalSpan.text($.LeonSoft.helpers.formmatterCurrency($selected.sale));
                        $saletInput.val($.LeonSoft.methods.numberFormat($selected.sale));
                        $quantityInput.val($.LeonSoft.methods.numberFormat(1));
                    }).append(newOption).focus();
                    // Calculate total
                    if ( obj.product_id != "-1" )
                    {
                        $(clone).find('[id~=item-in-stock]').text($.LeonSoft.methods.numberFormat(obj.stock));
                        $.LeonSoft.methods.getTotalProduct(obj.quantity.replace(/[^\d.-]/g,''), obj.cost.replace(/[^\d.-]/g,''), $(clone).find('[id~=item-total]'));
                        $.LeonSoft.methods.getSubTotal('[id~=item-total]', $('#main-total'));
                    }
                    
                    // Quantity event
                    $(clone).on('keyup', "[id$='_quantity']", function(){
                        var $tr = $(this).closest('tr'),
                            $quantity = $tr.find("[id$='_quantity']"),
                            $opticion = $tr.find("select option:checked").data(),
                            $element = $tr.find('#item-total');

                        $.LeonSoft.methods.getTotalProduct($quantity.val().replace(/[^\d.-]/g,''), $opticion.sale, $element);
                        $.LeonSoft.methods.getSubTotal('[id~=item-total]', $('#main-total'));
                    });
                },
                beforeRemoveCurrent: function(source, form) {
                    swal(
                        {
                            title: "Eliminar Producto",
                            text: "¿Está seguro que desea eliminar este producto?",
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