if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

$.accounting = {
    regions: {
        'es-DO': {
            currency: {
                symbol : "RD$",   // default currency symbol is 'RD$'
                format: { // controls output: %s = symbol, %v = value/number (can be object: see below)
                    pos : "%s%v",   // for positive values, eg. "RD$1.00" (required)
                    neg : "(%s%v)", // for negative values, eg. "(RD$1.00)" [optional]
                    zero: "%s -- "  // for zero values, eg. "RD$ --" [optional]
                },
                decimal : ".",  // decimal point separator
                thousand: ",",  // thousands separator
                precision : 2   // decimal places
            },
            number: {
                precision : 2,  // default precision on numbers is 2
                thousand: ",",
                decimal : ".",
                format: { // controls output: %s = symbol, %v = value/number (can be object: see below)
                    pos : "%v",   // for positive values, eg. "1.00" (required)
                    neg : "(%v)", // for negative values, eg. "(1.00)" [optional]
                    zero: "-- "  // for zero values, eg. "--" [optional]
                },
            }
        },
        'en-US': {
            currency: {
                symbol : "$",   // default currency symbol is '$'
                format: { // controls output: %s = symbol, %v = value/number (can be object: see below)
                    pos : "%s%v",   // for positive values, eg. "$1.00" (required)
                    neg : "(%s%v)", // for negative values, eg. "($1.00)" [optional]
                    zero: "%s -- "  // for zero values, eg. "$ --" [optional]
                },
                decimal : ".",  // decimal point separator
                thousand: ",",  // thousands separator
                precision : 2   // decimal places
            },
            number: {
                precision : 2,  // default precision on numbers is 2
                thousand: ",",
                decimal : "."
            }
        }
    }
};