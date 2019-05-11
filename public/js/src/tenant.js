$(function () {
    // variables
    var $tax_switch = $('.tax_switch');

    $.LeonSoft.methods.hideShowElement($tax_switch, $('#is_tax').is(':checked'));

    $('.datepicker').bootstrapMaterialDatePicker({
        lang: $.Language.lang,
        format: 'DD/MM/YYYY',
        clearButton: true,
        weekStart: 1,
        time: false,
        cancelText: $.Language.message.cancel,
        okText: $.Language.message.ok,
        clearText: $.Language.message.clear
    });

    //Mobile Phone Number
    $('.mobile-phone-number').inputmask('(999) 999-9999', { placeholder: '(___) ___-____' });

    // Datepicker
    $('.input-datepicker').datepicker({
        format: "dd MM yyyy",
        weekStart: 0,
        maxViewMode: 0,
        language: $.Language.lang,
        autoclose: true,
        todayHighlight: true,
    });

    $('#is_tax').on('click', function (e) {
        var $this = $(this);

        $.LeonSoft.methods.hideShowElement($tax_switch, $this.is(':checked'));
    });
    
});