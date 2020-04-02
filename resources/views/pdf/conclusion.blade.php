<img src="images/logo.png" alt="logo" />
<p>Patient: {{ $enquire->first_name . ' ' . $enquire->last_name }}</p>
<p>Doctor: {{ $enquire->doctor->title->name . ' ' . $enquire->doctor->first_name . ' ' . $enquire->doctor->last_name }}</p>
<p>Date: {{ $enquire->conclusion_created_at->format('d.m.Y H:i:s') }}</p>

<h1 style="text-align: center">Conclusion</h1>
{!! $enquire->conclusion !!}