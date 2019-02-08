
if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

$.LeonSoft = {};

$.LeonSoft.options = {
    URL: window.location.protocol + "//" + window.location.hostname + ((window.location.port != '') ? ":" + window.location.port : ""),
    pathArray: window.location.pathname.split('/'),
    DATATABLE_TEMPLATE: {
        language: $.Language.datatable,
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            type: 'POST'
        },
        columnDefs: [
            {
                orderable: false,
                width: "1px",
                targets: 0
            }
        ],
        order: [ 
            [1, 'desc'] 
        ],
        dom: "<'row'<'col-sm-4 m-b-0'l><'col-sm-4 m-b-0 align-center-xs'B><'col-sm-4 m-b-0'f>><'row'<'col-sm-6 m-b-0'i><'col-sm-6 m-b-0'p>><'text-center'r>t<'row DTTTFooter'<'col-sm-6 m-b-0'i><'col-sm-6 m-b-0'p>>",
        buttons: [
            {
                extend: "copy",
                text: "<i class='material-icons' data-toggle='tooltip' data-original-title='Copiar'>content_copy</i>",
                className: "btn-sm",
                exportOptions: {
                    columns: '.copy,.all'
                }
            },
            {
                extend: "csv",
                text: "<i class='material-icons' data-toggle='tooltip' data-original-title='CSV'>attachment</i>",
                className: "btn-sm",
                exportOptions: {
                    columns: '.csv,.all'
                }
            },
            {
                extend: "excel",
                text: "<i class='material-icons' data-toggle='tooltip' data-original-title='Excel'>blur_linear</i>",
                className: "btn-sm",
                exportOptions: {
                    columns: '.excel,.all'
                }
            },
            {
                extend: "pdfHtml5",
                text: "<i class='material-icons' data-toggle='tooltip' data-original-title='PDF'>picture_as_pdf</i>",
                className: "btn-sm",
                exportOptions: {
                    columns: '.pdf,.all'
                }
            },
            {
                extend: "print",
                text: "<i class='material-icons' data-toggle='tooltip' data-original-title='Imprimir'>print</i>",
                className: "btn-sm",
                exportOptions: {
                    columns: '.print,.all'
                }
            }
        ]
    },
};

