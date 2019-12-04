let paymentform = {

    api_url: null,
    web_url: null,
    language: "de",
    stripeClient: null,
    pricingTable: null,
    selectedResponsetime: null,
    submission_amount: null,
    transaction_id: null,
    partner_id: null,
    zahlweise: "creditcard",
    creditcardCallbackSuccess: null,

    setUrls: function (api_url, web_url) {
        this.api_url = api_url;
        this.web_url = web_url;
    },

    setLanguage: function (language) {
        this.language = language;
    },

    setStripeClient: function (stripe) {
        this.stripeClient = stripe;
    },

    setCreditcardCallbackSuccess: function (callback) {
        this.creditcardCallbackSuccess = callback;
    },

    show: function (transaction_id, partner_id) {
        this.transaction_id = transaction_id;
        this.partner_id = partner_id;

        // get the pricingtable and then show stripe card payment
        var that = this;
        let url = this.api_url + "/api/pricingtable";
        $.ajax({
            url: url,
            async: false,
            data: { partner_id:  this.partner_id },
            success: function (result) {
                that.pricingTable = result;

                // sort all prices by responsetime
                that.pricingTable.sort(function(a, b){
                    var a1= a.responsetime, b1= b.responsetime;
                    if(a1 === b1) return 0;
                    return a1<b1? 1: -1;
                });

                // which one is the preselected price
                that.submission_amount = $.grep(that.pricingTable, function(plan) { return plan.selected === true; })[0].price;
                that.selectedResponsetime=$.grep(that.pricingTable, function(plan) { return plan.selected === true; })[0].responsetime;

                that.createPricingSelector();
                that.registerPaymentSelectionChange();
                that.createStripeClient();
                that.updateSubmitButton();
            }
        });

    },

    createPricingSelector: function() {

        // clear all pricing options (needed if user searches multiple time for his submission_id)
        $('#submission_responsetime_wrapper').html('');

        // if there is only 1 priceoption available we will create a p and a hidden textinput instead
        if (this.pricingTable.length === 1) {
            let hiddenInput = $('<input type="hidden" class="form-control" id="submission_responsetime" name="responsetime">').val(this.pricingTable[0].responsetime).appendTo($('#submission_responsetime_wrapper'));
            let pricingText = (this.language === "de") ? 'Maximal ' + this.pricingTable[0].responsetime + 'h (Durchschnittlich weniger als 60 Minuten)'
                                                       : 'A few hours (maximum ' + this.pricingTable[0].responsetime + 'h)';
            let responsetimeDisplay = $('<p class="form-control-static" id="submission_responsetime_display">').text(pricingText).appendTo($('#submission_responsetime_wrapper'));
        }
        else {
            var that = this;
            this.pricingTable.forEach(function (plan) {
                let option = $('<input type="radio" name="responsetime" id="responsetime_' + plan.responsetime + '" value="' + plan.responsetime + '"/>');
                if (plan.selected) option.prop('checked', true);
                option.on("change", function (event) {
                    that.selectedResponsetime = parseInt(this.value);
                    that.submission_amount = $.grep(that.pricingTable, function(plan) { return plan.responsetime === that.selectedResponsetime; })[0].price;
                    that.updateSubmitButton();
                });

                let label = $('<label for="responsetime_' + plan.responsetime + '" class="radio" />');
                let pricingText = (that.language === "de") ? 'Maximal ' + plan.responsetime + ' Stunden (' + plan.price.toLocaleString() + ' €)'
                                                           : 'Maximum ' + plan.responsetime + ' hours (' + plan.price.toLocaleString() + ' €)';
                label.text(pricingText)
                    .prepend(option)
                    .appendTo($('#submission_responsetime_wrapper'));
            });
        }
    },

    updateSubmitButton: function () {
        let zahlweiseText;

        if (this.zahlweise === "creditcard") zahlweiseText = (this.language === "de") ? this.submission_amount.toFixed(2).toLocaleString() + ' € per Kreditkarte bezahlen'
                                                                                      : 'Pay ' + this.submission_amount.toFixed(2).toLocaleString() + ' € by credit card';
        if (this.zahlweise === "sofort")     zahlweiseText = (this.language === "de") ? this.submission_amount.toFixed(2).toLocaleString() + ' € mit Sofortüberweisung bezahlen'
                                                                                      : 'Pay ' + this.submission_amount.toFixed(2).toLocaleString() + ' € with Sofortüberweisung';
        $('#payment_submit_text').text(zahlweiseText);

        if (this.zahlweise === "sofort") {
            $('#payment_form').attr('target', '_top'); // unfortunately target=_blank does not work from within an iframe
        }
        if (this.zahlweise === "creditcard") {
            $('#payment_form').removeAttr('target');
        }
    },

    registerPaymentSelectionChange: function () {
        var that = this;
        $('input:radio[name=zahlweise]').change(function (event) {
            that.zahlweise = $(this).val();
            $('.stripe-payment-method').hide();
            $('#stripe-payment-' + $(this).val()).show();
            that.updateSubmitButton();
        });
    },

    processCreditcard: function(stripe) {
        let options = { name: "-" };
        var that = this;

        // Generate a stripe checkout session id on the server
        let url = that.api_url + "/api/stripe/createCheckoutSession";
        $.post(url, {
            transaction_id: that.transaction_id,
            responsetime: that.selectedResponsetime})
            .done(function(msg) {
                stripe.redirectToCheckout({
                    // Make the id field from the Checkout Session creation API response
                    // available to this file, so you can provide it as parameter here
                    // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
                    sessionId: msg.session_id
                }).then(function (result) {
                    // If `redirectToCheckout` fails due to a browser or network
                    // error, display the localized error message to your customer
                    // using `result.error.message`.
                });

            })
            .fail(function(msg) {
                let errors = msg.responseJSON.errors,
                    displayError = document.getElementById('card-errors');
                if (errors) {
                    if (errors.creditcard) {
                        displayError.textContent = errors.creditcard[0];
                    }
                    else {
                        displayError.textContent = (this.language === "de") ? "Ein Fehler ist aufgetreten."
                            : "An error occurred.";
                    }
                }
            })
            .always(function() {
                $("#payment_submit").prop("disabled",false);
                $("#payment_spinner").hide();
            });
    },

    processSofort: function(stripe) {

        var that = this;
        stripe.createSource({
            type: 'sofort',
            amount: Math.round(that.submission_amount * 100),
            currency: 'eur',
            sofort: {
                country: 'DE',
                preferred_language: 'de',
            },
            redirect: {
                return_url: that.web_url + '/sofort' +
                    '?transaction_id=' + that.transaction_id +'' +
                    '&version=web' +
                    '&partner_id=' + that.partner_id +
                    '&responsetime=' +  that.selectedResponsetime
            },
        }).then(function (result) {
            if (result.error) {
                $("#payment_submit").prop("disabled",false);
                $("#payment_spinner").hide();
                let errormsg;
                if      (result.error.type === "payment_method_not_available") errormsg = "Sofortüberweisung ist leider aktuell nicht verfügbar.";
                else if (result.error.type === "processing_error")             errormsg = "Leider ist ein Fehler aufgetreten. Bitte versuchen Sie Kreditkartenzahlung.";
                else                                                           errormsg = "Leider ist ein Fehler aufgetreten. Bitte versuchen Sie Kreditkartenzahlung.";
                $('#sofort_error').show().text(errormsg);
            }
            if (result.source && result.source.status === "pending") {
                let source = result.source;
                top.location.href = source.redirect.url;
            }
        });
    },

    createStripeClient: function() {
    // Create a Stripe client, if its not yet existing (if the user changes the price, the stripeClient already exists)
    let stripekey = $('#stripe-payment-creditcard').data('stripekey'),
        stripeClient = this.stripeClient(stripekey);

    // Handle form submission.
    var that = this;
    $('#payment_form').submit(function (event) {
        event.preventDefault();

        // disable the submit button to prevent doubleclicks
        $("#payment_submit").prop("disabled",true);
        $("#payment_spinner").show();

        if (that.zahlweise === "creditcard") that.processCreditcard(stripeClient);
        if (that.zahlweise === "sofort")     that.processSofort(stripeClient);
    });

}

};


exports.paymentform = paymentform;