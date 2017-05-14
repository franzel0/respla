@extends('app')

@section('headercsrf')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('links')
<link href="{{ asset('/css/duties.css') }}" rel="stylesheet">
<script src="{{asset('/js/duties.js')}}"></script>

@endsection

@section('css')
<style>
td, th{
    min-width: 140px !important;
    max-width: 140px !important;
}
table tr{
    height:30px !important;
}
.weekend{
    background-color: #ED7600;
}
.customdate{
    background-color: #CC6600;
}
.holiday{
    background-size: auto 100%;
    background-repeat: no-repeat;
    background-image: url({{URL::asset('img/holiday.png')}});
}
.today{
    background-color: green;
}
.form-control{
    width:auto;
}
.comment{
    background-image: url({{URL::asset('img/comment.png')}});
    background-repeat: no-repeat;
    background-position: right top;
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
<div class="container-fluid">
    <div class="row" >
        <div class="col-md-12 padding5">
            <div  class="panel panel-default">
                <div class="panel-heading panel-heading-month">
                    <nav class="navbar navbar-default">
                        <span class="navbar-brand">Gemeinsame Dienste</span>
                        {!!Form::open(['route' => ['overviewOncalls', Auth::user()->company->id], 'method' => 'get', 'class' => 'navbar-form navbar-left form-inline', 'role' => 'search'])!!}
                            {!! Form::hidden('day', $startOfMonth->toDateString()) !!}
                            <div class="form-group">
                                {!!Form::button('<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>', array('name' => 'back', 'class' => 'btn btn-primary', 'type' => 'submit', 'value' => '1'))!!}
                                {!!Form::button('<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>', array('name' => 'next', 'class' => 'btn btn-primary', 'type' => 'submit', 'value' => '1'))!!}
                                {!!Form::label('month', 'Monat', array('class' => 'hidden-xs'))!!}
                                {!!Form::select('month', $monate, $startOfMonth->month, array('class' => 'form-control'))!!}
                                {!!Form::label('year', 'Jahr', array('class' => 'hidden-xs'))!!}
                                {!!Form::text('year', $startOfMonth->year, array('size' => '4', 'placeholder' => 'Jahr', 'class' => 'form-control'))    !!}
                                {!!Form::button('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', array('class' => 'btn btn-primary', 'type' => 'submit', 'value' => '1'))!!}
                                {!!Form::submit('JETZT', array('name' => 'this', 'class' => 'btn btn-primary'))!!}
                            </div>
                        {!!Form::close() !!}
                    </nav>
                </div>
                <div id="panel-body" class="panel-body panel_month" style="padding: 0px 15px">
                    <div class="row">
                        <div id="left" class="col-md-1 col-xs-2 padding0 overflow">
                            <div class="left-top">
                                <table class="table table-bordered padding0">
                                    <tr>
                                       <td>
                                           Datum
                                       </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="left-bottom">
                                <table class="table table-bordered padding0">
                                    @for($day= clone $startOfMonth; $day <= $endOfMonth ; $day->addDay())
                                        <tr class="@if($day->isToday()) today @endif @if($day->isWeekend())weekend @endif @if($cusdate = $customdates->where('date', $day->toDateString()) and $cusdate->count()>0 ) customdate" title="{{$cusdate->first()['name']}} @endif">
                                            <td>
                                                {{ $day->formatLocalized('%a %e. %b') }}
                                            </td>
                                        </tr>
                                    @endfor
                                </table>
                            </div>
                        </div>
                        <div id="right" class="col-md-11 col-xs-10 padding0 overflow">
                            <div id="right-top" >
                                <table class="table table-bordered">
                                    <tr>
                                        @foreach($allentries as $e)
                                            <td>
                                                {{$e->name}}
                                            </td>
                                        @endforeach
                                    </tr>
                                </table>
                            </div>
                            <div id="right-bottom">
                                <table id="table-events" class="table table-bordered">
                                    <colgroup>
                                    @for($i=0;$i<$allentries->count();$i++)
                                        <col style="min-width:100px; width: {{round(100/$allentries->count(),2)}}%">
                                    @endfor
                                    @for($day= clone $startOfMonth; $day <= $endOfMonth; $day->addDay())
                                        <tr data-date="{{$day->toDateString()}}" class="@if($day->isToday()) today @endif @if($day->isWeekend())weekend @endif @if($cusdate = $customdates->where('date', $day->toDateString()) and $cusdate->count()>0 ) customdate" title="{{$cusdate->first()['name']}} @endif">
                                            @foreach($allentries as $e)
                                                @if($ev = $events->where('date', $day->toDateString())->where('entry_id', $e->id) and $ev->count()>0)
                                                    <td @if($ev->first()->comment) class="comment" @endif title='Bemerkung: {{$ev->first()->comment}}, Telefon: {{$ev->first()->phone}}' data-id='{{$ev->first()->id}}' data-user='{{$ev->first()->user_id}}' data-entryid='{{$ev->first()->entry_id}}'>
                                                    {{$ev->first()->fullname}}
                                                @else
                                                    <td title='' data-id='0' data-user='0' data-entryid='{{$e->id}}'>
                                                @endif
                                            </td>
                                            @endforeach
                                        </tr>
                                    @endfor
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalUser" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mitarbeiter ändern</h4>
            </div>
            <div class="modal-body">
                <div class="form-group{{ $errors->has('inputname') ? ' has-error' : '' }}">
                    {!! Form::label('userId', 'Mitarbeiter auswählen') !!}
                    <select id="userId" class="form-control" name="userId">
                    </select>
                    <small class="text-danger">{{ $errors->first('userId') }}</small>
                </div>
                <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
                    {!! Form::label('comment', 'Kommentar') !!}
                    {!! Form::text('comment', null, ['id' => 'comment', 'class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('comment') }}</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                <button type="button" id="saveUser" class="btn btn-primary">Speichern</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
$(document).ready(function(){
    var cell;
    $('td').click(function() {
        cell = $(this);
        date = $(this).parents("tr").data("date");
        user_id = $(this).data("user");
        event_id = $(this).data("id");
        entry_id = $(this).data('entryid');
        comment = $(this).attr('title').split(',')[0].substring(11);
        oldevent_id = 0;
        //get users for select
        $.ajax({
		    url: '/modalGetUsers',
		    type: 'post',
		    data: {entry_id: entry_id,
                   user_id: $(this).data('user')
		    	  },
		    success: function(data){
				$('#userId').html(data);
                $('#comment').val(comment);
                $('#modalUser').modal();
		    },
		    error: function(data){
		    	var a = JSON.parse(data.responseText);
      		  	alert('Fehlerr: ' + a);
      		}
    	});
    });
    $('#saveUser').on('click', function(){
        //check if user has already an event
        $.ajax({
            url: "/modalUserHasEvent",
            type: "post",
            async: false,
            data: { user_id: $("#userId").val(),
                    date: date,
            },
            success: function(data){
                var result = JSON.parse(data)
                if (result.id > 0){
                    if(!confirm($("#userId option:selected").text() + " hat schon den Eintrag: " + result.name + " an diesem Tag \nMöchten Sie diesen Eintrag überschreiben?")) return;
                    //still missing: existing entry must be deleted on view!
                    oldevent_id = result.id;
                }
                else{
                    oldevent_id = 0;
                }
                //change to new user
                $.ajax({
                    url: "/modalChangeUser",
                    type: "post",
                    data: { user_id: $("#userId").val(),
                            event_id: event_id,
                            date: date,
                            comment: $('#comment').val(),
                            entry_id: entry_id,
                            oldevent_id: oldevent_id
                    },
                    success: function(data){
                        var result = JSON.parse(data);
                        // erase oldevent if exists
                        if(oldevent_id >0){
                            $("td[data-id='" + oldevent_id +"']").html("");
                        }
                        cell.html($("#userId option:selected").text());
                        cell.attr('data-user', result.user_id);
                        cell.data('user',result.user_id);
                        cell.attr('data-id', result.id);
                        cell.data('id',result.id);
                        cell.attr('title', 'Bemerkung: ' + result.comment + ', Telefon: ');
                        if(result.comment != "") {
                            cell.attr('class', 'comment')
                        };
                        $('#modalUser').modal('hide');
                    },
                    error: function(){
                        alert("Fehler beim Ändern des Benutzers.");
                    }
                });
            },
            error: function(){
                alert("Fehler beim Überprüfen der Einträge");
            }
        });
    });
})
</script>
@endsection