$.LeonSoft.methods = {
    notification: function (message, type) {
        var icon = '';
        switch (type) {
            case 'success':
                icon = '<i class="material-icons font-16">check_circle</i> ';
                break;
            case 'danger':
                icon = '<i class="material-icons font-16">cancel</i> ';
                break;
            case 'warning':
                icon = '<i class="material-icons font-16">warning</i> ';
                break;
            case 'info':
            default:
                icon = '<i class="material-icons font-16">info</i> ';
                type = 'info';
        }

        $.notify(icon + message, { "type": type });
    },
    sweetNotification: function (title, text, type, timer) {
        switch (type) {
            case 'success':
            case 'info':
            case 'warning':
            case 'error':
                break;
            case 'danger':
                type = 'error';
                break;
            default:
                type = 'info';
        }
    
        var obj = { title: title, text: text, type: type };
        if ( timer !== undefined ) { obj.timer = timer; }
    
        swal(obj);
    },
    preventExit: function (e) {
        var message = $.Language.message.exitPage,
            e = e || window.event;
        // For IE and Firefox
        if (e) {
            e.returnValue = message;
        }
    
        // For Safari
        return message;
    },
    /**
     * Agrega una fila a una tabla.
     * 
     * @param {string} element HTML element for the column of row.
     * @param {object} data The value of the column.
     * 
     * @returns {object} jQuery object
     */
    addRow: function (element, data) {
        // Creamos nuestra fila.
        var $tr = $('<tr></tr>');
        // Recoremos las columnas que va a tener la nueva fila.
        $.each(data, function (index, value) {
            console.info(index, value);
            //Agremos la nueva columna a nuesra fila.
            $tr.append("<" + element + ">" + value + "</" + element + ">");
        });
        // Retornamos nuestra fila con sus columnas.
        return $tr;
    },
    /**
     * Metodo que carga las opciones de un selector o combobox.
     * @protected
     * @param {!Object} $select - El componente que vamos a llenar.
     * @param {!Object} items - El listado de valores a insertar.
     * @param {(number|string)} defaultValue - Valor por defecto que se seleccionara.
     */
    optionTemplate: function ($select, items, defaultValue) {
        var html = `<option value="" disabled selected>${$.Language.message.choose_option}</option>`;

        if ( items.length > 0 ) {
            $.each(items, function (key, value) {
                html += `<option value="${value.id}">${value.name}</option>`;
            });

            $select.empty();
            $select.append(html);
            $select.val(defaultValue);
            $select.selectpicker('refresh');
        }
    },
    /**
     * Format a number with grouped thousands
     * @param {number} number - The number being formatted.
     * @param {number} decimals - Sets the number of decimal points.
     * @param {string} decPoint - Sets the separator for the decimal point.
     * @param {string} thousandsSep - Sets the thousands separator.
     */
    numberFormat: function (number, decimals, decPoint, thousandsSep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
            var n = !isFinite(+number) ? 0 : +number
            var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
            var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
            var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
            var s = ''

            var toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec)
                return '' + (Math.round(n * k) / k)
                  .toFixed(prec)
            }
            
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || ''
                s[1] += new Array(prec - s[1].length + 1).join('0')
            }

            return s.join(dec)
    },
    /**
     * Uppercase the first character of each word in a string
     * @param {string} text - The input string.
     */
    ucwords: function (text) {
        //  discuss at: http://locutus.io/php/ucwords/
        // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
        // improved by: Waldo Malqui Silva (http://waldo.malqui.info)
        // improved by: Robin
        // improved by: Kevin van Zonneveld (http://kvz.io)
        // bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
        // bugfixed by: Cetvertacov Alexandr (https://github.com/cetver)
        //    input by: James (http://www.james-bell.co.uk/)
        //   example 1: ucwords('kevin van  zonneveld')
        //   returns 1: 'Kevin Van  Zonneveld'
        //   example 2: ucwords('HELLO WORLD')
        //   returns 2: 'HELLO WORLD'
        //   example 3: ucwords('у мэри был маленький ягненок и она его очень любила')
        //   returns 3: 'У Мэри Был Маленький Ягненок И Она Его Очень Любила'
        //   example 4: ucwords('τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός')
        //   returns 4: 'Τάχιστη Αλώπηξ Βαφής Ψημένη Γη, Δρασκελίζει Υπέρ Νωθρού Κυνός'

        return (text + '')
            .replace(/^(.)|\s+(.)/g, function ($first) {
                return $first.toUpperCase()
            }) 
    },
    /**
     * Obtain the total of the amount of a product plus the mount 
     * of it and display it in the specified field.
     * @param {number} quantity - The amount of product to be calculated.
     * @param {number} amount - The amount of the product.
     * @param {string} display - the element will be display.
     */
    getTotalProduct: function (quantity, amount, display) {
        if (Number.isNaN(Number.parseFloat(quantity)))
        {
            throw new Error(`The value of the variable quantity: [${quantity}] is not numeric`);
        }

        if (Number.isNaN(Number.parseFloat(amount)))
        {
            throw new Error(`The value of the variable amount: [${amount}] is not numeric`);
        }

        var result = quantity * amount;

        display.text($.LeonSoft.helpers.formmatterCurrency(result));
    },
    getSubTotal: function (target, display) {
        var total = 0;
        
        $(target).each(function (key, value) {
            total = Number.parseFloat(total) + Number.parseFloat($(value).text().replace(/[^\d.-]/g,''));
        });

        display.text($.LeonSoft.helpers.formmatterCurrency(total));
    },
    getSubTotalFromValue: function (target, display) {
        var total = 0;
        
        $(target).each(function (key, value) {
            total = Number.parseFloat(total) + Number.parseFloat($(value).val().replace(/[^\d.-]/g,''));
        });

        display.text($.LeonSoft.helpers.formmatterCurrency(total));
    }
};

