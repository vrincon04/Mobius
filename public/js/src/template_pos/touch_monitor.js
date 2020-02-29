$(function () {
    let $productCard = $('#product-card'),
        $productTable = $('#product-table'),
        $printOrderButton = $('#print-order-button'),
        $saveOrderButton = $('#save-order-button'),
        $payOrderButton = $('#payment-button'),
        Orderitems = [],
        isPrint = false,
        isInvoice = false;

    let getProduct = function () {
        $.LeonSoft.methods.busy('#product-card');

        $.get(`${$.LeonSoft.options.URL}/product/get_json`, {is_salable: 1})
        .done(function (response) {
            var object = JSON.parse(response);
            if ( !object.error ) {
                object.data.forEach(element => {
                    $productCard.append($.LeonSoft.templates.productCard(element));
                });
            }
            
        })
        .fail()
        .always(function () {
            //Loading hide
            $.LeonSoft.options.loading.waitMe('hide');
        });
    }

    let generateTotal = function () {
        if ( $('.item-subtotal').size() > 0 ) {
            $.LeonSoft.methods.getSubTotal('.item-subtotal', $('#main-subtotal'));
            if ( $('#main-tax').size() > 0 )
                $.LeonSoft.methods.getTotalProduct(0.18, $.LeonSoft.methods.parseElementToFloat($('#main-subtotal')), $('#main-tax'));
            $.LeonSoft.methods.displaySum($('#main-subtotal'), $('#main-tax'), $('#main-total'));
            $.LeonSoft.methods.displaySum($('#main-subtotal'), $('#main-tax'), $('#total_payable'));
            $('#amount').val($.LeonSoft.methods.parseElementToFloat($('#main-subtotal')));
        } else {
            $.LeonSoft.methods.getTotalProduct(0, 0, $('#main-subtotal'));
            if ( $('#main-tax').size() > 0 )
                $.LeonSoft.methods.getTotalProduct(0, 0, $('#main-tax'));
            $.LeonSoft.methods.getTotalProduct(0, 0, $('#main-total'));
            $.LeonSoft.methods.getTotalProduct(0, 0, $('#total_payable'));
            $('#amount').val(0);
        }
    }

    let addItem = function (data) {
        let row = $.LeonSoft.methods.addRow('td', [
            { attribute: `class="text-left" style="width: 37%;"`,  value: data.name },
            { attribute: `class="text-right item-price" style="width: 22%;"`, value: $.LeonSoft.helpers.formmatterCurrency(data.price) },
            { attribute: `class="text-right item-quantity" style="width: 11%;"`, value: $.LeonSoft.methods.numberFormat(1) },
            { attribute: `class="text-right item-subtotal" style="width: 22%;"`, value: $.LeonSoft.helpers.formmatterCurrency(data.price) },
            { attribute: `class="text-left item-delete pointer-cursor" data-id="${data.id}"`, value: `<i class="material-icons col-red font-12" id="item-${data.id}">delete</i>` }
        ]);

        $productTable.append(row);

        Orderitems[data.id] = {
            name: data.name,
            productId: data.id,
            price: data.price,
            quantity: 1
        };

        generateTotal();
    }

    let updateItem = function (row, data) {
        let price = $.LeonSoft.methods.parseElementToFloat(row.find('.item-price')),
            quantity = $.LeonSoft.methods.parseElementToFloat(row.find('.item-quantity'));
        
        $.LeonSoft.methods.getTotalProduct(++quantity, price, row.find('.item-subtotal'));
        row.find('.item-quantity').text(quantity);

        Orderitems[data.id].quantity = quantity;

        generateTotal();        
    }

    let deleteItem = function () {
        let $this = $(this),
            $data = $this.data();

        $this.closest('tr').remove();

        Orderitems.splice($data.id, 1);

        generateTotal();
        if ($('.item-subtotal').size() == 0)
            $('#footer-button').hide();
    }

    let saveOrder = function () {
        $.LeonSoft.methods.busy('#info-table');

        let items = Orderitems.filter(element => {
            if(element != null) return element;
        });

        let order = {
            id: $('#id').val(),
            customer_id: $('#customer_id').val(),
            status: (isInvoice) ? 'invoiced' : 'pending',
            subtotal: $.LeonSoft.methods.parseElementToFloat($('#main-subtotal')),
            tax: ($('#main-tax').size() > 0) ? $.LeonSoft.methods.parseElementToFloat($('#main-tax')) : 0,
            total: $.LeonSoft.methods.parseElementToFloat($('#main-total')),
            products: items
        };

        $.ajax({
            url: `${$.LeonSoft.options.URL}/pos/hold_order`,
            type: `POST`,
            contentType: `application/json`,
            data: JSON.stringify(order)
        }).fail(function () {
            $.LeonSoft.options.loading.waitMe('hide');
        }).done(function(respuesta) {
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

            $('#id').val(obj.data.id);

            if (isPrint)
                printOrder(obj.data.id);
            else if (isInvoice)
                InvoiceOrder(obj.data.id);
            else
                $.LeonSoft.options.loading.waitMe('hide');
        });

    }

    let printOrder = function ($id) {
        if ( $id != '0' ) {
            $.get(`${$.LeonSoft.options.URL}/pos/print_order/${$id}`)
                .done(function(respuesta) {
                    let obj = JSON.parse(respuesta);
        
                    if ( obj.error === true)
                    {
                        $.LeonSoft.methods.sweetNotification(
                            $.Language.title.error,
                            obj.message, 
                            'danger'
                        );
        
                        return;
                    }
                }).fail(function () {
                    $.LeonSoft.methods.sweetNotification(
                        $.Language.title.error,
                        $.Language.message.printError, 
                        'danger'
                    );
                }).always(function () {
                    //Loading hide
                    $.LeonSoft.options.loading.waitMe('hide');
                    isPrint = false;
                });
        }
    }

    let InvoiceOrder = function ($id) {
        if ( $id != '0' ) {
            let form = $('#payment-form')[0];
            let formData = new FormData(form);
            formData.append('order_id', $id);
            formData.append('customer_id', $('#customer_id').val());

            $.ajax({
                url: `${$.LeonSoft.options.URL}/pos/make_payment`,
                type: "POST",
                data: formData,
                processData: false,  // tell jQuery not to process the data
                contentType: false   // tell jQuery not to set contentType
              }).done(function(respuesta) {
                    let obj = JSON.parse(respuesta);
        
                    if ( obj.error === true)
                    {
                        $.LeonSoft.methods.sweetNotification(
                            $.Language.title.error,
                            obj.message, 
                            'danger'
                        );
        
                        return;
                    }
                }).fail(function () {
                    $.LeonSoft.methods.sweetNotification(
                        $.Language.title.error,
                        $.Language.message.printError, 
                        'danger'
                    );
                }).always(function () {
                    //Loading hide.
                    $.LeonSoft.options.loading.waitMe('hide');
                    isInvoice = false;
                });
        }
    }


    // Ajax Select.
    $(".select2_product").select2({
        theme: 'bootstrap',
        language: $.Language.lang,
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
            let obj = data.obj;
            let stock = 0;
            let min = -1;

            if (obj.stocks.length > 0)
            {
                stock = obj.stocks.reduce(function (total, item) {
                    return parseFloat(total) + parseFloat(item.count)
                }, 0);
                min = obj.stocks[0].min;
            }

            // Add custom attributes to the <option> tag for the selected option
            $(data.element).attr('data-stock', stock);
            $(data.element).attr('data-min', min);
            $(data.element).attr('data-id', obj.id);
            $(data.element).attr('data-price', obj.sale);
            $(data.element).attr('data-name', obj.name);

            return data.text;
        }
    }).on('change', function(e){
        let $selected = $('option:selected',this).data(),
            row = $productTable.find(`#item-${$selected.id}`).closest('tr');

            if (row.size() == 0)
                addItem($selected);
            else
                updateItem(row, $selected);        
    });

    // Product Event.
    $(document).on('click', '.product-event', function (e) {
        let $this = $(this),
            $data = $this.data(),
            row = $productTable.find(`#item-${$data.id}`).closest('tr');
        
        if (row.size() == 0)
            addItem($data);
        else
            updateItem(row, $data);
        
        $('#footer-button').show();
    });

    // Delete Row Event.
    $(document).on('click', '.item-delete', deleteItem);

    // Save Order Event.
    $saveOrderButton.on('click', saveOrder);

    // Print Order Event.
    $printOrderButton.on('click', function() {
        isPrint = true;
        saveOrder();
    });
    // Payment Order Event.
    $payOrderButton.on('click', function() {
        isInvoice = true;
        saveOrder();
    });

    getProduct();

    $('#footer-button').hide();
    
});