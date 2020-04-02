    <style>
        .content {
            width: 700px;
            padding: 10px;
            line-height: 1.5;
            font-size: 20px;
            word-wrap: break-word;
        }
    </style>
<img align="right" src="{{url('images/logo.png')}}" alt="logo" />

    <p style="font-size: 14px; padding: 40px; text-align: left">Smart Health Heidelberg GmbH | Handschuhsheimer Landstr. 9/1 | 69120 Heidelberg</p>

<p>{{ $enquire->first_name . ' ' . $enquire->last_name }}</p>
<p>Street address of patient (i.e. Landstr. 9)</p>
<p>ZIP CODE and Cityname of patient</p>
<br>
<p>Number of the invoice: OH-{{$billing->id}}</p>
<p>Date of the invoice: OH-{{$billing->created_at}}</p>
<p>Number of order: {{$enquire->id}}</p>
<p>UST. ID: DE315308615</p>
<br>
    <h3 style="text-align: left">Rechnungsbeleg</h3>
<br>
<div class="content">
    <style type="text/css">
        .tg  {border-collapse:collapse;border-spacing:0;}
        .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
        .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
        .tg .tg-baqh{text-align:center;vertical-align:top}
        .tg .tg-nrix{text-align:center;vertical-align:middle}
        .tg .tg-0lax{text-align:left;vertical-align:top}
    </style>
    <table class="tg" style="undefined;table-layout: fixed; width: 703px">
        <colgroup>
            <col style="width: 177px">
            <col style="width: 359px">
            <col style="width: 94px">
            <col style="width: 73px">
        </colgroup>
        <tr>
            <th class="tg-nrix"><span style="font-weight:bold">Datum</span></th>
            <th class="tg-baqh"><span style="font-weight:bold">Leistung</span></th>
            <th class="tg-baqh"><span style="font-weight:bold">Betrag</span></th>
            <th class="tg-baqh"><span style="font-weight:bold">Summe</span></th>
        </tr>
        <tr>
            <td class="tg-baqh"><span style="font-weight:bold">{{$enquire->created_at}}</span></td>
            <td class="tg-0lax"><span style="font-weight:bold">Vermittlung der Online-Beratung (u.a. Zahlung, Datenübertragung, Server)</span></td>
            <td class="tg-baqh"><span style="font-weight:bold">{{$price}} €</span></td>
            <td class="tg-baqh"><span style="font-weight:bold">{{$price}} €</span></td>
        </tr>
        <tr>
            <td class="tg-0lax"><span style="font-weight:bold">Summe inkl. 19% USt.:</span></td>
            <td class="tg-0lax"></td>
            <td class="tg-baqh"><span style="font-weight:bold">{{$price}} €</span></td>
            <td class="tg-baqh">{{$price}} €</td>
        </tr>
        <tr>
            <td class="tg-baqh"><span style="font-weight:bold">Gezahlter Betrag</span></td>
            <td class="tg-0lax"></td>
            <td class="tg-baqh"><span style="font-weight:bold">{{$price}} €</span></td>
            <td class="tg-baqh"><span style="font-weight:bold">{{$price}} €</span></td>
        </tr>
    </table>
</div>