$.LeonSoft.helpers = {
    hexToRgb: function (hexCode) {
        var patt = /^#([\da-fA-F]{2})([\da-fA-F]{2})([\da-fA-F]{2})$/;
        var matches = patt.exec(hexCode);
        var rgb = "rgb(" + parseInt(matches[1], 16) + "," + parseInt(matches[2], 16) + "," + parseInt(matches[3], 16) + ")";
        
        return rgb;
    },
    hexToRgba: function (hexCode, opacity) {
        var patt = /^#([\da-fA-F]{2})([\da-fA-F]{2})([\da-fA-F]{2})$/;
        var matches = patt.exec(hexCode);
        var rgb = "rgba(" + parseInt(matches[1], 16) + "," + parseInt(matches[2], 16) + "," + parseInt(matches[3], 16) + "," + opacity + ")";
        
        return rgb;
    },
    formmatterCurrency: function (number) {
        return accounting.formatMoney(number, $.accounting.regions[$.Language.region].currency);
    },
    formmatterNumber: function (number) {
        return accounting.formatMoney(number, $.accounting.regions[$.Language.region].number);
    },
    shortName: function (first_name, last_name) {
        return $.LeonSoft.methods.ucwords(`${first_name} ${last_name}`);
    },
    niceDate: function (value) {
        if (!value) 
            return '';
        
        var date = moment(value);

        return $.LeonSoft.methods.ucwords(date.locale($.Language.lang).format('DD/MMM/YYYY').replace('.', ''));
    },
    niceDateTime:function (value) {
        if (!value) 
            return '';

        var date = moment(value);

        return $.LeonSoft.methods.ucwords(date.locale($.Language.lang).format('DD/MMM/YYYY hh:mm A').replace('.', ''));
    },
    niceDuration: function (value) {
        var fromm = moment.utc();
        var tom = moment.utc(value);
        var d = moment.duration(tom.diff(fromm, 'seconds'), 'seconds');
    
        if (d.asHours() > 5)
            return d.locale($.Language.lang).humanize().toUpperCase()
    
        if (d.asMinutes() >= 60) 
            return moment.utc(tom.diff(fromm)).format('H [H] m [M]')
    
        return moment.utc(tom.diff(fromm)).format('m [M] s [S]')
    }

};

$.LeonSoft.templates = {
    purchaseOrderItems: function (data) {
        var obj = data.obj;
        if (!data.id) {
            return data.text;
        }

        var stock = obj.stocks.reduce(function (total, item) {
            return parseFloat(total) + parseFloat(item.count)
          }, 0)

        var html = `<div class="panel panel-default panel-post m-b-0">
        <div class="panel-heading">
            <div class="media">
                <div class="media-left">
                    <a href="javascript:void(0);">
                        <img src="${$.LeonSoft.options.URL + obj.image_path}">
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">
                        <a href="javascript:void(0);">${obj.name}</a>
                    </h4>
                    ${$.Language.message.in_stock}: <span class="col-cyan font-bold">${$.LeonSoft.methods.numberFormat(stock, 2)}</span> - 
                    ${$.Language.message.sale}: <span class="col-teal font-bold">${$.LeonSoft.helpers.formmatterCurrency(obj.sale)}</span> -
                    ${$.Language.message.cost}: <span class="col-red font-bold">${$.LeonSoft.helpers.formmatterCurrency(obj.stocks[0].cost)}</span>

                </div>
            </div>
        </div>
    </div>`;
        
        return $(html);
    },
    progress: function (data, height) {
        var percentage = ( Number.parseFloat(data.starters) /  Number.parseFloat(data.quantity) ) * 100; 
        return `<div class="progress m-b-0" style="height: ${height};">
        <div class="progress-bar bg-grey" role="progressbar" aria-valuenow="${percentage}" aria-valuemin="0" aria-valuemax="100" style="width: ${percentage}%;"></div>
    </div>
    <span class="col-grey font-10">${$.LeonSoft.methods.numberFormat(data.starters, 0)} de ${$.LeonSoft.methods.numberFormat(data.quantity, 0)}</span>`;
    },
    provederPurchases: function (data) {
        var html = `
            <tr>
                <td>
                    <input type="checkbox" id="check-${data.id}" class="filled-in chk-col-orange check-pay" name="pruchases[${data.id}][id]" value="${data.id}" />
                    <label class="m-b-0" for="check-${data.id}"</label>
                </td>
                <td>C${data.id.padStart(6, "0")}</td>
                <td>${$.LeonSoft.helpers.niceDate(data.date)}</td>
                <td>${$.LeonSoft.helpers.niceDate(data.expired_at)}</td>
                <td class="text-right">${$.LeonSoft.helpers.formmatterCurrency(data.total)}</td>
                <td class="text-right">
                    <input type="text" name="pruchases[${data.id}][amount]" id="amount-${data.id}" class="form-control currency text-right amount-pay" value="0" placeholder="0" data-payable="${data.total}" disabled require />
                </td>
            </tr>
        `;

        return $(html)
    }

};

