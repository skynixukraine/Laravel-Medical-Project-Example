$(function() {

    const api_url = process.env.MIX_API_URL;
    let urlParams = new URLSearchParams(window.location.search),
        transaction_id = urlParams.get('transaction_id'),
        responsetime = urlParams.get('responsetime'),
        stripekey = $('#stripekey').data('stripekey'),
        stripeClient = Stripe(stripekey);

    let url = api_url + "/api/stripe/createCheckoutSession";
    $.post(url, {
        transaction_id: transaction_id,
        responsetime: responsetime})
        .done(function(msg) {
            stripeClient.redirectToCheckout({
                sessionId: msg.session_id
            }).then(function (result) {
                // If `redirectToCheckout` fails due to a browser or network
                // error, display the localized error message to your customer
                // using `result.error.message`.
                $('#card-errors').text(result.error.message);
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
});