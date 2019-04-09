$(function () {
    var $providerTable = $('#providers-table'),
        $providerForm = $('#provider-form');

    // Validamos si existe una tabla en la vista
    if ( $providerTable.size() > 0 )
    {
        $providerTable.DataTable($.extend(true, {}, $.LeonSoft.options.DATATABLE_TEMPLATE, {
            ajax: {
                url: `${$.LeonSoft.options.URL}/provider/datatable_json`
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
                    data: function(data) {
                        return $.LeonSoft.helpers.shortName(data.first_name, data.last_name);
                    },
                    orderable: false,
                    searchable: false
                },
                { data: 'email' },
                {
                    data: function(data) {
                        if (data.is_modifiable == 1) {
                            return `
                                <div class="btn-group font-10">
                                    <a href="${$.LeonSoft.options.URL}/provider/edit/${data.id}" target="_blank" class="btn btn-info btn-xs waves-effect" data-toggle="tooltip" data-original-title="${$.Language.message.edit}">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-xs delete-btn waves-effect" data-id="${data.id}" data-controller="provider" data-toggle="tooltip" data-original-title="${$.Language.message.eliminate}">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </div>
                            `;
                        } else {
                            return '';
                        }
                        
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
    if ( $providerForm.size() > 0 ) {
        var $country = $('select[name="country_id"]'),
            $state = $('select[name="state_id"]'),
            $city = $('select[name="city_id"]');
            
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
    }
});