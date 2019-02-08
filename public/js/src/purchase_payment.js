$(function () {
    var $purchase_paymentTable = $('#purchase_payments-table'),
        $purchase_paymentForm = $('#purchase_payment-form');

    // Validamos si existe una tabla en la vista
    if ( $purchase_paymentTable.size() > 0 )
    {
        $purchase_paymentTable.removeAttr('width').DataTable($.extend(true, {}, $.LeonSoft.options.DATATABLE_TEMPLATE, {
            ajax: {
                url: `${$.LeonSoft.options.URL}/purchase_payment/datatable_json`
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
                        return `PC${value.padStart(6, "0")}`
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
                    width: '25%',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'lang',
                    render: function(value, type, obj, meta) {
                        return $.Language.message[value]
                    },
                    width: '15%',
                    orderable: false,
                    searchable: false
                },
                { 
                    data: 'status',
                    render: function(value, type, obj, meta) {
                        return $.Language.message[value]
                    },
                    searchable: false,
                    width: '10%'
                },
                { 
                    data: 'amount',
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
                                <a href="${$.LeonSoft.options.URL}/purchase_payment/view/${data.id}" class="btn btn-success btn-xs waves-effect" data-toggle="tooltip" data-original-title="${$.Language.message.view}">
                                    <i class="material-icons">remove_red_eye</i>
                                </a>
                            </div>
                        `;
                    },
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
    if ( $purchase_paymentForm.size() > 0 ) {
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
        }).on("change", function() {
            var $this = $(this),
                $url = `${$.LeonSoft.options.URL}/purchase/get_by_provider_json/${$this.val()}`,
                $purchase_container = $('#purchases');

            // Limpiamos el contener.
            $purchase_container.empty();
            // Buscamos las ordenes del proveedor seleccionado.
            $.get($url, function(response) {
                var obj = JSON.parse(response)
                // Verificamos que no hay errores.
                if ( obj.error )
                {
                    $.LeonSoft.methods.sweetNotification($.Language.title.error, obj.message, 'error');
                    return;
                }

                $.each(obj.data, function (key, value) {
                    $purchase_container.append($.LeonSoft.templates.provederPurchases(value))    
                });
                // Number and Currency format.
                $('.currency').maskMoney($.formatCurrency.regions[$.Language.region].currency);
            });
        });

        $(document).on('click', '.check-pay', function() {
            var $this = $(this);
            $(`#amount-${$this.val()}`).prop('disabled', !$this.is(':checked'));
        });

        $(document).on('keyup', '.amount-pay', function() {
            var $this = $(this),
                $data = $this.data();
            
            if ( Number.parseFloat($this.val().replace(/[^\d.-]/g,'')) > Number.parseFloat($data.payable.replace(/[^\d.-]/g,'')) )
            {
                $.LeonSoft.methods.sweetNotification($.Language.title.warning, $.Language.message.totalPayable, 'warning');
                $this.val(0);
            }
            
            $.LeonSoft.methods.getSubTotalFromValue('.amount-pay', $('#total'))

            $('#amount').val($('#total').text());
        });
    }
});