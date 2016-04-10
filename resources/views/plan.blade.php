@extends('app')

@section('headercsrf')
<meta name="csrf-token" content="{{ csrf_token() }}" />

@endsection

@section('links')
<link href="{{ asset('/css/plan.css') }}" rel="stylesheet">
<script src="{{asset('/js/plan.js')}}"></script>
<script src="{{asset('/js/jquery.slimscroll.min.js')}}"></script>

@endsection

@section('css')
<style type="text/css">
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
    
</style>

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
$weekday = array(2 => "Montag",
                 3 => "Dienstag",
                 4 => "Mittwoch",
                 5 => "Donnerstag",
                 6 => "Freitag",
                 7 => "Samstag",
                 1 => "Sonntag",
                 8 => "Feiertag");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 col-sm-offset-0 col-xs-offset-0 padding5">
            <div class="panel panel-default">
                
                <div class="panel-heading">
                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-form-details"    aria-expanded="  false"  >
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand hidden-sm hidden-md hidden-lg" href="#">Plan</a>
                            </div>
                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="collapse navbar-collapse" id="navbar-form-details">
                                {!! Form::open(['action' => 'PlanController@index', 'method' => 'post', 'class' => 'navbar-form navbar-left panel-body-navbar-form', 'role' => 'search']) !!}
                                <span class="navbar-brand visible-sm visible-md visible-lg pull-left title-form">Plan</span>
                                
                                    <div class="input-group" >
                                        {!! Form::label('entry', 'Planung für...', array('class' => 'input-group-addon', 'style' =>"width:100px")) !!}
                                        {!! Form::select('entry', $entries->lists('name', 'id'), $entry, array('class' => 'form-control select select2')) !!}
                                    </div> 
            
                                    <div class="input-group">
                                        {!!Form::label('month', 'Monat', array('class' => 'input-group-addon'))!!}
                                        {!!Form::select('month', $monate, $startofmonth->month, array('class' => 'form-control select select2'))!!}
                                    </div>
                                    <div class="input-group">
                                        {!!Form::label('year', 'Jahr', array('class' => 'input-group-addon'))!!}
                                        {!!Form::text('year', $startofmonth->year, array('size' => '4', 'placeholder' => 'Jahr', 'class' => 'form-control'))!!}
                                    </div>
                                    <div class="input-group">
                                        {!!Form::label('position', 'Position', array('class' => 'input-group-addon'))!!}
                                        {!!Form::select('position', $positionList, $position, array('class' => 'form-control  select2'))!!}
                                    </div>
            
                                    <div class="input-group ">
                                        {!! Form::submit("Los", ['class' => 'btn btn-success']) !!}
                                    </div>
                                
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </nav>
                </div>
                <div  id="panel-body" class="panel-body plan-panel-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-4">
                            <div class="row">
                                <div class="col-md-12 plan-title-overflow">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Mitarbeiter</th>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-12 plan-overflow">
                                    <table id="users" class="table table-bordered">
                                        @foreach($users as $u)
                                        <tr>
                                            <td>
                                                <span class="drag" data-user="{{$u->id}}" data-event="0" data-short="{{mb_substr($u->firstname,0,2, "UTF-8")}}{{mb_substr($u->lastname,0,2, "UTF-8")}}">{{$u->lastname}}, {{$u->firstname}}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-8">
                            <div class="row">
                                <div class="col-md-12 plan-title-overflow">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th width="30%" >Datum</th>
                                                    <th>Mitarbeiter</th>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <table class="table table-bordered userdatatitle">
                                                <tr>
                                                    <th width="30%">Termine</th>
                                                    <th></th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 plan-overflow">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <table id="events" class="table table-bordered">
                                                @for($day = clone $startofmonth; $day <= $endofmonth; $day->addDay())
                                                <tr class="@if($day->isweekend()) weekend @endif @if(in_array($day->toDateString(), $customdates)) customdate @endif"  data-date="{{$day->toDateString()}}">
                                                    <td width="30%">{{$day->formatLocalized('%a %e. %b')}}</td>
                                                    <td class="drop">
                                                        @foreach($events->filter(function($item) use ($day, $entry) { if($item->date == $day->toDateString() && $item->entry_id == $entry) return true;}) as $ev)
                                                            <span class="drag" data-user="{{$ev->user_id}}" data-event="{{$ev->eventid}}">{{mb_substr($ev->firstname,0 , 2, "UTF-8")}}{{mb_substr($ev->lastname, 0, 2, "UTF-8")}} <button class="btn btn-sm btn-info">x</button></span>
                                                        @endforeach
                                                    </td>
                                                @endfor
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            @foreach($users as $u)
                                            <table id="user{{$u->id}}" class="table table-bordered userdata">
                                                <tbody>
                                                    @for($day = clone $startofmonth; $day <= $endofmonth; $day->addDay())
                                                    <tr>
                                                        @foreach( $events->filter(function($item) use ($day, $u) { if($item->date == $day->toDateString() && $item->user_id == $u->id) return true;}) as $ev)
                                                        <td class="item{{$ev->entry_id}}">
                                                            {{$ev->entry->name}}
                                                        </td>
                                                        @endforeach
                                                    </tr>
                                                    @endfor
                                                <tbody>
                                            </table>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 hidden-sm hidden-xs div-stats">
                            @foreach($users as $u)
                            <table id="stats{{$u->id}}" class="table table-bordered table-striped statsdata">
                                <tbody>
                                    <tr>
                                        <th colspan=2>Statistik</th>
                                    <tr>
                                        <th>Tag</th>
                                        <th>Anzahl</th>
                                    </tr>
                                    @foreach($stats->filter(function($item) use ($u) { if($item->userid == $u->id) return true;}) as $s)
                                    <tr>
                                        <td>
                                            {{$weekday[$s->weekday]}}
                                        </td>
                                        <td>
                                            {{$s->numberdays}}
                                        </td>
                                    </tr>
                                    @endforeach
                                <tbody>
                            </table>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>   
</div>

@endsection
