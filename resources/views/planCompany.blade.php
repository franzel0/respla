@extends('app')

@section('headercsrf')
<meta name="csrf-token" content="{{ csrf_token() }}" />

@endsection

@section('links')
<link href="{{ asset('/css/plan.css') }}" rel="stylesheet">
<link href="{{ asset('/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
<script src="{{asset('/js/planDepartment.js')}}"></script>
<script src="{{asset('/js/bootstrap-toggle.min.js')}}"></script>

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
                                {!! Form::open(['action' => 'PlanController@indexCompany', 'method' => 'post', 'class' => 'navbar-form navbar-left panel-body-navbar-form', 'role' => 'search']) !!}
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

                                    <div class="input-group ">
                                        {!! Form::submit("Los", ['class' => 'btn btn-success']) !!}
                                    </div>

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </nav>
                </div>
                <!-- include plan partial -->
                @include('partials/plan')
            </div>
        </div>
    </div>
</div>

@endsection
