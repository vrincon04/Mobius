$(function () {

    // Funciones y variables que tienen que ver con la funcionalidad de la caja registradora.
    var $inputButton = $('#input-button'),
        $outputButton = $('#output-button'),
        $userButton = $('#user-button'),
        $closeButton = $('#close-button'),
        $sendOrderButton = $('#send-order-button'),
        $inputForm = $('#input-form'),
        $outputForm = $('#output-form'),
        $userForm = $('#check-user-form'),
        $closeForm = $('#close-form'),
        $orderForm = $('#order-form');

    $('#output-form, #input-form, #check-user-form, close-form').validate({
        highlight: function (input) {
            $(input).closest('.form-line').addClass('focused error');
        },
        unhighlight: function (input) {
            $(input).closest('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).closest('.form-group,.input-group').append(error);
        }
    });

    // --------------------------------- Show Modal Event ---------------------------------
    $('#userModal').on('show.bs.modal', function (event) {
        setTimeout(function(){ $('#username').focus(); }, 1000);
        
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data('open'); // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('#user-button').data('target', recipient);
        
    });

    $('#inputModal, #outputModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        setTimeout(function(){ modal.find('#amount').focus(); }, 1000);
    });

    $('#closeCashDrawerModal').on('show.bs.modal', function (event) {
        var modal = $(this);

        $.get(`${$.LeonSoft.options.URL}/cash_drawer/get_open_json`)
            .done(function(response) {
                var obj = JSON.parse(response);

                if ( obj.error === true)
                {
                    $.LeonSoft.methods.sweetNotification(
                        $.Language.title.error,
                        obj.message, 
                        'danger'
                    );

                    return;
                }

                var theoretical = obj.data.details.reduce(function(total, item) {
                    if (item.payment_method_id == 1)
                        return Number.parseFloat(total) + Number.parseFloat(item.amount);
                    else
                        return Number.parseFloat(total) + 0;
                }, 0)

                modal.find('#theoretical').val($.LeonSoft.helpers.formmatterCurrency(theoretical));
                modal.find('#block_cash').focus();
            });
    });
    // --------------------------------- [END] Show Modal Event [END] ---------------------------------

    // --------------------------------- Form Send Event ---------------------------------
    $userButton.on('click', function() {
        var $this = $(this),
            $data = $this.data();
        if ($userForm.valid()) {
            $.post(`${$.LeonSoft.options.URL}/user/check_supervisor`, $userForm.serialize())
                .done(function(response){
                    var obj = JSON.parse(response);

                    if ( obj.error === true)
                    {
                        $.LeonSoft.methods.sweetNotification(
                            $.Language.title.error,
                            obj.message, 
                            'danger'
                        );

                        return;
                    }

                    $('.btn-close-modal').trigger('click');
                    $('#closed_by').val(obj.data.id);
                    $($data.target).modal('show');
                });
        }

        $userForm[0].reset();
    });

    $inputButton.on('click', function() {
        if ( $inputForm.valid() ) {
            $.post(`${$.LeonSoft.options.URL}/cash_drawer/register_income`, $inputForm.serialize())
                .done(function(respuesta) {
                    var obj = JSON.parse(respuesta);

                    if ( obj.error === true)
                    {
                        $.LeonSoft.methods.sweetNotification(
                            $.Language.title.error,
                            obj.message, 
                            'danger'
                        );

                        return;
                    }

                    $('.btn-close-modal').trigger('click');
                });
        }

        $inputForm[0].reset();
    });

    $outputButton.on('click', function() {
        if ( $outputForm.valid() ) {
            $.post(`${$.LeonSoft.options.URL}/cash_drawer/register_expense`, $outputForm.serialize())
                .done(function(respuesta) {
                    var obj = JSON.parse(respuesta);

                    if ( obj.error === true)
                    {
                        $.LeonSoft.methods.sweetNotification(
                            $.Language.title.error,
                            obj.message, 
                            'danger'
                        );

                        return;
                    }

                    $('.btn-close-modal').trigger('click');
                });
        }

        $outputForm[0].reset();
    });

    $closeButton.on('click', function() {
        if ( $closeForm.valid() ) {
            $.post(`${$.LeonSoft.options.URL}/cash_drawer/close`, $closeForm.serialize())
                .done(function(respuesta) {
                    var obj = JSON.parse(respuesta);

                    if ( obj.error === true)
                    {
                        $.LeonSoft.methods.sweetNotification(
                            $.Language.title.error,
                            obj.message, 
                            'danger'
                        );

                        return;
                    }

                    $('.btn-close-modal').trigger('click');
                });
        }

        $outputForm[0].reset();
    });

    $sendOrderButton.on('click', function() {
        $.post(`${$.LeonSoft.options.URL}/pos/send_order`, $orderForm.serialize())
            .done(function(respuesta) {
                var obj = JSON.parse(respuesta);

                if ( obj.error === true)
                {
                    $.LeonSoft.methods.sweetNotification(
                        $.Language.title.error,
                        obj.message, 
                        'danger'
                    );

                    return;
                }

                $('.btn-close-modal').trigger('click');
            });
    });

    // --------------------------------- [END] Form Send Event [END] ---------------------------------

    // Fin de las funciones y variables que tienen que ver con la funcionalidad de la caja registradora.

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
                        $totalSpan.text($.LeonSoft.helpers.formmatterCurrency(Number.parseFloat($selected.sale) * $quantityInput.val()));
                        $saletInput.val($.LeonSoft.methods.numberFormat($selected.sale));
                        $.LeonSoft.methods.getSubTotal('[id~=item-total]', $('#main-total'));
                        
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