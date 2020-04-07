<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
    <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <TITLE>Diagnosis</TITLE>
    <META name="generator" content="BCL easyConverter SDK 5.0.210">
    <STYLE type="text/css">

        body {margin-top: 0px;margin-left: 0px;}

        #page_1 {position:relative; overflow: hidden;margin: 38px 0px 164px 56px;padding: 0px;border: none;}

        #page_1 #p1dimg1 {position:absolute;top:0px;left:0px;z-index:-1;width:491px;height:373px;}
        #page_1 #p1dimg1 #p1img1 {width:566px;height:124px;}
        #page_1 #p1dimg1 #p1img1-doctor {margin-top: 50px; margin-left: 20px;}




        #page_2 {position:relative; overflow: hidden;margin: 92px 0px 0px 0px;padding: 0px;border: none;}

        #page_2 #p2dimg1 {position:absolute;top:0px;left:90px;z-index:-1;width:80%;height:361px;}
        #page_2 #p2dimg1 #p2img1 {width:642px;height:361px;}
        #page_2 #p2dimg1 .top-hr {
            height: 5px;
            background: #d9d9d9;
            border: none;
        }

        #page_2 #p2dimg1 .with-answer{
            display: flex;
            width: 100%;
        }
        #page_2 #p2dimg1 .photo-answer {
            display: flex;
            position: relative;
            top: 10px;
        }

        .dclr {clear:both;float:none;height:1px;margin:0px;padding:0px;overflow:hidden;}

        .ft0{font-size: 28px;color: #407cde;line-height: 24px;font-weight: 600;}
        .ft1{font-size: 18px;color: #061848;line-height: 18px;}
        .ft2{font-size: 18px;color: #061848;line-height: 17px;}
        .ft3{font-size: 19px;line-height: 17px; word-wrap: break-word;}
        .ft4{font-size: 19px;color: #222222;line-height: 17px;}
        .ft5{font-size: 19px;color: #222222;line-height: 21px;}
        .ft6{font-size: bold 15px;color: #6b3424;line-height: 28px;}
        .ft7{font-size: 15px;color: #6b3424;line-height: 17px;}
        .ft8{font-size: 14px;line-height: 22px;font-weight: 600;}
        .grey-add{color:grey}
        .margin-bt-add{margin-bottom:35px;position: relative;top: -15px;width: 50%;}

        .p0{text-align: left;padding-left: 320px;margin-top: 220px;margin-bottom: 0px;}
        .p1{text-align: left;padding-left: 320px;margin-top: 21px;margin-bottom: 0px;}
        .p2{text-align: left;padding-left: 320px;margin-top: 22px;margin-bottom: 0px;}
        .p3{text-align: left;padding-left: 320px;margin-top: 14px;margin-bottom: 0px;}
        .p4{text-align: left;padding-left: 20px;margin-top: 195px;margin-bottom: 0px;}
        .p5{text-align: left;padding-left: 20px;margin-top: 22px;margin-bottom: 15px;}
        .p6{text-align: left;padding-left: 20px;margin-top: 9px;margin-bottom: 0px;}
        .p7{text-align: left;padding-left: 20px;margin-top: 9px;margin-bottom: 0px;}
        .p8{text-align: left;padding-left: 20px;margin-top: 9px;margin-bottom: 0px;}
        .p9{text-align: left;padding-left: 20px;padding-right: 453px;margin-top: 9px;margin-bottom: 0px;width:335px;}
        .p10{text-align: left;padding-left: 20px;margin-top: 9px;margin-bottom: 0px;}
        .p11{text-align: left;padding-left: 20px;margin-top: 140px;margin-bottom: 0px;}
        .p12{text-align: left;padding-left: 20px;margin-top: 23px;margin-bottom: 0px;}
        .p13{text-align: left;margin-top: 23px;margin-bottom: 0px;width:100%}
        .with-photo-answer{width:50%;}

    </STYLE>
</HEAD>

<BODY>
<DIV id="page_1">
    <DIV id="p1dimg1">
        <IMG width="155" height="80" src="{{ 'data:' . 'image/png' . ';base64,' . $logo }}" id="p1img1">
        <IMG src="./doctor.png" id="p1img1-doctor">
    </DIV>

    <DIV class="dclr"></DIV>
    <P class="p0 ft0">Ihr gewählter Hautarzt:</P>
    <P class="p1 ft1">{{$enquire->doctor->title->name}} <A href="{{config('app.url')}}/hautarzt/{{$enquire->doctor->first_name}}_{{$enquire->doctor->last_name}}">{{$enquire->doctor->first_name}} {{$enquire->doctor->last_name}}</A></P>
    <P class="p2 ft2">{{$enquire->doctor->location->address}}</P>
    <P class="p3 ft2">{{$enquire->doctor->location->postal_code}} {{$enquire->doctor->location->city}}</P>
    <P class="p4 ft0">Professionelle Einschätzung Ihres Hautarztes:</P>
    <P class="p5 ft3">{{$enquire->conclusion}}</P>
</DIV>

<style>
    .page-break {
        page-break-after: always;
    }
</style>
<div class="page-break"></div>

<DIV>
    <DIV id="p2dimg1">
        @foreach($enquire->answers as $answer)

            @if ($answer->message->type == \App\Models\Message::TYPE_SELECT)
                <div class="margin-bt-add width-answer">
                    <p class="p13 ft8 grey-add">{{$answer->message->questioner}}</p>
                    <p class="p13 ft8 grey-add answer">{{$answer->value}}</p>
                </div>
                <hr>
            @endif

            @if ($answer->message->type == \App\Models\Message::TYPE_TEXT)
                <div class="margin-bt-add with-answer">
                    <p class="p13 ft8 grey-add">{{$answer->message->questioner}}</p>
                    <p class="p13 ft8 grey-add answer">{{$answer->value}}</p>
                </div>
                <hr>
            @endif

            @if ($answer->message->type == \App\Models\Message::TYPE_IMAGE)
                <div class="margin-bt-add with-answer">
                    <p class="p13 ft8 grey-add with-photo-answer">{{$answer->message->questioner}}</p>
                    <div class="photo-answer">
                        <IMG width="100" height="100" src="{{ 'data:' . 'image/png' . ';base64,' . base64_encode(file_get_contents(config('app.url') . $answer->value)) }}">
                    </div>
                </div>
                <hr>
            @endif

            @if ($answer->message->type == \App\Models\Message::TYPE_BODY_SELECT)
                <div class="margin-bt-add with-answer">
                    <p class="p13 ft8 grey-add with-photo-answer">{{$answer->message->questioner}}</p>
                    <div>
                        <IMG src="{{ 'data:' . 'image/png' . ';base64,' . $frontBody }}">
                    </div>
                </div>
                <hr>
            @endif
        @endforeach

    </DIV>
</DIV>
</BODY>
</HTML>