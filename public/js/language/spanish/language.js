if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

$.Language = {
    lang: 'es',
    region: 'es-DO',
    datatable: {
        sProcessing:     "<div class='preloader pl-size-xs'><div class='spinner-layer pl-light-blue'><div class='circle-clipper left'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>",
        sLengthMenu:     "Mostrar _MENU_ registros",
        sZeroRecords:    "No se encontraron resultados",
        sEmptyTable:     "Ningún dato disponible en esta tabla",
        sInfo:           "Mostrando _START_ al _END_ de _TOTAL_",
        sInfoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
        sInfoFiltered:   "(filtrado de un total de _MAX_ registros)",
        sInfoPostFix:    "",
        sSearch:         "Buscar:",
        sUrl:            "",
        sInfoThousands:  ",",
        sLoadingRecords: "<div class='preloader pl-size-xs'><div class='spinner-layer pl-light-blue'><div class='circle-clipper left'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>",
        oPaginate: {
            sFirst:    "Primero",
            sLast:     "Ultimo",
            sNext:     "Siguiente",
            sPrevious: "Anterior"
        },
        oAria: {
            sSortAscending:  ": Activar para ordenar la columna de manera ascendente",
            sSortDescending: ": Activar para ordenar la columna de manera descendente"
        }
    },
    title: {
        error: "Error",
        success: "Éxito",
        warning: "Advertencia",
        danger: "Peligro",
        info: "Información"
    },
    message: {
        exitPage: "Si abandona ahora la página perderá los cambios. ¿Desea salir de todas formas?",
        eliminate: "Eliminar",
        eliminateQuestion: "¿Está seguro que desea eliminar este elemento?",
        eliminateConfirm: "¡Si, eliminar!",
        eliminateSuccess: "Eliminado correctamente!",
        delete: "Borrar",
        right: "¡Bien!",
        edit: "Editar",
        cancel: 'Cancelar',
        ok: 'De Acuerdo',
        clear: 'Despejar',
        choose_option: 'Elija una opción'
    }
}