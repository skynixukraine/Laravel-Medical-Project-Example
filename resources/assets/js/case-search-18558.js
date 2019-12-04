let paymentform = require('./modules/paymentform');
var stripepaymentform = paymentform.paymentform; // fixme: maybe I will someday understand how to use export/require correctly and can avoid the double paymentform

$(function() {

    // *** star rating: https://bootsnipp.com/snippets/v70B ***
    var __slice = [].slice;
    (function($, window) {
        var Starrr;

        Starrr = (function() {
            Starrr.prototype.defaults = {
                rating: void 0,
                numStars: 5,
                change: function(e, value) {}
            };

            function Starrr($el, options) {
                var i, _, _ref,
                    _this = this;

                this.options = $.extend({}, this.defaults, options);
                this.$el = $el;
                _ref = this.defaults;
                for (i in _ref) {
                    _ = _ref[i];
                    if (this.$el.data(i) != null) {
                        this.options[i] = this.$el.data(i);
                    }
                }
                this.createStars();
                this.syncRating();
                this.$el.on('mouseover.starrr', 'span', function(e) {
                    return _this.syncRating(_this.$el.find('span').index(e.currentTarget) + 1);
                });
                this.$el.on('mouseout.starrr', function() {
                    return _this.syncRating();
                });
                this.$el.on('click.starrr', 'span', function(e) {
                    return _this.setRating(_this.$el.find('span').index(e.currentTarget) + 1);
                });
                this.$el.on('starrr:change', this.options.change);
            }

            Starrr.prototype.createStars = function() {
                var _i, _ref, _results;

                _results = [];
                for (_i = 1, _ref = this.options.numStars; 1 <= _ref ? _i <= _ref : _i >= _ref; 1 <= _ref ? _i++ : _i--) {
                    _results.push(this.$el.append("<span class='glyphicon .glyphicon-star-empty'></span>"));
                }
                return _results;
            };

            Starrr.prototype.setRating = function(rating) {
                if (this.options.rating === rating) {
                    rating = void 0;
                }
                this.options.rating = rating;
                this.syncRating();
                return this.$el.trigger('starrr:change', rating);
            };

            Starrr.prototype.syncRating = function(rating) {
                var i, _i, _j, _ref;

                rating || (rating = this.options.rating);
                if (rating) {
                    for (i = _i = 0, _ref = rating - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
                        this.$el.find('span').eq(i).removeClass('glyphicon-star-empty').addClass('glyphicon-star');
                    }
                }
                if (rating && rating < 5) {
                    for (i = _j = rating; rating <= 4 ? _j <= 4 : _j >= 4; i = rating <= 4 ? ++_j : --_j) {
                        this.$el.find('span').eq(i).removeClass('glyphicon-star').addClass('glyphicon-star-empty');
                    }
                }
                if (!rating) {
                    return this.$el.find('span').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
                }
            };

            return Starrr;

        })();
        return $.fn.extend({
            starrr: function() {
                var args, option;

                option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
                return this.each(function() {
                    var data;

                    data = $(this).data('star-rating');
                    if (!data) {
                        $(this).data('star-rating', (data = new Starrr($(this), option)));
                    }
                    if (typeof option === 'string') {
                        return data[option].apply(data, args);
                    }
                });
            }
        });
    })(window.jQuery, window);
    $(function() {
        return $(".starrr").starrr();
    });

    const api_url = process.env.MIX_API_URL;
    var web_url = process.env.MIX_WEB_URL;
    if (config_partner_id === "ita") web_url = process.env.MIX_WEB_URL_ITA;
    if (config_partner_id === "sna") web_url = process.env.MIX_WEB_URL_SNA;

    const partner_language = (typeof(config_partner_language) !== 'undefined') ? config_partner_language : "de";

    const symptoms_en = {
        1: 'Rash',
        2: 'Itching',
        3: 'Swelling',
        4: 'Redness',
        5: 'Pain',
        6: 'Flaking',
        7: 'Mole',
        8: 'Spots',
        9: 'Others'
    };
    const side_en = {
        'einseitig' : 'One side',
        'beidseitig' : 'Both sides',
        'nicht sicher' : 'Not sure',
    };
    const since_en = {
        'weniger als zwei Tage' : 'Less than 2 days',
        'zwischen 2 bis 6 Tagen' : 'Between 2 and 6 days',
        'zwischen 1 bis 4 Wochen' : 'Between 1 and 4 weeks',
        'länger als 1 Monat' : 'Longer than 1 month',
        'chronisch/permanent' : 'chronic/permanent',
        'andere Angabe' : 'Other',
    };

    $('.case_search').submit(function (event) {
        event.preventDefault();
        let url = api_url + "/api/submissions/" + $(this).find('input')[0].value;
        let case_search_error = $('#case_search_error');
        let case_search_error_message = $('#case_search_error_message');
        case_search_error_message.text('');

        clearCaseSearchData();

        $.get( url, { partner_id: config_partner_id }, function(submission) {

            enableTabs(submission.status);

            if (submission.status === "setup") {
                tabMyCase(submission, url);
                tabPayment(submission, url);
            }
            else {
                tabResult(submission, url);
                tabMyCase(submission, url);
                tabQuestions(submission);
                tabEvaluation(submission, url);
            }

            // show all data
            $('#case_search_data').show();
            resizeCaseSearchIFrame();
        })
        .fail(function() {
            case_search_error_message.text((partner_language === "de") ? 'Fallnummer nicht gefunden.' : 'Case number not found.');
            case_search_error.fadeTo(2000, 500).slideUp(500);
            resizeCaseSearchIFrame();
        });
    });

    /**
     * depending on the submission status we create different tabs
     * @param status
     */
    let enableTabs = function(status) {

        // we need to wait some ms after the tabs were made visible before we can set the active tab
        if (status === "setup") {
            $('#tabs-setup').show();
            setTimeout(function() {
                $('#tabs-setup a[href="#case_search_payment"]').tab('show');
            }, 250);
        }
        else {
            $('#tabs-default').show();
            setTimeout(function() {
                $('#tabs-default a[href="#case_search_result"]').tab('show');
            }, 250);
        }
    };

    let resizeCaseSearchIFrame = function() {
        let iframeheight = $("#case-search").outerHeight(true)+10;
        window.parent.postMessage({'caseSearch' : {'iframeHeight': iframeheight }}, '*');
    };

    let clearCaseSearchData = function () {

        // reset both tab-sets again
        $('#tabs-setup').hide();
        $('#tabs-default').hide();

        $('.case_search_info').text('');
        $('#case_search_data').hide();
        $('#case_search_answered_by_photo').attr('src', '');
        $('#case_search_photo_overview').attr('src', '');
        $('#case_search_photo_closeup').attr('src', '');
        $('#case_search_photo_closeup2').attr('src', '');
        $('#case_search_questions_list').html('');
    };

    let symptomsAsString = function (submission) {

        let symptoms = submission["symptoms"];

        // order symptoms bei "order"
        symptoms = symptoms.sort(function(a,b) {return (a["order"] > b["order"]) ? 1 : ((b["order"] > a["order"]) ? -1 : 0);} );

        // translate symptoms to EN and add other symptoms
        symptoms = symptoms.map(function (symp) {
            var s = partner_language === "en" ? symptoms_en[symp.id] : symp.name;
            if (symp.id === 9) {
                s += ' (' + submission.other_symptoms + ')';
            }
            return s;
        });

        return symptoms.join(', ');
    };

    let tabPayment = function (submission, url) {
        stripepaymentform.setUrls(api_url, web_url);
        stripepaymentform.setLanguage(partner_language);
        stripepaymentform.setStripeClient(Stripe);
        stripepaymentform.setCreditcardCallbackSuccess(function () {
            resizeCaseSearchIFrame();
        });
        $('#payment_form_container').show();
        stripepaymentform.show(submission.transaction_id, config_partner_id);
    };

    let tabResult = function (submission, url) {
        if (submission.answered_by) {
            $('#case_search_status_answered').show();
            $('#case_search_result_answered').show();

            let answered_at = mysqlTimeStampToDate(submission.answered_at);
            let answered_by = submission.answered_by;
            $('#case_search_answered_at').text(answered_at.getDate() + '.' + (answered_at.getMonth()+1) + '.' + answered_at.getFullYear());
            $('#case_search_answered_by_photo').attr("src", answered_by.photo_url);
            $('.case_search_answered_by_name').text((answered_by.title ? answered_by.title : "") + " "+ answered_by.first_name + " " + answered_by.last_name);
            $('#case_search_answered_by_city').text(answered_by.city);
            let country = answered_by.country;
            if (answered_by.country == 'DE') country = (partner_language === "de") ? 'Deutschland' : 'Germany';
            if (answered_by.country == 'AT') country = (partner_language === "de") ? 'Österreich' : 'Austria';
            if (answered_by.country == 'CH') country = (partner_language === "de") ? 'Schweiz' : 'Switzerland';
            $('#case_search_answered_by_country').text(country);
            $('#case_search_answer').html(submission.answer.replace(/\n/g,"<br>"));
        }
        else {
            $('#case_search_status_open').show();
            $('#case_search_result_open').show();

            let submission_minutesLeft = parseInt(submission.minutesLeft);
            let submission_hoursLeft = Math.round( submission_minutesLeft / 60);
            let submission_timeleft = '';

            if (submission_minutesLeft >= 0) {
                submission_timeleft = (partner_language === "de") ? "Die Bearbeitung erfolgt spätestens in " + submission_hoursLeft + " Stunden."
                                                                  : "The evaluation takes place within a maximum of " + submission_hoursLeft + " hours";
            }
            else {
                submission_timeleft = (partner_language === "de") ? "Wir müssen uns entschuldigen. Die Bearbeitung ist überfällig seit " + Math.abs(submission_hoursLeft) + " Stunden."
                                                                  : "We have to apologize. The processing is overdue for " + Math.abs(submission_hoursLeft) + " hours.";
            }
            $('#case_search_time_left').text(submission_timeleft);

            if (submission_minutesLeft < 0) $('#case_search_time_left').attr('class', 'text-danger');
            else                            $('#case_search_time_left').removeAttr('class', 'text-danger');
            $('#case_search_open').show();
        }
    };

    let tabMyCase = function (submission, url) {
        let created_at = mysqlTimeStampToDate(submission.created_at);
        $('#case_search_submission_id').text(submission.submission_id);
        $('#case_search_symptoms').text(symptomsAsString(submission));
        $('#case_search_affected_area').html(submission.affected_area.replace(/\n/g,"<br>"));
        let since = (partner_language === "en") ? since_en[submission.since] : submission.since;
        if (submission.since_other && submission.since_other !== "Bitte näher erläutern:") since += ' (' + submission.since_other + ')';
        $('#case_search_since').html(since.replace(/\n/g,"<br>"));
        let treated;
        if (submission.treated) {
            treated = (partner_language === "en") ? 'Yes ' : 'Ja ';
            treated += '(' + submission.treatment + ')';
        }
        else {
            treated = (partner_language === "en") ? 'No' : 'Nein';
        }
        $('#case_search_treated').html(treated.replace(/\n/g,"<br>"));
        $('#case_search_description').html(submission.description.replace(/\n/g,"<br>"));

        $('#case_search_created_at').text(created_at.getDate() + '.' + (created_at.getMonth()+1) + '.' + created_at.getFullYear() + ', ' + created_at.getHours() + ':' + created_at.getMinutes() + ' Uhr');
        $('#case_search_side').text((partner_language === "en") ? side_en[submission.side] : submission.side);
        $('#case_search_gender').text(submission.gender);
        $('#case_search_age').text(submission.age);

        $('#case_search_photo_overview').attr('src', url + '/photo/' + submission.overview_image_id + '/600');
        $('#case_search_photo_closeup').attr('src', url + '/photo/' + submission.closeup_image_id + '/600');
        $('#case_search_photo_closeup2').attr('src', url + '/photo/' + submission.closeup2_image_id + '/600');
    };

    let tabQuestions = function (submission) {
        let questionsList = $('#case_search_questions_list'),
            questionsBadge = $('#questions_badge');
        // we need to remove all child elements inside case_search_questions_list
        // when this funtion is called after submitting the answer
        questionsList.empty();

        // first we hide the badge
        questionsBadge.hide();

        if (submission.questions.length > 0) {

            // sort questions by date
            submission.questions.sort(function(a,b){
                return new Date(b.created_at) - new Date(a.created_at);
            });

            $.each(submission.questions, function(i, q)
            {
                let answer;
                if (q.answered_at == null) {

                    // we show the badge in the questions tab because we have at least one open question
                    questionsBadge.show();

                    answer = '<h4 class="warning">' + ((partner_language === "en") ? "My Answer" : "Meine Antwort") + '</h4>' +
                        '<form class="form-horizontal" id="question_answer">' +
                        '<input type="hidden" id="question_submission_id" name="submission_id" value="' + submission.submission_id + '">' +
                        '<input type="hidden" id="question_id" name="question_id" value="' + q.id + '">' +
                        '<div class="form-group">' +
                        '<div class="col-sm-10">' +
                        '<textarea class="form-control" id="answer" name="answer" required rows="3"></textarea>' +
                        '<p id="answer_error" style="display: none;" class="text-danger"></p>' +
                        '</div>' +
                        '<div class="col-sm-2">' +
                        '<button type="submit" id="answer_submit_btn" class="btn btn-primary">' + ((partner_language === "en") ? "Submit" : "Senden") + '</button>' +
                        '</div>' +
                        '</div>' +
                        '</form>';
                }
                else {
                    answer = '<blockquote>' +
                        '<p>' + q.answer.replace(/\n/g,"<br>") + '</p>' +
                        '<footer>Meine Antwort vom ' + (new Date(q.answered_at)).toLocaleString('de-DE') + '</footer>' +
                        '</blockquote>';
                }
                let li = '<div class="question_wrapper">' +
                    '<div class="question">' +
                    '<blockquote>' +
                    '<p>' + q.question.replace(/\n/g,"<br>") + '</p>' +
                    '<footer>' + q.asked_by.title + ' ' + q.asked_by.first_name + ' ' + q.asked_by.last_name +
                    ' - ' + (new Date(q.created_at)).toLocaleString('de-DE') + '</footer>' +
                    '</blockquote>' +
                    '</div>' +
                    '<div class="answer">' +
                    answer +
                    '</div>' +
                    '</div>';

                $(li).appendTo(questionsList);
            });
            $('#case_search_questions_list').show();

            $('#question_answer').submit(function (event) {
                event.preventDefault();
                $('#answer_submit_btn').prop('disabled', true);
                let submission_id = $('#question_submission_id').val(),
                    question_id = $('#question_id').val(),
                    url = api_url + "/api/submission/" + submission_id + "/question/" + question_id + "/answer",
                    answer_error = $('#answer_error');
                answer_error.text('').hide();

                $.post( url, $(this).serialize())
                    .done(function(msg) {
                        submission.questions = submission.questions.map(function (question) {
                            if (question.id === parseInt(question_id)) {
                                question.answered_at = dateToMysqlFormat(new Date());
                                question.answer = $('#answer').val();
                            }
                            return question;
                        });
                        tabQuestions(submission)
                        resizeCaseSearchIFrame();
                    })
                    .fail(function(xhr, status, error) {
                        $('#answer_submit_btn').prop('disabled', false);
                        let errors = xhr.responseJSON.errors;
                        if (errors.answer) {
                            answer_error.text(errors.answer[0]).show();
                        }
                        resizeCaseSearchIFrame();
                    });
            });
        }
        else {
            $('#case_search_questions_none').show();
        }
    };

    let tabEvaluation = function (submission, url) {
        if (submission.answered_by) {
            if (submission.stars) {
                $('#case_search_evaluation_answered').show();
                $('#case_search_evaluation_answered_stars').text(submission.stars + "/5");
                $('#case_search_evaluation_answered_feedback').text(submission.feedback);
            }
            else {
                // resetting the form (doesnt work perfectly)
                $('#submission_stars').val("");
                $('#submission_feedback').val("");

                // whenever the star rating changes, the value of the hidden field "submission_stars" will be updated
                $('#stars').on('starrr:change', function(e, value){
                    $('#submission_stars').val(value);
                });

                $('#case_search_evaluation_form').show().submit(function (event) {
                    event.preventDefault();
                    let url = api_url + "/api/submission/" + submission.submission_id + "/evaluate/";
                    // let data = {stars: '2', feedback: 'bla'};
                    let evaluation_error_stars = $('#evaluation_error_stars');
                    let evaluation_error_feedback = $('#evaluation_error_feedback');
                    evaluation_error_stars.text('').hide();
                    evaluation_error_feedback.text('').hide();

                    $.post( url, $(this).serialize())
                        .done(function(msg) {
                            $('#case_search_evaluation_form').hide();
                            $('#case_search_evaluation_answered_stars').text(msg.stars + "/5");
                            $('#case_search_evaluation_answered_feedback').text(msg.feedback);
                            $('#case_search_evaluation_answered').show();
                            resizeCaseSearchIFrame();
                        })
                        .fail(function(xhr, status, error) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.feedback) {
                                evaluation_error_feedback.text(errors.feedback[0]).show();
                            }
                            if (errors.stars) {
                                evaluation_error_stars.text(errors.stars[0]).show();
                            }
                            resizeCaseSearchIFrame();
                        });
                });
            }
        }
        else {
            $('#case_search_evaluation_open').show();
        }
    };

    let mysqlTimeStampToDate = function (timestamp) {
        //function parses mysql datetime string and returns javascript Date object
        //input has to be in this format: 2007-06-05 15:26:02
        let regex=/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$/;
        let parts=timestamp.replace(regex,"$1 $2 $3 $4 $5 $6").split(' ');
        return new Date(parts[0], parts[1]-1, parts[2], parts[3], parts[4], parts[5]);
    };

    let dateToMysqlFormat = function (date) {
        date = date.getUTCFullYear() + '-' +
            ('00' + (date.getUTCMonth()+1)).slice(-2) + '-' +
            ('00' + date.getUTCDate()).slice(-2) + ' ' +
            ('00' + date.getUTCHours()).slice(-2) + ':' +
            ('00' + date.getUTCMinutes()).slice(-2) + ':' +
            ('00' + date.getUTCSeconds()).slice(-2);
        return date;
    };


    /* =============================================================================
     * On Window Load
     * ========================================================================== */
    window.onload = function() {
        resizeCaseSearchIFrame();
    };

    /* =============================================================================
     * On Window Resize
     * ========================================================================== */
    $(window).resize(resizeCaseSearchIFrame);

    /* =============================================================================
     * On Bootstrap Tab Change
     * ========================================================================== */
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        resizeCaseSearchIFrame();
    });

});