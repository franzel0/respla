@extends('app')

@section('headercsrf')

@endsection

@section('links')
<link href="{{ asset('/css/day.css') }}" rel="stylesheet">
<script src="{{asset('/js/day.js')}}"></script>

@endsection

@section('css')
<style>
.weekend{
    background-color: #ED7600;
}
.customdate{
    background-color: #CC6600;
}
.holiday{
    background-image: url({{URL::asset('img/holiday.png')}});
    background-size: auto 100%;
    background-repeat: no-repeat;
}

@foreach($styles as $s)

.item{{$s->id}} {
  color: {{($s->present) ? $s->textcolor : '#000000'}};
  background-color: {{($s->present) ? 'transparent': $s->bgcolor}};
  background-size: auto 100%;
}

@endforeach

.today{
    background-color: green;
}

#users-list, #details-list{
    overflow-y: scroll;
}
#users-list-head, #users-list{
    table-layout: fixed;
}
.col1{
    width:30%;
}
.col2{
    width:20%;
}
.col3{
    width:20%;
}
.col4{
    width:30%;
}
.notapproved{
  background-image: url({{ URL::asset('/img/bg.png') }});
}
.present_na{
    background-image: url({{ URL::asset('/img/bg_present_na.png') }});
}

</style>

@endsection

@section('token')

@endsection

@section('content')
<?php
$monate = array(1=>"Januar",
                2=>"Februar",
                3=>"M&auml;rz",
                4=>"April",
                5=>"Mai",
                6=>"Juni",
                7=>"Juli",
                8=>"August",
                9=>"September",
                10=>"Oktober",
                11=>"November",
                12=>"Dezember");

$weekday = array(1 => "Montag",
                 2 => "Dienstag",
                 3 => "Mittwoch",
                 4 => "Donnerstag",
                 5 => "Freitag",
                 6 => "Samstag",
                 7 => "Sonntag");
