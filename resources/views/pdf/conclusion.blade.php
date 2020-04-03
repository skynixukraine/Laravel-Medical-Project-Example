    <style>
        .content {
            width: 700px;
            padding: 10px;
            line-height: 1.5;
            font-size: 20px;
            word-wrap: break-word;
        }
    </style>
<img src="images/logo.png" alt="logo" />
<p>Patient: {{ $enquire->first_name . ' ' . $enquire->last_name }}</p>
<p>Doctor: {{ $enquire->doctor->title->name . ' ' . $enquire->doctor->first_name . ' ' . $enquire->doctor->last_name }}</p>
<p>Date: {{ $enquire->conclusion_created_at->format('d.m.Y H:i:s') }}</p>

<h1 style="text-align: center">Conclusion</h1>
<div class="content">
    {!! $enquire->conclusion !!}
</div>