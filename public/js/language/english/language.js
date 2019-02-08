if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

$.Language = {
    lang: 'en',
    datatable: {
        sEmptyTable:     "No data available in table",
        sInfo:           "Showing _START_ to _END_ of _TOTAL_ entries",
        sInfoEmpty:      "Showing 0 to 0 of 0 entries",
        sInfoFiltered:   "(filtered from _MAX_ total entries)",
        sInfoPostFix:    "",
        sInfoThousands:  ",",
        sLengthMenu:     "Show _MENU_ entries",
        sLoadingRecords: "<div class='preloader pl-size-xs'><div class='spinner-layer pl-light-blue'><div class='circle-clipper left'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>",
        sProcessing:     "<div class='preloader pl-size-xs'><div class='spinner-layer pl-light-blue'><div class='circle-clipper left'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>",
        sSearch:         "Search:",
        sZeroRecords:    "No matching records found",
        oPaginate: {
            sFirst:    "First",
            sLast:     "Last",
            sNext:     "Next",
            sPrevious: "Previous"
        },
        oAria: {
            sSortAscending:  ": Activate to sort column ascending",
            sSortDescending: ": Activate to sort column descending"
        }
    },
    title: {
        error: "Error",
        success: "Success",
        warning: "Warning",
        danger: "Danger",
        info: "Info"
    },
    message: {
        exitPage: "If you leave the page now, you will lose the changes. Do you want to get out in all the ways?",
        eliminate: "Eliminate",
        eliminateQuestion: "Are you sure you want to eliminate this item?",
        eliminateConfirm: "Yes, eliminate!",
        eliminateSuccess: "Eliminated correctly!",
        delete: "Delete",
        right: "Right!",
        edit: "Edit",
        cancel: 'Cancel',
        ok: 'Ok',
        clear: 'Clear',
        choose_option: 'Choose your option',
        choose_product: 'Choose your product',
        expected_at_greater_than_date: 'It must not be before the Purchase Order date',
        in_stock: 'En existencia',
        sale: 'Sale',
        cost: 'Cost',
        pending: 'Pending',
        draft: 'Draft',
        close: 'Close',
        partial: 'Partial',
        unpaid: 'Unpaid',
        paid: 'Paid'
    }
}