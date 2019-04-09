$(function () {
    var $areaTable = $('#areas-table'),
        $areaForm = $('#area-form');

    // Validamos si existe una tabla en la vista
    if ( $areaTable.size() > 0 ) {
        $areaTable.DataTable($.extend(true, {}, $.LeonSoft.options.DATATABLE_TEMPLATE, {
            ajax: {
                url: `${$.LeonSoft.options.URL}/area/datatable_json`
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
                    data: 'department',
                    render: function(value, type, obj, meta) {
                        return `<a href="${$.LeonSoft.options.URL}/department/view/${obj.department_id}" target="_blank">${value}</a>`;
                    }
                },
                {
                    data: 'name',
                    render: function(value, type, obj, meta) {
                        return `<a href="${$.LeonSoft.options.URL}/area/view/${obj.id}" target="_blank">${value}</a>`;
                    }
                },
                { data: 'description' },
                {
                    data: 'is_active',
                    render: function (value, type, obj, meta) {
                        var active = (obj.is_active) ? "col-green" : "col-red";
                        return "<i class='material-icons " + active + " font-18' aria-hidden='true'>album</i>";
                    }
                },
                {
                    data: function(data) {
                        return `
                            <div class="btn-group font-10">
                                <a href="${$.LeonSoft.options.URL}/area/edit/${data.id}" target="_blank" class="btn btn-info btn-xs waves-effect" data-toggle="tooltip" data-original-title="${$.Language.message.edit}">
                                    <i class="material-icons">edit</i>
                                </a>
                            </div>
                        `;
                    },
                    orderable: false,
                    searchable: false
                }
            ]
        }));
    
    }

    // Validamos si existe un formulario en la vista.
    if ( $areaForm.size() > 0 ) {
        //Textarea auto growth
        autosize($('textarea.auto-growth'));
    }
});
