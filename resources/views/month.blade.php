@extends('app')

@section('headercsrf')

@endsection

@section('links')
<link href="{{ asset('/css/month.css') }}" rel="stylesheet">
<script src="{{asset('/js/month.js')}}"></script>

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

.notapproved{
    background-image: url({{ URL::asset('/img/bg.png') }});
}

.today{
    background-color: green;
}

.form-control{
    width:auto;
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

$weekday = array(0 => "Montag",
                 1 => "Dienstag",
                 2 => "Mittwoch",
                 3 => "Donnerstag",
                 4 => "Freitag",
                 5 => "Samstag",
                 6 => "Sonntag");
?>
<div id='xtoken' class="hidden">{!! csrf_token() !!}</div>
<div class="container-fluid">
    <div class="row" >
        <div class="col-md-12 padding5">
            <div  class="panel panel-default">
                <div class="panel-heading panel-heading-month">
                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#month-navbar-collapse"  aria-    expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="#">Übersicht</a>
                            </div>

                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="collapse navbar-collapse" id="month-navbar-collapse">
                                {!!Form::open(['action' => 'EventController@showMonth', 'method' => 'post', 'class' => 'navbar-form navbar-left form-inline', 'role' => 'search'])!!}
                                    {!! Form::hidden('day', $startofmonth->toDateString()) !!}
                                    <div class="form-group">
                                        {!!Form::button('<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>', array('name' => 'back', 'class' => 'btn btn-primary', 'type' => 'submit', 'value' => '1'))!!}
                                    </div>
                                    <div class="form-group">
                                        {!!Form::label('month', 'Monat', array('class' => 'hidden-xs'))!!}
                                        {!!Form::select('month', $monate, $startofmonth->month, array('class' => 'form-control'))!!}
                                    </div>
                                    <div class="form-group">
                                        {!!Form::label('year', 'Jahr', array('class' => 'hidden-xs'))!!}
                                        {!!Form::text('year', $startofmonth->year, array('size' => '4', 'placeholder' => 'Jahr', 'class' => 'form-control'))    !!}
                                    </div>
                                    <div class="form-group">
                                        {!!Form::button('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', array('class' => 'btn btn-primary', 'type' => 'submit', 'value' => '1'))!!}
                                    </div>
                                    <div class="form-group">
                                        {!!Form::submit('JETZT', array('name' => 'this', 'class' => 'btn btn-primary'))!!}
                                    </div>
                                    <div class="form-group">
                                        {!!Form::button('<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>', array('name' => 'next', 'class' => 'btn btn-primary', 'type' => 'submit', 'value' => '1'))!!}
                                    </div>
                                    <div class="form-group">
                                        {!!Form::label('position', 'Positon', array('class' => 'hidden-xs'))!!}
                                        {!!Form::select('position', $positionList, $position, array('class' => 'form-control'))!!}
                                    </div>
                                    <div class="form-group">
                                        <a href="{{action('PdfController@month', ['department' => Auth::user()->department_id, 'startofmonth' => $startofmonth->toDateString(), 'position' => $position])}}" class="btn btn-primary" target="_blank"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></a>
                                    </div>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#insert_events">
                                            <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                {!!Form::close() !!}
                                <div id="errors" ></div>
                            </div><!-- /.navbar-collapse -->
                        </div><!-- /.container-fluid -->
                    </nav>
                </div>
                <div id="panel-body" class="panel-body panel_month" style="padding: 0px 15px">
                    <div class="row"  >
                        <div id="left" class="col-md-1 col-xs-2 padding0 overflow">
                            <div class="left-top">
                                <table class="table table-bordered padding0">
                                    <tr>
                                       <th>
                                           Datum
                                       </th>
                                    </tr>
                                </table>
                            </div>
                            <div id="left-bottom">
                                <table class="table table-bordered padding0">
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                            {{$user->firstname}} {{$user->lastname}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <div id="right" class="col-md-11 col-xs-10 padding0 overflow">
                            <div id="right-top" >
                                <table class="table table-bordered">
                                    <tr>
                                        @foreach($daysinmonth as $day)
                                        <th class="{{$day['class']}}" title="{{$day['title']}}">{!!$day['dayofmonth']!!}</th>
                                        @endforeach
                                    </tr>
                                </table>
                            </div>
                            <div id="right-bottom">
                                <table id="table-events" class="table table-bordered">
                                    @foreach( $events as $e )
                                        @if ( $e->monthday == 1 )
                                            <tr data-id={{$e->userid}}>
                                        @endif

                                            <td class="{{$e->class1}} {{$e->class2}} {{$e->class3}} {{$e->class4}}"  data-date="{{$e->d}}" data-event_id="{{$e->eventid}}" data-approved="{{$e->class4}}" title="Datum: {{$weekday[$e->weekday]}} {{$e->cusname}}&#10;Eintrag: {{$e->entryname}}&#010;Bemerkung: {{$e->  comment}}">
                                                {{$e->shorttext}} @if($e->comment <> '')<br><span class='badge'>K</span> @endif
                                            </td>

                                        @if ( $e->monthday == $numberdays )
                                            </tr>
                                        @endif

                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('modal')
<!--
Dialog f�r das Eingeben der Dienste
-->
<style>
button[class^="item"] {
    border: 1px solid grey;
    margin: 5px;
}
</style>

<!--include dialogs partials-->
@include('partials.dialogs')

@endsection
