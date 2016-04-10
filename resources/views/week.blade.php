@extends('app')

@section('headercsrf')

<meta name="csrf-token" content="{{ csrf_token() }}" />

@endsection

@section('links')
<link href="{{ asset('/css/week.css') }}" rel="stylesheet">
<script src="{{asset('/js/week.js')}}"></script>
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
  background-image: url({{($s->approved) ? '' : URL::asset('/img/bg.png') }});
}

.today{
    background-color: green;
}

.form-control{
    width:auto;
}
</style>

@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 padding5">
            <div class="panel panel-default">
                <div class="panel-heading">
                	<nav class="navbar navbar-default">
                		<div class="container-fluid">
    						<!-- Brand and toggle get grouped for better mobile display -->
    						<div class="navbar-header">
    						  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2" aria-expanded="	false"	>
    						    <span class="sr-only">Toggle navigation</span>
    						    <span class="icon-bar"></span>
    						    <span class="icon-bar"></span>
    						    <span class="icon-bar"></span>
    						  </button>
    						  <a class="navbar-brand" href="#">Woche</a>
    						</div>
                		
                			<!-- Collect the nav links, forms, and other content for toggling -->
    						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                			
                				{!!Form::open(['action' => 'EventController@showWeek', 'method' => 'post', 'class' => 'navbar-form navbar-left', 'role' => 'search', 'style' => 'display: inline', 'id' => 'week-form'])!!}
                					
                					{!!Form::button('<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>', array('name' => 'back', 'class' => 'btn btn-primary', 'type' => 'submit', 'value' => '1'))!!}
                    
                        			{!!Form::button('<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>', array('name' => 'next', 'class' =>            'btn btn-primary', 'type' => 'submit', 'value' => '1'))!!}
                    
                        			{!!Form::submit('Heute', array('name' => 'today', 'class' => 'btn btn-primary'))!!}
      						
                					<div class="input-group">
                						<span class="input-group-addon " id="sizing-addon1">Datum</span>
                						{!! Form::text('day', $day->formatLocalized('%d.%m.%Y'), ['class' => 'form-control date-width', 'id' => 'day', 'required' => 'required', 'title' =>'Datum eingeben']) !!}
                						<span class="input-group-btn">
                						{!!Form::button('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', array('class' => 'btn btn-primary', 'type' => 'submit', 'value' => 'date'))!!}                						
      									</span>
                					</div>
			
                					<div class="input-group">
                						<span class="input-group-addon " id="sizing-addon1">Woche</span>
                						{!! Form::text('weekOfYear', $weekOfYear, ['name' => 'weekofyear', 'class' => 'form-control week-width', 'id' => 'weekOfYear', 'required' => 'required', 'title' => 'Kalenderwoche eingeben']) !!}
                						<span class="input-group-btn">
                						{!! Form::submit('Go!', ['name' => 'setweekofyear', 'class' => 'btn btn-primary', 'value' => 'weekofyear']) !!}
      									</span>
                					</div>
                		                   
                				{!!Form::close()!!}
                			
                			</div>	
                		</div>
                	</nav>
                </div>
            	<div ID="week-panel" class="panel-body">
            		<div class="row well well-sm">
                        <div class="col-md-12 col-sm-12 col-xs-12 week-overflow-header">
                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <strong>Datum</strong>
                                </div>
                                @for($day = clone $monday; $day <= $friday ; $day->addDay())
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <table class="table table-bordered table-data">
                                        <tr>
                                            <th class=" @if($day->isweekend()) weekend @endif @if(in_array($day->toDateString(), $customdates)) customdate @endif">
                                            {{$day->formatLocalized('%a %e. %b')}}
                                            </th>
                                        </tr>
                                    </table>
                                </div>
                                @endfor
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 week-overflow-header">
                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <strong>Kommentare</strong>
                                </div>
                                @for($day = clone $monday; $day <= $friday ; $day->addDay())
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <table class="table table-bordered table-data">
                                        <tr>
                                            <th title="@if($c=$comments->get($day->toDateString())) {{$c->text}} @endif">
                                                <div class="input-group">                                                        
                                                    <input type="text" value="@if($c=$comments->get($day->toDateString())) {{$c->text}} @endif" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default btn-comment" value="@if($c=$comments->get($day->toDateString())) {{$c->id}} @else 0 @endif" type="button" data-date="{{$day->toDateString()}}">K!</button>
                                                    </span>
                                                </div>
                                            </th>
                                        </tr>
                                    </table>
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="row well well-sm">
                        <h3>Anwesend</h3>
                        <div id=" present" class="col-md-12 col-sm-12 col-xs-12 week-overflow">
                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                </div>
                                <?php
                                $count_array = array();
                                ?>
                                @for($day = clone $monday; $day <= $friday ; $day->addDay())
                                <?php
                                $count=0
                                ?>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <table class="table table-bordered table-data ">
                                        @foreach ($events[$day->toDateString()] as $e)
                                            @if($e->entrypresent==1  || ($e->entrypresent==2 && ($day->isweekday() && !in_array($day->toDateString(), $customdates))))
                                            <tr>
                                                <td class="{{$e->class}} {{$e->class2}} entry" title="{{$e->entryname}}, {{$e->eventscomment}}" data-id="{{$e->uid}}" data-approved="{{$e->class2}}" data-date="{{$day->toDateString()}}">{{$e->fullname}}</td>
                                            </tr>
                                            <?php
                                            $count++;
                                            ?>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                                <?php
                                $count_array[]= $count;
                                ?>
                                @endfor
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 week-overflow-header">
                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <strong>Anzahl</strong>
                                </div>
                                @foreach($count_array as $c)
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <table class="table table-bordered table-data">
                                        <tr>
                                            <th>
                                            {{$c}}
                                            </th>
                                        </tr>
                                    </table>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div> 
                    <div class="row well well-sm">
                        <h3>Abwesend</h3>
                        <div class="col-md-12 col-sm-12 col-xs-12  week-overflow">
                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    
                                </div>
                                <?php
                                $count_array = array();
                                ?>
                                @for($day = clone $monday; $day <= $friday ; $day->addDay())
                                <?php
                                $count=0
                                ?>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <table class="table table-bordered table-data">
                                        @foreach ($events[$day->toDateString()] as $e)
                                            @if($e->entrypresent==0  || ($e->entrypresent==2 && ($day->isweekend() || in_array($day->toDateString(), $customdates)))) 
                                            <tr>
                                                <td class="{{$e['class']}} {{$e['class2']}} entry" title="{{$e->entryname}}, {{$e->eventscomment}}" data-id="{{$e->uid}}" data-approved="{{$e->class2}}" data-date="{{$day->toDateString()}}">{{$e->fullname}}</td>
                                            </tr>
                                            <?php
                                            $count++;
                                            ?>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                                <?php
                                $count_array[]= $count;
                                ?>
                                @endfor
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 week-overflow-header">
                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <strong>Anzahl</strong>
                                </div>
                                @foreach($count_array as $c)
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <table class="table table-bordered table-data">
                                        <tr>
                                            <th>
                                            {{$c}}
                                            </th>
                                        </tr>
                                    </table>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
            	</div>
            </div>
        </div>
    </div>
</div>                                                          
@endsection

<!--
Dialog f�r das Eingeben der Dienste
-->
@section('modal')

<!--include dialogs partials-->
@include('partials.dialogs')

@endsection