$(function () {
    /* Styles for validation */
    if ( typeof ($.fn.validate) !== 'undefined' ) {
        validateMaterial = function($form) {
            $form.validate({
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
        };

        validateMaterial($('form.form-validate'));
    }

    /* Avatar inputs code */
    $('.avatar a.avatar-edit').on('click', function() {
        $(this).siblings('input.avatar-input').click();
    });

    $('.avatar input.avatar-input').on('change', function() {
        var $this = $(this);

        if ( $this.val() != '' ) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $this.parent('.avatar').css('background-image', 'url(' + e.target.result + ')');
            };

            reader.readAsDataURL($this[0].files[0]);
        }
    });
    /* Avatar inputs code - END */

    /* Side bar highlight */
    var $item = $('.sidebar li[data-controller="' + window.location.pathname.split( '/' )[1] + '"]');
    $item.addClass('active').parents('li').find('a.menu-toggle').click();
    /* Side bar highlight - END */

    /* Delete button action code */
    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();
        var $btn = $(this),
            $data = $btn.data();
        swal({
            title: $.Language.message.eliminate,
            text: $.Language.message.eliminateQuestion,
            type: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: $.Language.message.eliminateConfirm,
            closeOnConfirm: false
        },
        function(){
            $.post(`${$.LeonSoft.options.URL}/${$data.controller}/delete/${$data.id}`, function (json) {
                var json_obj = JSON.parse(json),
                    table = $btn.closest('table');
    
                if ( json_obj.error == true ) {
                    swal.showInputError(json_obj.message);
                    return false;
                } else {
                    $.LeonSoft.methods.sweetNotification($.Language.message.right, $.Language.message.eliminateSuccess, "success", 1000);
                    $(table).DataTable().ajax.reload();
                }
            });
        });
    });
    /* Delete button action code - END */

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        container: 'body'
    });

    $('body').popover({ 
        selector: '[data-rel=popover]',
        html: true, 
        container: 'body', 
        trigger: 'click hover', 
        delay: { show: 50, hide: 100 }
    });

    /* Ajax popover code */
    $(document).on('mouseover', 'a[data-rel="ajax_popover"]', function () {
        var $element = $(this),
            $data = $element.data();
        // set a loader image, so the user knows we're doing something
        /*$data.content = '<div class="preloader pl-size-sm">\n' +
            '                                    <div class="spinner-layer pl-cyan">\n' +
            '                                        <div class="circle-clipper left">\n' +
            '                                            <div class="circle"></div>\n' +
            '                                        </div>\n' +
            '                                        <div class="circle-clipper right">\n' +
            '                                            <div class="circle"></div>\n' +
            '                                        </div>\n' +
            '                                    </div>\n' +
            '                                </div>';
        $element.popover({
            html: true,
            trigger: 'hover'
        }).popover('show');*/
        // retrieve the real content for this popover, from location set in data-href
        $.get($data.url, function (response) {
            // set the ajax-content as content for the popover
            $data.content = response;
            // replace the popover
            //$element.popover('destroy');
            $element.popover({
                html: true,
                container: 'body',
                trigger: 'hover'
            });
            // check that we're still hovering over the preview, and if so show the popover
            if ($element.is(':hover')) {
                $element.popover('show');
            }
            $element.attr('data-rel', 'popover');
        });
    
    });
    /* Ajax popover code - END */

    /* Check all checkbox from table code */
    $('#check-all').change(function () {
        $(this).closest('table')
            .find('tbody tr td input.chk-col-orange')
            .not(':disabled')
            .prop('checked', $(this).is(':checked'));
    });
    /* Check all checkbox from table code - END */
    
    $(document).on('change', 'table.table tbody tr td input.chk-col-orange', function () {
        var checked = $(this).closest('tbody').find('input.chk-col-orange').not(':checked').length > 0;
        $('#check-all').prop('checked', !checked);
    });

    $(document).on('focusout', '#document_number', function (e) {
        e.preventDefault();

        var $this = $(this),
            filter = {'persons|document_number' : $this.val()}
            obj = {};

        $.get(`${$.LeonSoft.options.URL}/person/info_json`, filter)
            .done(function(response){
                obj = JSON.parse(response);

                if (obj.error === false) {
                    for (x in obj.data[0]) {
                        $(`#${x}`).val(obj.data[0][x]);
                    }

                    $('select').selectpicker('refresh');
                }
            });
    })
});