setlocale(LC_TIME, 'German');
?>
<div id='xtoken' class="hidden">{!! csrf_token() !!}</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 col-sm-offset-0 col-xs-offset-0 padding5">
            <div class="panel panel-default">
                {!!Form::open(['action' => 'EventController@showDay', 'method' => 'post', 'class' => 'navbar-form', 'role' => 'search'])!!}
                <div class="panel-heading">
                    <span class="hidden-sm hidden-xs">Tag</span>
                    <div class="form-group">
                        {!!Form::button('<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>', array('name' => 'back', 'class' => '           btn btn-primary', 'type' => 'submit', 'value' => '1'))!!}

                        <span class="hidden-sm hidden-xs">Datum</span>
                        {!! Form::text('day', $day->formatLocalized('%d.%m.%Y'), ['class' => 'form-control', 'id' => 'day', 'required' => 'required', '         title' =>'Datum eingeben']) !!}

                        {!!Form::button('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', array('class' => 'btn btn-primary', 'type' =>            'submit', 'value' => '1'))!!}

                        {!!Form::button('<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>', array('name' => 'next', 'class' =>            'btn btn-primary', 'type' => 'submit', 'value' => '1'))!!}

                        {!!Form::submit('Heute', array('name' => 'today', 'class' => 'btn btn-primary'))!!}

                        <span class="form-group">
                        <a href="{{action('PdfController@day', ['department' => Auth::user()->department_id, 'date' => $day->toDateString(), 'orderbycol' => $orderbycol])}}" class="btn btn-primary" target="_blank"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></a>
                        </span>
                    </div>
                    @if($errors->first('day'))
                    <span style="color: #A94442;">
                        Bitte ein gültige Datum eingeben
                    </span>
                    @endif
                </div>
                <div  id="panel-body" class="panel-body" sstyle="padding: 7px 0px">
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Mitarbeiter
                                </div>
                                <div class="panel-body table-responsive" >
                                    <div class="scrolly">
                                        <table id="users-list-head" class="table table-bordered table-condensed">
                                            <tbody>
                                                <tr>
                                                    <td class="col1">
                                                        <button class="btn " type="submit" value="Name" style="width:100%; "  name="name">
                                                            <span style="float:left;">
                                                                Name
                                                            </span>
                                                            <span class="glyphicon glyphicon-sort-by-alphabet pull-right hidden-xs @if ($orderbycol == 1) orderbycol  @endif"></span>
                                                        </button>
                                                    </td>
                                                    <td class="col2">
                                                        <button class="btn " type="submit"  value="section" style="width:100%; "  name="section">
                                                            <span style="float:left;">
                                                                Station
                                                            </span>
                                                            <span class="glyphicon glyphicon-sort-by-alphabet pull-right hidden-xs @if ($orderbycol == 2) orderbycol  @endif"></span>
                                                        </button>
                                                    </td>
                                                    <td class="col3">
                                                        <button class="btn " type="submit"  value="entry" style="width:100%; "  name="entry">
                                                            <span style="float:left;">
                                                                Eintrag
                                                            </span>
                                                            <span class="glyphicon glyphicon-sort-by-alphabet pull-right hidden-xs @if ($orderbycol == 3) orderbycol  @endif"></span>
                                                        </button>
                                                    </td>
                                                    <td class="col4">
                                                        {!! Form::submit('Kommentar', ['class' => 'btn', 'style' =>'width:100%; text-align:left;']) !!}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id ="users-list" class="scrolly">
                                        <table class="table table-bordered table-condensed">
                                            <tbody>
                                                @foreach ($events as $e)
                                                <tr data-id="{{$e->uid}}">
                                                    <td class="col1 {{$e->class}} {{$e->class2}} @if($customdate->count()>0) customdate @endif @if($day->isWeekend()) weekend @endif @if($e->entrypresent && $e->class2 == 'notapproved')present_na @endif">
                                                        {{$e->fullname}}
                                                    </td>
                                                    <td class="col2 {{$e->class}} {{$e->class2}} @if($customdate->count()>0) customdate @endif @if($day->isWeekend()) weekend @endif @if($e->entrypresent && $e->class2 == 'notapproved')present_na @endif">
                                                        {{$e->secname}}
                                                    </td>
                                                    <td class="col3 {{$e->class}} {{$e->class2}} @if($customdate->count()>0) customdate @endif @if($day->isWeekend()) weekend @endif entry @if($e->entrypresent && $e->class2 == 'notapproved')present_na @endif" data-approved="{{$e->class2}}">
                                                        {{$e->entryname}}
                                                    </td>
                                                    <td class="col4 {{$e->class}} {{$e->class2}} @if($customdate->count()>0) customdate @endif @if($day->isWeekend()) weekend @endif entry @if($e->entrypresent && $e->class2 == 'notapproved')present_na @endif" data-approved="{{$e->class2}}">
                                                        {{$e->eventscomment}}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 hhidden-xs hhidden-sm">
                            <div class="panel panel-default">
                                <style>
                                td, th {
                                    border: 1px solid darkgrey !important;
                                }
                                </style>

                                <div class="panel-heading">
                                    Details
                                </div>
                                <div class="panel-body">

                                    <div id="details-list" class="well well-sm" style ="padding: 5px; margin-bottom: 0px;">
                                            <div class="col-md-12">
                                                Datum:<br>
                                                {{$day->formatLocalized('%A %e. %B %Y')}}
                                                @if($customdate->count()>0 && $customdate->first()->cusname!="")
                                                , {{$customdate->first()->cusname}}
                                                @endif
                                                @if ($holiday->count()>0)
                                                <hr>
                                                Ferien /sonstige Termine:<br>
                                                @foreach ($holiday as $h)
                                                {{$h->name}}<br>
                                                @endforeach
                                                @endif


                                                <hr>
                                                {!! Form::label('comment', 'Bemerkung') !!}<br>
                                                <div class="form-group @if($errors->first('comment')) has-error @endif">
                                                    {!! Form::hidden('commentid',  ($comment->count()>0) ? $comment->first()->id : '' )!!}
                                                    {!! Form::textarea('depcomment', ($comment->count()>0) ? $comment->first()->text : '', ['class' => 'form-  control', 'rows' => '2',  'style' => 'width:100%', 'name' => 'comment']) !!}
                                                </div>
                                                {!! Form::submit("Speichern", ['class' => 'btn btn-success', 'name' => 'save_comment']) !!}
                                                <br>

                                            <hr>
                                            </div>
                                        <!--<div class="row">-->
                                            <div class="col-md-6">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td>
                                                            Anwesend
                                                        </td>
                                                        <td>
                                                            @if($customdate->count()==0 && !$day->isWeekend())
                                                                {{$events_present->first()['p1']}}
                                                            @else
                                                                0
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @foreach ($events_summary as $e)
                                                    @if ($e->entrypresent == 1 && $e->entrycount > 0)
                                                    <tr>
                                                        <td>
                                                            {{$e->entryname}}
                                                        </td>
                                                        <td>
                                                            {{$e->entrycount}}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @endforeach
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table  class="table table-bordered">
                                                    <tr>
                                                        <td>
                                                            Abwesend
                                                        </td>
                                                        <td>
                                                            {{$events_present->last()['p']}}
                                                        </td>
                                                    </tr>
                                                    @foreach ($events_summary as $e)
                                                    @if ($e->entrypresent == 0 && $e->entrycount > 0)
                                                    <tr>
                                                        <td>
                                                            {{$e->entryname}}
                                                        </td>
                                                        <td>
                                                            {{$e->entrycount}}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @endforeach
                                                </table>
                                            </div>
                                        <!--</div>-->
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                {!!Form::close() !!}
            </div>
        </div>
    </div>
</div>
<?php
//print_r($events_summary);
?>

@endsection


<!--
Dialog f�r das Eingeben der Dienste
-->
@section('modal')

<!--include dialogs partials-->
@include('partials.dialogs')

@endsection
