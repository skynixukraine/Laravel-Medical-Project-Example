<!-- ------------ -->
<!-- payment_form -->
<!-- ------------ -->
<div class="container" id="payment_form_container" style="display:none;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h2>{{ __('case-submit.payment_headline') }}</h2>
            <form id="payment_form" class="form-horizontal" method="POST" action="">
                <div class="form-group">
                    <label for="submission_responsetime" class="col-sm-3 control-label">{{ __('case-submit.payment_responsetime') }}</label>
                    <div class="col-sm-9" id="submission_responsetime_wrapper">
                        {{--we are dynamically adding a dropdown (if there are many responsetimes available to choose from)--}}
                        {{--Or a hidden input and a paragraph if there is only 1 responsetime available--}}
                        <p id="submission_responsetime_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="zahlweise" class="col-sm-3 control-label" style="margin-top: 11px">{{ __('case-submit.payment_options') }}</label>
                    <div class="col-sm-7">
                        <label class="radio" style="border-bottom: 1px solid rgb(204, 204, 204); padding-bottom: 10px;">
                            <input type="radio" name="zahlweise" value="creditcard" class="submission_form_radio" style="margin-top: 13px"
                                   checked><span class="payment-label-creditcard">{{ __('case-submit.payment_creditcard') }}</span>
                            <img src="images/visa.jpg" class="payment-provider-logo" alt="VISA"
                            ><img src="images/amex.jpg" class="payment-provider-logo" alt="American Express"
                            ><img src="images/mastercard.jpg" class="payment-provider-logo" alt="Mastercard">
                        </label>
                        <label class="radio">
                            <input type="radio" name="zahlweise" value="sofort" class="submission_form_radio" style="margin-top: 13px"
                            ><span class="payment-label-sofort">{{ __('case-submit.payment_sofort') }}</span>
                            <img src="images/klarna.png" class="payment-provider-logo" style="height: 20px" alt="Klarna"
                            ><img src="images/tuev.png" class="payment-provider-logo" alt="TÜV Saarland">
                        </label>
                    </div>
                </div>

                <!-- creditcard -->
                <div class="form-row form-group stripe-payment-method" id="stripe-payment-creditcard"
                     data-stripekey="{{ config('services.stripe.key') }}">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-9" style="padding-left: 0; padding-right: 0">
                        <div id="card-errors" role="alert"></div>
                    </div>
                </div>

                <!-- sofort -->
                <div class="form-row form-group stripe-payment-method" id="stripe-payment-sofort" style="display: none">
                    <label for="card-element" class="col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                        <p id="sofort_error" class="submission_field_error" style="display: none"></p>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9" style="padding-left: 0">
                        <button type="submit" class="btn btn-primary btn-lg" id="payment_submit">
                            <img src="images/spinner.svg" id="payment_spinner"
                                 style="display:none; margin-right: 10px; width:30px;">
                            <span id="payment_submit_text">{{ __('case-submit.payment_submit') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>