$(function () {
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
});