$(function () {

    // Funciones y variables que tienen que ver con la funcionalidad de la caja registradora.
    var $inputButton = $('#input-button'),
        $outputButton = $('#output-button'),
        $userButton = $('#user-button'),
        $closeButton = $('#close-button'),
        $inputForm = $('#input-form'),
        $outputForm = $('#output-form'),
        $userForm = $('#check-user-form'),
        $closeForm = $('#close-form');

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

    $('#paymentModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        setTimeout(function(){ modal.find('#amount').focus(); }, 1000);
    })
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

    // --------------------------------- [END] Form Send Event [END] ---------------------------------

    // Fin de las funciones y variables que tienen que ver con la funcionalidad de la caja registradora.


    //Textarea auto growth.
    autosize($('textarea.auto-growth'));
    // Mascara para los input tipo moneda.
    $('.currency').maskMoney($.formatCurrency.regions[$.Language.region].currency);
});