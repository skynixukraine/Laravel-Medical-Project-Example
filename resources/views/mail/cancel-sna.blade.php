@component('mail::message')
# Dear user of Snapdoc,

it looks like you've just entered your case completely,
but ultimately did not send it to our specialists.

If you have a payment problem, feel free to reply to this e-mail.
We will then try to fix it as soon as possible.

The processing time is currently less than two hours on average,
no matter what maximum processing period you choose.
Accordingly, you receive a professional assessment of your skin problem in no time.
Only skin specialists with at least 10 years of clinical experience from Heidelberg are allowed
Join our online dermatologist service.
In about 70% of cases, we can help the patient so much that they no longer need to go to the office.
Our service is the only online dermatologist service in Germany approved by a regional medical association
and 100% a German product.

If you would like to close your case now, you can do so here:

@component('mail::button', ['url' => 'https://online-dermatologist.net/view-case/'])
    My Case
@endcomponent

{{--Bei Rückfragen steht ihnen unser Team zur Verfügung.--}}

Sincerely, <br>
Your Snapdoc team

@component('mail::subcopy')
If you can not click on the link "My case", please use the following link:
[https://online-dermatologist.net/view-case/](https://online-dermatologist.net/view-case/)
@endcomponent

@endcomponent
