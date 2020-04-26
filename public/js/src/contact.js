
if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

$(function() {
    let $document_number = $('input[name="document_number"]'),
        $document_type = $('select[name="document_type_id"]'),
        $first_name = $('input[name="first_name"]'),
        $last_name = $('input[name="last_name"]'),
        $middle_name = $('input[name="middle_name"]'),
        $last_name2 = $('input[name="last_name2"]'),
        $gender = $('select[name="gender_id"]'),
        $dob = $('input[name="dob"]'),
        $phone = $('input[name="phone"]'),
        $mobile = $('input[name="mobile"]'),
        $trade_name = $('input[name="trade_name"]'),
        $business_name = $('input[name="business_name"]'),
        $person_info = $('.person-info'),
        $business_info = $('.business-info'),
        contactHelper = [
            {
                url: `${$.LeonSoft.options.URL}/person/get_by_document_number_json`,
                filter: {
                    number: null,
                    type: null,
                },
                setInfoToField: function(obj) {
                    data = obj.data[0];
                    $first_name.val(data.first_name);
                    $middle_name.val(data.middle_name);
                    $last_name.val(data.last_name);
                    $last_name2.val(data.last_name2);
                    $gender.val(data.gender_id).selectpicker('refresh');
                    $dob.val($.LeonSoft.helpers.niceDate(data.dob, 'DD MMMM YYYY'));
                    $phone.val(data.phone);
                    $mobile.val(data.mobile);
                }
            },
            {
                url: `http://adamix.net/gastosrd/api.php`,
                filter: {
                    act: 'GetContribuyentes',
                    rnc: null
                },
                setInfoToField: function(data) {
                    $business_name.val(data.RGE_NOMBRE);
                    $trade_name.val(data.NOMBRE_COMERCIAL);
                }
            }
        ];

        // Hiden the contact info.
        $person_info.hide();
        $business_info.hide();

        $document_number.on('focusout', function (e) {
            e.preventDefault();
            let $this = $(this),
                index = ($document_type.val() == '4') ? 1 : 0;
                
            
            if ( $.trim($this.val()) == '' )
                return;
            
            if (index == 0 ) {
                contactHelper[index].filter.number = $this.val();
                contactHelper[index].filter.type = $document_type.val();
            } else {
                contactHelper[index].filter.rnc = $this.val().replace(/-/gi, "");
            }

            $.LeonSoft.methods.busy('input[name="document_number"]');
    
            $.getJSON(contactHelper[index].url, contactHelper[index].filter)
                .done(function (response) {
                    contactHelper[index].setInfoToField(response);
                })
                .fail(function() {

                })
                .always(function () {
                    //Loading hide
                    $.LeonSoft.options.loading.waitMe('hide');
                });
        });
    
        $document_type.on('change', function (e) {
            e.preventDefault();
            $person_info.hide();
            $business_info.hide();
    
            var $this = $(this);
    
            switch($this.val()) {
                case '1':
                    $person_info.show();
                    $document_number.inputmask("999-9999999-9");
                    break;
                case '2':
                    $person_info.show();
                    $document_number.inputmask('99999999999');
                    break;
                case '3':
                    $person_info.show();
                    $document_number.inputmask('99999999999');
                    break;
                case '4':
                    $business_info.show();
                    $document_number.inputmask('9-99-99999-9');
                    break;
                default:
                    break;
            }
        });

        $document_type.trigger('change');
})