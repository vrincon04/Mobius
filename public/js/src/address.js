if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

$(function() {
    let $country = $('select[name="country_id"]'),
        $state = $('select[name="state_id"]'),
        $city = $('select[name="city_id"]');

        $country.on('change', function (e) {
            e.preventDefault();

            $.LeonSoft.methods.busy('select[name="country_id"]');

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
                })
                .fail(function() {

                })
                .always(function () {
                    //Loading hide
                    $.LeonSoft.options.loading.waitMe('hide');
                });
        });

        $state.on('change', function (e) {
            e.preventDefault();

            $.LeonSoft.methods.busy('select[name="state_id"]');

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
                })
                .fail(function() {

                })
                .always(function () {
                    //Loading hide
                    $.LeonSoft.options.loading.waitMe('hide');
                });
        });
});