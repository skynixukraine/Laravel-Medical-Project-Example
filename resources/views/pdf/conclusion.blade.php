<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
    <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <TITLE>Diagnosis</TITLE>
    <META name="generator" content="BCL easyConverter SDK 5.0.210">
    <STYLE type="text/css">

        body {margin-top: 0px;margin-left: 0px;}

        #page_1 {position:relative; overflow: hidden;margin: 20px 0px 0px 0px;padding: 0px;border: none;}

        #page_1 #p1dimg1 {position:absolute;top:0px;left:0px;z-index:-1;width:491px;height:373px;}
        #page_1 #p1dimg1 #p1img1 {width:566px;height:124px; margin-left: 20px;}
        #page_1 #p1dimg1 #p1img1-doctor {margin-top: 50px; margin-left: 20px;}

        .photo-answer {margin-top: 20px}


        #page_2 {position:relative; overflow: hidden;margin: 0px;padding: 0px;border: none;}

        #page_2 #p2dimg1 {position:absolute;top:0px;left:90px;z-index:-1;width:491px;height:361px;}
        #page_2 #p2dimg1 #p2img1 {width:566px;height:124px;}

        .dclr {clear:both;float:none;height:1px;margin:0px;padding:0px;overflow:hidden;}

        .ft0{font-size: 28px;color: #407cde;line-height: 24px;font-weight: 600;}
        .ft1{font-size: 18px;color: #061848;line-height: 18px;}
        .ft2{font-size: 18px;color: #061848;line-height: 17px;}
        .ft3{font-size: 19px;line-height: 1.5; word-wrap: break-word;}
        .ft4{font-size: 19px;color: #222222;line-height: 17px;}
        .ft5{font-size: 19px;color: #222222;line-height: 21px;}
        .ft6{font-size: bold 15px;color: #6b3424;line-height: 28px;}
        .ft7{font-size: 15px;color: #6b3424;line-height: 17px;}
        .ft8{font-size: 14px;line-height: 22px;font-weight: 600;}
        .grey-add{color:grey}
        .margin-bt-add{margin-bottom:35px;position: relative;top: -15px;width: 50%;}

        .p0{text-align: left;padding-left: 320px;margin-top: 220px;margin-bottom: 0px;}
        .p1{text-align: left;padding-left: 320px;margin-top: 21px;margin-bottom: 0px;font-weight: bold;}
        .p1 a{text-decoration: none;}
        .p2{text-align: left;padding-left: 320px;margin-top: 22px;margin-bottom: 0px;}
        .p3{text-align: left;padding-left: 320px;margin-top: 14px;margin-bottom: 0px;}
        .p4{text-align: left;padding-left: 20px;margin-top: 195px;margin-bottom: 0px;}
        .p5{text-align: left;padding-left: 20px;margin-top: 22px;margin-bottom: 15px;}
        .p6{text-align: left;padding-left: 20px;margin-top: 9px;margin-bottom: 0px;}
        .p7{text-align: left;padding-left: 20px;margin-top: 9px;margin-bottom: 0px;}
        .p8{text-align: left;padding-left: 20px;margin-top: 9px;margin-bottom: 0px;}
        .p9{text-align: left;padding-left: 20px;padding-right: 453px;margin-top: 9px;margin-bottom: 0px;width:335px;}
        .p10{text-align: left;padding-left: 20px;margin-top: 9px;margin-bottom: 0px;}
        .p11{text-align: left;padding-left: 0px;margin-top: 11px;margin-bottom: 0px;}
        .p12{text-align: left;padding-left: 20px;margin-top: 23px;margin-bottom: 0px;}
        .p13{text-align: left;margin-top: 23px;margin-bottom: 0px;width:100%}
        .with-photo-answer{width:80%;}

    </STYLE>
</HEAD>

<BODY>
<DIV id="page_1">
    <DIV id="p1dimg1">
        <IMG width="125" height="80" src="{{ 'data:' . 'image/png' . ';base64,' . $logo }}" id="p1img1">
        <IMG width="280" height="300" src="{{ 'data:' . 'image/png' . ';base64,' . $doctorPhoto }}" id="p1img1-doctor">
    </DIV>

    <DIV class="dclr"></DIV>
    <P class="p0 ft0">Ihr gew??hlter Hautarzt:</P>
    <P class="p1 ft1">{{$enquire->doctor->title->name}} <A href="{{config('app.url')}}/hautarzt/{{$enquire->doctor->first_name}}_{{$enquire->doctor->last_name}}">{{$enquire->doctor->first_name}} {{$enquire->doctor->last_name}}</A></P>
    <P class="p2 ft2">{{$enquire->doctor->location->address}}</P>
    <P class="p3 ft2">{{$enquire->doctor->location->postal_code}} {{$enquire->doctor->location->city}}</P>
    <P class="p4 ft0">Professionelle Einsch??tzung Ihres Hautarztes:</P>
    <P class="p5 ft3">{!! $conclusion !!}</P>
</DIV>

<style>
    .page-break {
        page-break-after: always;
    }
</style>
<div class="page-break"></div>

<P class="p11 ft0">Ihre Angaben und Bilder:</P>

<DIV id="page_1">
    <DIV id="p2dimg1">
        <hr class="top-hr">
        @foreach($enquire->answers as $answer)

            @if ($answer->value == null && $answer->next_message_id == null)
                @continue
            @endif

            @if ($answer->message->type == \App\Models\Message::TYPE_SELECT)
                <div class="margin-bt-add width-answer">
                    <p class="p13 ft8 grey-add">{!! $answer->message->content !!}</p>
                    <p class="p13 ft8 grey-add answer">{{$answer->prepareValue()}}</p>
                </div>
                <hr>
            @endif

            @if ($answer->message->type == \App\Models\Message::TYPE_RADIO)
                <div class="margin-bt-add with-answer">
                    <p class="p13 ft8 grey-add">{!! $answer->message->content !!}</p>
                    <p class="p13 ft8 grey-add answer">{{$answer->prepareValue()}}</p>
                </div>
                <hr>
            @endif

            @if ($answer->message->type == \App\Models\Message::TYPE_TEXT)
                <div class="margin-bt-add with-answer">
                    <p class="p13 ft8 grey-add">{!! $answer->message->content !!}</p>
                    <p class="p13 ft8 grey-add answer">{{$answer->prepareValue()}}</p>
                </div>
                <hr>
            @endif

            @if ($answer->message->type == \App\Models\Message::TYPE_IMAGE)
                <div class="margin-bt-add with-answer">
                    <p class="p13 ft8 grey-add with-photo-answer">{!! $answer->message->content !!}</p>
                    <div class="photo-answer">
                        <IMG width="150" height="150" src="{{ $answer->prepareValue() }}">
                    </div>
                </div>
                <hr>
            @endif

            @if ($answer->message->type == \App\Models\Message::TYPE_BODY_SELECT)
                <div class="margin-bt-add with-answer">
                    <p class="p13 ft8 grey-add with-photo-answer">{!! $answer->message->content !!}</p>
                    <div class="photo-answer">
                        <IMG width="75" height="150"  src="{{ 'data:' . 'image/png' . ';base64,' . \App\Facades\Svg::setColorByIds('body-front.svg', json_decode($answer->prepareValue())) }}">
                        <IMG width="75" height="150" src="{{ 'data:' . 'image/png' . ';base64,' . \App\Facades\Svg::setColorByIds('body-back.svg', json_decode($answer->prepareValue())) }}">
                    </div>
                </div>
                <hr>
            @endif
        @endforeach

    </DIV>
</DIV>
</BODY>
</HTML>