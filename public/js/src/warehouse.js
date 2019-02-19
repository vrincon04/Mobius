$(function () {
    var $warehouseTable = $('#warehouses-table'),
        $warehouseForm = $('#warehouse-form');

    // Validamos si existe una tabla en la vista
    if ( $warehouseTable.size() > 0 ) {
        $warehouseTable.DataTable($.extend(true, {}, $.LeonSoft.options.DATATABLE_TEMPLATE, {
            ajax: {
                url: `${$.LeonSoft.options.URL}/warehouse/datatable_json`
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
                    data: 'name',
                    render: function(value, type, obj, meta) {
                        return `<a href="${$.LeonSoft.options.URL}/warehouse/view/${obj.id}" target="_blank">${obj.name}</a>`;
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
                        if (data.is_modifiable == 1) {
                            return `
                                <div class="btn-group font-10">
                                    <a href="${$.LeonSoft.options.URL}/warehouse/edit/${data.id}" target="_blank" class="btn btn-info btn-xs waves-effect" data-toggle="tooltip" data-original-title="${$.Language.message.edit}">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-xs delete-btn waves-effect" data-id="${data.id}" data-controller="warehouse" data-toggle="tooltip" data-original-title="${$.Language.message.eliminate}">
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
                }
            ]
        }));
    
    }

    // Validamos si existe un formulario en la vista.
    if ( $warehouseForm.size() > 0 ) {
        //Textarea auto growth
        autosize($('textarea.auto-growth'));
    }
});
