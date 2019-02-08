$(function () {
    var $userTable = $('#users-table'),
        $userForm = $('#user-form');

    // Validamos si existe una tabla en la vista
    if ( $userTable.size() > 0 )
    {
        $userTable.DataTable($.extend(true, {}, $.LeonSoft.options.DATATABLE_TEMPLATE, {
            ajax: {
                url: `${$.LeonSoft.options.URL}/user/datatable_json`
            },
            columns: [
                {
                    data: function(data) {
                        return `
                        <input type="checkbox" id="check-${data.id}" class="filled-in chk-col-orange" name="check" value="${data.id}" />
                        <label class="m-b-0" for="check-${data.id}"</label>
                        `;
                    },
                    orderable: false,
                    searchable: false
                },
                { data: 'id' },
                {
                    data: 'avatar_path',
                    render: function (value, type, obj, meta) {
                        return `<img src="${obj.avatar_path}" width="48" height="48" alt="User" style="border-radius: 50%;" />`;
                    },
                    orderable: false,
                    searchable: false,
                    width: '48px'
                },
                {
                    data: 'username',
                    render: function(value, type, obj, meta) {
                        var active = (obj.status == "active") ? "col-green" : "col-red";
                        return `
                            <a href="${$.LeonSoft.options.URL}/user/view/${obj.id}" target="_blank">
                                <span><i class="material-icons ${active}" aria-hidden="true">person</i> ${obj.username}</span>
                            </a>
                        `;
                    }
                },
                { 
                    data: function(data) {
                        return $.LeonSoft.helpers.shortName(data.first_name, data.last_name);
                    },
                    orderable: false,
                    searchable: false
                },
                { data: 'email' },
                {
                    data: function(data) {
                        return `
                            <div class="btn-group font-10">
                                <a href="${$.LeonSoft.options.URL}/user/edit/${data.id}" target="_blank" class="btn btn-info btn-xs waves-effect" data-toggle="tooltip" data-original-title="${$.Language.message.edit}">
                                    <i class="material-icons">edit</i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-danger btn-xs delete-btn waves-effect" data-id="${data.id}" data-controller="user" data-toggle="tooltip" data-original-title="${$.Language.message.eliminate}">
                                    <i class="material-icons">delete</i>
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
                    data: 'middle_name',
                    visible: false
                },
                {
                    data: 'last_name',
                    visible: false
                },
                {
                    data: 'last_name2',
                    visible: false
                }
            ]
        }));
    }

    // Validamos si existe un formulario en la vista.
    if ( $userForm.size() > 0 ) {
        var $country = $('select[name="country_id"]'),
            $state = $('select[name="state_id"]'),
            $city = $('select[name="city_id"]'),
            $document_number = $('input[name="document_number"]'),
            $document_type = $('select[name="document_type_id"]'),
            $first_name = $('input[name="first_name"]'),
            $last_name = $('input[name="last_name"]'),
            $middle_name = $('input[name="middle_name"]'),
            $last_name2 = $('input[name="last_name2"]'),
            $gender = $('select[name="gender_id"]'),
            $dob = $('input[name="dob"]'),
            $phone = $('input[name="phone"]'),
            $mobile = $('input[name="mobile"]');
            
        $('.datepicker').bootstrapMaterialDatePicker({
            lang: $.Language.lang,
            format: 'DD MMMM YYYY',
            clearButton: true,
            weekStart: 1,
            time: false,
            cancelText: $.Language.message.cancel,
            okText: $.Language.message.ok,
            clearText: $.Language.message.clear
        });

        //Mobile Phone Number
        $('.mobile-phone-number').inputmask('(999) 999-9999', { placeholder: '(___) ___-____' });
        //Document Number
        $('.identification-card').inputmask('999-9999999-9', { placeholder: '___-_______-_' });
        //Passport
        $('.passport').inputmask('999999999', { placeholder: '_________' });
        // NIF - RNC
        $('.NIF').inputmask('9-999999-9', { placeholder: '_-______-_'});

        $country.on('change', function (e) {
            e.preventDefault();

            var $this = $(this)
                obj = null,
                filter = {
                    'states.country_id': $this.val()
                };

            $.get(`${$.LeonSoft.options.URL}/state/get_json`, filter)
                .done(function(response){
                    obj = JSON.parse(response);

                    if ( obj.error )
                        $.LeonSoft.methods.sweetNotification($.Language.message.title.warning, obj.message, 'warning', 1000);
                    else
                        $.LeonSoft.methods.optionTemplate($state, obj.data);
                });
        });

        $state.on('change', function (e) {
            e.preventDefault();

            var $this = $(this)
                obj = null,
                filter = {
                    'cities.state_id': $this.val()
                };

            $.get(`${$.LeonSoft.options.URL}/city/get_json`, filter)
                .done(function(response){
                    obj = JSON.parse(response);

                    if ( obj.error )
                        $.LeonSoft.methods.sweetNotification($.Language.message.title.warning, obj.message, 'warning', 1000);
                    else
                        $.LeonSoft.methods.optionTemplate($city, obj.data);
                });
        });

        $document_number.on('focusout', function () {
            var $this = $(this),
                filter = {
                    number: $this.val(),
                    type: $document_type.val()
                };

            $.get(`${$.LeonSoft.options.URL}/person/get_by_document_number_json`, filter)
                .done(function (response) {
                    var obj = JSON.parse(response),
                        person = {};

                    if ( !obj.error )
                    {
                        if ( obj.data.length = 1)
                        {
                            person = obj.data[0];

                            $first_name.val(person.first_name);
                            $middle_name.val(person.middle_name);
                            $last_name.val(person.last_name);
                            $last_name2.val(person.last_name2);
                            $gender.val(person.gender_id).selectpicker('refresh');
                            $dob.val($.LeonSoft.helpers.niceDate(person.dob, 'DD MMMM YYYY'));
                            $phone.val(person.phone);
                            $mobile.val(person.mobile);
                        }
                    }
                });
        });
    }
});