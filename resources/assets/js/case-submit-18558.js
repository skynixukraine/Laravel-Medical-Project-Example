try {
    require('fine-uploader/lib/jquery/traditional');
    let paymentform = require('./modules/paymentform');
    var stripepaymentform = paymentform.paymentform;
} catch (e) {}

$(function() {

    const api_url = process.env.MIX_API_URL;
    var web_url = process.env.MIX_WEB_URL;
    if (config_partner_id === "ita") web_url = process.env.MIX_WEB_URL_ITA;
    if (config_partner_id === "sna") web_url = process.env.MIX_WEB_URL_SNA;

    const partner_language = (typeof(config_partner_language) !== 'undefined') ? config_partner_language : "de";

    let resizeCaseSubmitIFrame = function() {
        let iframeheight = $("#case-submit").outerHeight(true) + 50;
        window.parent.postMessage({'caseSubmit' : {'iframeHeight': iframeheight }}, '*');
    };

    /* =============================================================================
     * On Window Resize
     * ========================================================================== */
    $(window).resize(resizeCaseSubmitIFrame);


    // ***********
    // ***********
    // submit case
    // ***********
    // ***********

    $('#uploader-closeup').fineUploader({
        template: 'upload-template',
        request: {
            endpoint: api_url + '/api/submissions/photoupload'
        },
        deleteFile: {
            enabled: true, // defaults to false
            endpoint: api_url + '/api/submissions/photoupload'
        },
        thumbnails: {
            placeholders: {
                waitingPath: '/images/upload_waiting.png',
                notAvailablePath: '/images/upload_not_available.png'
            }
        },
        multiple: false,
        validation: {
            allowedExtensions: ['jpeg', 'jpg', 'png', 'bmp'],
            itemLimit: 1,
            image: {
                minWidth: 300
            },
            sizeLimit: 10485760 // 10 MB = 10 * 1024 kB * 1024 bytes
        },
        messages: {
            tooManyItemsError: (partner_language === "de") ? 'Sie haben bereits ein Foto hochgeladen. Wenn Sie stattdessen ein anderes Foto verwenden möchten, löschen Sie bitte erst das vorherige.'
                                                           : 'You have already uploaded a photo. If you want to use another photo instead, please delete the previous one first.',
            minWidthImageError: (partner_language === "de") ? 'Dieses Bild ist zu klein.'
                                                            : 'The photo is too small.'
        }
    }).on('complete', function (event, id, name, responseJSON) {
        $('#submission_closeup_image_id').val(responseJSON.image_id);
        resizeCaseSubmitIFrame();
    }).on('deleteComplete', function(id, xhr, isError) {
        $('#submission_closeup_image_id').val("");
        resizeCaseSubmitIFrame();
    });

    $('#uploader-closeup2').fineUploader({
        template: 'upload-template',
        request: {
            endpoint: api_url + '/api/submissions/photoupload'
        },
        deleteFile: {
            enabled: true, // defaults to false
            endpoint: api_url + '/api/submissions/photoupload'
        },
        thumbnails: {
            placeholders: {
                waitingPath: '/images/upload_waiting.png',
                notAvailablePath: '/images/upload_not_available.png'
            }
        },
        multiple: false,
        validation: {
            allowedExtensions: ['jpeg', 'jpg', 'png', 'bmp'],
            itemLimit: 1,
            image: {
                minWidth: 300
            },
            sizeLimit: 10485760 // 10 MB = 10 * 1024 kB * 1024 bytes
        },
        messages: {
            tooManyItemsError: (partner_language === "de") ? 'Sie haben bereits ein Foto hochgeladen. Wenn Sie stattdessen ein anderes Foto verwenden möchten, löschen Sie bitte erst das vorherige.'
                                                           : 'You have already uploaded a photo. If you want to use another photo instead, please delete the previous one first.',
            minWidthImageError: (partner_language === "de") ? 'Dieses Bild ist zu klein.'
                                                            : 'The photo is too small.'
        }
    }).on('complete', function (event, id, name, responseJSON) {
        $('#submission_closeup2_image_id').val(responseJSON.image_id);
        resizeCaseSubmitIFrame();
    }).on('deleteComplete', function(id, xhr, isError) {
        $('#submission_closeup2_image_id').val("");
        resizeCaseSubmitIFrame();
    });

    $('#uploader-overview').fineUploader({
        template: 'upload-template',
        request: {
            endpoint: api_url + '/api/submissions/photoupload'
        },
        deleteFile: {
            enabled: true, // defaults to false
            endpoint: api_url + '/api/submissions/photoupload'
        },
        thumbnails: {
            placeholders: {
                waitingPath: '/images/upload_waiting.png',
                notAvailablePath: '/images/upload_not_available.png'
            }
        },
        multiple: false,
        validation: {
            allowedExtensions: ['jpeg', 'jpg', 'png', 'bmp'],
            itemLimit: 1,
            image: {
                minWidth: 300
            },
            sizeLimit: 10485760 // 10 MB = 10 * 1024 kB * 1024 bytes
        },
        messages: {
            tooManyItemsError: (partner_language === "de") ? 'Sie haben bereits ein Foto hochgeladen. Wenn Sie stattdessen ein anderes Foto verwenden möchten, löschen Sie bitte erst das vorherige.'
                                                           : 'You have already uploaded a photo. If you want to use another photo instead, please delete the previous one first.',
            minWidthImageError: (partner_language === "de") ? 'Dieses Bild ist zu klein.'
                                                            : 'This photo is too small.'
        }
    }).on('complete', function (event, id, name, responseJSON) {
        $('#submission_overview_image_id').val(responseJSON.image_id);
        resizeCaseSubmitIFrame();
    }).on('deleteComplete', function(id, xhr, isError) {
        $('#submission_overview_image_id').val("");
        resizeCaseSubmitIFrame();
    });

    $('#symptom_sonstiges').change(function () {
        if (this.checked) $('#submission_other_symptoms').fadeIn('fast');
        else $('#submission_other_symptoms').val('').fadeOut('fast');
    });

    $("#submission_since").change(function() {
        if (this.value === 'andere Angabe') $('#submission_since_other').fadeIn('fast');
        else $('#submission_since_other').fadeOut('fast');
    });

    $('input:radio[name="treated"]').change(function() {
        if (this.value === '1') $('#submission_treatment').fadeIn('fast');
        else $('#submission_treatment').val('').fadeOut('fast');
    });

    // send the submission_form (and showing error messages)
    $('#submission_form').submit(function (event) {
        event.preventDefault();
        let url = api_url + "/api/submissions/";

        // disable submit button
        $("#submission_submit").prop("disabled",true);

        // hide all errors
        $('.submission_field_error').hide();

        $.post(url,$(this).serialize())
            .done(function(msg) {
                // reduce opacity of submission_form_container
                $('#submission_form_container').addClass('reduced-opacity');

                // make all fields readonly/disabled
                // todo: disable fineuploader
                $('#submission_form textarea').prop('readonly', true);
                $('#submission_form select, #submission_form input').prop('disabled', true);

                // store the returned transaction_id that we just created on the server
                let submission_id_created = msg.submission_id;
                let transaction_id_created = msg.transaction_id;

                // show submission id to user
                $('#submission_created_submission_id').text(submission_id_created);
                $('#submission_created').show();

                // ---------------------------
                // --- stripe payment form ---
                // ---------------------------
                stripepaymentform.setUrls(api_url, web_url);
                stripepaymentform.setLanguage(partner_language);
                stripepaymentform.setStripeClient(Stripe);
                // stripepaymentform.setCreditcardCallbackSuccess(function () {
                //     $('#submission_form_container').remove();
                //     resizeCaseSubmitIFrame();
                // });
                $('#payment_form_container').show();
                stripepaymentform.show(transaction_id_created, config_partner_id);
                $('html, body').animate({
                    scrollTop: $("#submission_created").offset().top
                }, 500);
            })
            .fail(function(xhr, status, error) {
                let errors = xhr.responseJSON.errors;
                $.each( errors, function( key, value ) {
                    let fieldname = 'submission_' + key + '_error';
                    $('#' + fieldname).show().text(value);
                });
            })
            .always(function() {
                $("#submission_submit").prop("disabled",false);
                resizeCaseSubmitIFrame();
            });
    });

    // the new approach: Just resizing the iframe every second
    setInterval(resizeCaseSubmitIFrame, 1000);

});