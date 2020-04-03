    <style>
        .content {
            width: 700px;
            padding: 10px;
            line-height: 1.5;
            font-size: 20px;
            word-wrap: break-word;
        }
    </style>
<img align="right" width="125" height="80" src="{{ 'data:' . 'image/png' . ';base64,' . $logo }}" alt="logo" />
<br>
    <p style="font-size: 14px; padding: 60px;"><u>Smart Health Heidelberg GmbH | Handschuhsheimer Landstr. 9/1 | 69120 Heidelberg</u></p>
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
<br>
<p>Vielen Dank für Ihr Vertrauen!</p>
<br>
<p>Freundliche Grüße</p>
<p>Ihr Team von Online Hautarzt vor Ort</p>



<style>
    .page-break {
        page-break-after: always;
    }
</style>
<div class="page-break"></div>

<p>{{$enquire->doctor->title->name}} {{$enquire->doctor->first_name}} {{$enquire->doctor->last_name}}</p>
<p>{{$enquire->doctor->location->address}}</p>
<p>{{$enquire->doctor->location->postal_code}} {{$enquire->doctor->location->city}}</p>

<p>{{ $enquire->first_name . ' ' . $enquire->last_name }}</p>
<p>Street address of patient (i.e. Landstr. 9)</p>
<p>ZIP CODE and Cityname of patient</p>

<p>Number of the invoice: OH-{{$billing->id}}</p>
<p>Date of the invoice: OH-{{$billing->created_at}}</p>
<p>Number of order: {{$enquire->id}}</p>
<br>
<h3 style="text-align: left">Rechnungsbeleg</h3>
<br>
<div class="content">
    <style type="text/css">
        .tg  {border-collapse:collapse;border-spacing:0;}
        .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
        .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
        .tg .tg-cly1{text-align:left;vertical-align:middle}
        .tg .tg-baqh{text-align:center;vertical-align:top}
        .tg .tg-0lax{text-align:left;vertical-align:top}
        .tg .tg-nrix{text-align:center;vertical-align:middle}
    </style>
    <table class="tg" style="undefined;table-layout: fixed; width: 700px">
        <colgroup>
            <col style="width: 130px">
            <col style="width: 93px">
            <col style="width: 108px">
            <col style="width: 209px">
            <col style="width: 86px">
            <col style="width: 74px">
        </colgroup>
        <tr>
            <th class="tg-cly1"><span style="font-weight:bold">Datum</span></th>
            <th class="tg-cly1"><span style="font-weight:bold">Ziffer</span></th>
            <th class="tg-cly1"><span style="font-weight:bold">Anzahl</span></th>
            <th class="tg-cly1"><span style="font-weight:bold">Bezeichnung</span></th>
            <th class="tg-0lax"><span style="font-weight:bold">Faktor</span></th>
            <th class="tg-0lax"><span style="font-weight:bold">Betrag</span></th>
        </tr>
        <tr>
            <td class="tg-nrix"><span style="font-weight:bold">{{$enquire->created_at}}</span></td>
            <td class="tg-cly1">1A</td>
            <td class="tg-cly1">1</td>
            <td class="tg-cly1">Teledermatologische Beratung gemäß GOÄ 1</td>
            <td class="tg-0lax">1</td>
            <td class="tg-0lax">4,66€</td>
        </tr>
        <tr>
            <td class="tg-nrix"><span style="font-weight:bold">{{$enquire->created_at}}</span></td>
            <td class="tg-cly1">5A</td>
            <td class="tg-cly1">1</td>
            <td class="tg-cly1">Symptombezogene Untersuchung gemäß GOÄ 5A</td>
            <td class="tg-0lax">1</td>
            <td class="tg-0lax">4,66€</td>
        </tr>
        <tr>
            <td class="tg-nrix"><span style="font-weight:bold">{{$enquire->created_at}}</span></td>
            <td class="tg-cly1">75A</td>
            <td class="tg-cly1">1</td>
            <td class="tg-cly1">Teledermatologischer Bericht entsprechend GOÄ 75 Ausführlicher schriftlicher Krankheits- und Befundbericht</td>
            <td class="tg-0lax">0.42</td>
            <td class="tg-0lax">3,18€</td>
        </tr>
        <tr>
            <td class="tg-baqh">Summe*</td>
            <td class="tg-0lax"></td>
            <td class="tg-0lax"></td>
            <td class="tg-0lax"></td>
            <td class="tg-0lax"></td>
            <td class="tg-0lax">12,50€</td>
        </tr>
        <tr>
            <td class="tg-baqh">Gezahlter Betrag</td>
            <td class="tg-0lax"></td>
            <td class="tg-0lax"></td>
            <td class="tg-0lax"></td>
            <td class="tg-0lax"></td>
            <td class="tg-0lax">12,50€</td>
        </tr>
    </table>
</div>

<p>*Umsatzsteuerfreie Heilbehandlung</p>
<br>
<p>Vielen Dank für Ihr Vertrauen!</p>
<p>Mit freundlichen Grüßen,</p>
<p>{{$enquire->doctor->title->name}} {{$enquire->doctor->first_name}} {{$enquire->doctor->last_name}}</p>