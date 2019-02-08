if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

$.formatCurrency = {
    regions: {
        'es-DO': {
            currency: {
                decimal: '.',
                thousands: ',',
                prefix: 'RD$ ',
                suffix: '',
                precision: 2,
            },
            number: {
                decimal: '.',
                thousands: ',',
                prefix: '',
                suffix: '',
                precision: 2,
            }
        },
        'en-US': {
            currency: {
                decimal: '.',
                thousands: ',',
                prefix: '$',
                suffix: '',
                precision: 2,
            },
            number: {
                decimal: '.',
                thousands: ',',
                prefix: '',
                suffix: '',
                precision: 2,
            }
        }
    }
};