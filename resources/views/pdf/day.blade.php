@extends('pdf/app')

@section('title')
    {{Auth::user()->company->name}} - {{Auth::user()->department->name}}
@endsection

@section('css')
<link href="{{ asset('/css/print.css') }}" rel="stylesheet">
<!--<link href="{{ asset('/css/styles.css') }}" rel="stylesheet">-->

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
<div style="width:100%; float:right">Datum: {{date("j.n.Y")}} </div>
<h2>
    Abwesenheiten in respla.de
</h2>
<h2>Abteilung {{Auth::user()->department->name}}, {{Auth::user()->company->name}}</h2>
<h3> Dienste am {{$day->formatLocalized('%A %e. %B %Y')}}, @if($customdate->count()>0 && $customdate->first()->cusname!="") {{$customdate->first()->cusname}} @endif </h3>

<table class="table table-bordered table-condensed">
    <tbody>
        <tr>
            <th>Name</th>
            <th>Bereich</th>
            <th>Eintrag</th>
            <th>Kommentar</th>
        </tr>
        @foreach ($events as $e)
        <tr data-id="{{$e->uid}}">
            <td class="col1 {{$e->class}} {{$e->class2}}">
                {{$e->fullname}}
            </td>
            <td class="col2 {{$e->class}} {{$e->class2}}">
                {{$e->secname}}
            </td>
            <td class="col3 {{$e->class}} {{$e->class2}}">
                {{$e->entryname}}
            </td>
            <td class="col4 {{$e->class}} {{$e->class2}}">
                {{$e->eventscomment}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@if ($holiday->count()>0)
<br>
<h3>Ferien /sonstige Termine:</h3>
@foreach ($holiday as $h)
{{$h->name}}<br>
@endforeach
@endif
<br>
<h3>Bemerkung:</h3>
@if($comment->count()>0) {{$comment->first()->text }} @endif

@endsection
