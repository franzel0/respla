@extends('app')

@section('links')
<link href="{{ asset('/css/stats.css') }}" rel="stylesheet">
<!--<script src="{{asset('/js/plan.js')}}"></script>
<script src="{{asset('/js/jquery.slimscroll.min.js')}}"></script>-->

@endsection

@section('scripts')
<script>
$(document).ready(function(){

function el_height(){
    //inactivated as drag & drop does not work anymore with this function activated
    var h = $( window ).height();
    try {
        var p = $(".panel-body").offset();
        var height = h - p.top - 20 ;

        $(".stats-overflow").css("height", height);

    }
    catch(err){
        alert(err.message);
    }
}

/*
* Change height for element
*/
el_height();

/*
* Recompute the height fo divs when changing the size of the window
*/
$( window ).resize(function(){
  el_height();
    });

})

</script>

@endsection

@section('content')

<?php
$months = array(1=>"Januar",
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
		<div class="col-md-10 col-md-offset-1">
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
                                <a class="navbar-brand hidden-sm hidden-md hidden-lg" href="#">Statistik für {{Auth::user()->firstname}} {{Auth::user()->lastname}}</a>
                            </div>
                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="collapse navbar-collapse" id="navbar-form-details">
								{!!Form::open(['action' => 'StatsController@index', 'method' => 'post', 'class' => 'navbar-form ', 'role' => 'search'])!!}
								<span class="navbar-brand visible-sm visible-md visible-lg pull-left title-form">Statistik für {{Auth::user()->firstname}} {{Auth::user()->lastname}}</span>
                    			    <div class="form-group">
                    			        {!!Form::button('<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>', array('name' => 'back', 'class' => 'btn btn-primary', 'type' => 'submit', 'value' => '1'))!!}
                    			    </div>
                    			    <div class="form-group @if($errors->first('month')) has-error @endif">
                    			        {!!Form::label('month', 'Monat', array('class' => 'hidden-xs'))!!}
										{!! Form::selectMonth('month', $startofmonth->month, array('class' => 'form-control'))!!}
                    			        <small class="text-danger">{{ $errors->first('month') }}</small>
                    			    </div>
                    			    <div class="form-group @if($errors->first('year')) has-error @endif">
                    			        {!!Form::label('year', 'Jahr', array('class' => 'hidden-xs'))!!}
										{!!Form::text('year', $startofmonth->year, array('size' => '4', 'placeholder' => 'Jahr', 'class' => 'form-control'))    !!}
                    			    	<small class="text-danger">{{ $errors->first('year') }}</small>
                    			    </div>
                    			    <div class="form-group">
                    			        {!!Form::button('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', array('class' => 'btn btn-primary', 'type' => 'submit'			, 'value' => '1'))!!}
                    			    </div>
                    			    <div class="form-group">
                    			        {!!Form::submit('JETZT', array('name' => 'this', 'class' => 'btn btn-primary'))!!}
                    			    </div>
                    			    <div class="form-group">
                    			        {!!Form::button('<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>', array('name' => 'next', 'class' => 'btn btn-primary', 'type' => 'submit', 'value' => '1'))!!}
                    			    </div>
                    			{!!Form::close()!!}

                    		</div>
                    	</div>
                    </nav>
				</div>

				<div class="panel-body stats-overflow">
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<h4>Monat {{$startofmonth->formatLocalized('%B %Y')}}</h4>
							<table class="table table-bordered table-striped table-condensed">
								<tr>
									<th>Eintrag</th>
									@for($i = 1; $i <= 8; $i++)
									<th>{{$weekday[$i]}}</th>
									@endfor
								</tr>
								@foreach($entries as $entry)
								<tr>
								<td>{{$entry->name}}</td>
								@for($i=1; $i<=8; $i++)
								<td>
								@foreach($eventsInMonth->filter(function($item) use($entry, $i) { if ($item->entry_id == $entry->id && $item->weekday == $i) return true;}) as $events)
								{{$events->numberdays}}
								@endforeach
								</td>
								@endfor
								</tr>
								@endforeach
							</table>
						</div>
						<div class="col-md-10 col-md-offset-1">
							<h4>Jahr {{$startofmonth->formatLocalized('%Y')}}</h4>
							<table class="table table-bordered table-striped table-condensed">
								<tr>
									<th>Eintrag</th>
									@for($i = 1; $i <= 8; $i++)
									<th>{{$weekday[$i]}}</th>
									@endfor
								</tr>
								@foreach($entries as $entry)
								<tr>
								<td>{{$entry->name}}</td>
								@for($i=1; $i<=8; $i++)
								<td>
								@foreach($eventsInYear->filter(function($item) use($entry, $i) { if ($item->entry_id == $entry->id && $item->weekday == $i) return true;}) as $events)
								{{$events->numberdays}}
								@endforeach
								</td>
								@endfor
								</tr>
								@endforeach
							</table>
						</div>
						<div class="col-md-10 col-md-offset-1">
							<h4>Gesamt</h4>
							<table class="table table-bordered table-striped table-condensed">
								<tr>
									<th>Eintrag</th>
									@for($i = 1; $i <= 8; $i++)
									<th>{{$weekday[$i]}}</th>
									@endfor
								</tr>
								@foreach($entries as $entry)
								<tr>
								<td>{{$entry->name}}</td>
								@for($i=1; $i<=8; $i++)
								<td>
								@foreach($eventsTotal->filter(function($item) use($entry, $i) { if ($item->entry_id == $entry->id && $item->weekday == $i) return true;}) as $events)
								{{$events->numberdays}}
								@endforeach
								</td>
								@endfor
								</tr>
								@endforeach
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> <!---->

@endsection
