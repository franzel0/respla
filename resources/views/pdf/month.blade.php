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
  background-image: url({{($s->wish) ? URL::asset('/img/bg.png') : ''}});
  /*background-color: {{($s->present) ? 'transparent': 'lightgrey'}};
  background-image: url({{($s->wish) ? public_path().'/img/bg.png' : ''}});*/
  background-size: auto 100%;
}
@endforeach

@page {
  size: A4 landscape;
}

table{
    border-collapse: collapse;
    font-size: 0.6em;
}
td, th{
    border: 1px solid black;
    height:25px;
    width:18px;
    text-align: left;
    vertical-align: top;
    overflow: hidden;
    text-overflow: ellipsis;
}
.badge {
    display: inline-block;
    min-width: 10px;
    padding: 1px 1px;
    color: #ffffff;
    line-height: 1;
    vertical-align: baseline;
    white-space: nowrap;
    text-align: center;
    background-color: #777777;
    border-radius: 10px;
}
</style>
@endsection

@section('content')
<span style="float:right">Datum: {{date("j.n.Y")}} </span>
<h1>Abwesenheiten für {{$date}}</h1>
<h2>Abteilung {{Auth::user()->department->name}}</h2>
<?php
$row=0;
?>
<table id="month_table">
    <tr>
        <th>
            Datum
        </th>
        @foreach($daysinmonth as $day)
            <th class="{{$day['class']}}">{!!$day['dayofmonth']!!}</th>
        @endforeach
    </tr>
    @foreach( $events as $e )

        @if ( $e->monthday == 1 )
            <?php
            $row++;
            ?>

            @if($row % 10 === 0)
            </table>
            <p style="page-break-after:always;"></p>
            <table>
            <tr>
                <th>
                    Datum
                </th>
                @foreach($daysinmonth as $day)
                    <th class="{{$day['class']}}">{!!$day['dayofmonth']!!}</th>
                @endforeach
            </tr>
            <?php
            $row=1;
            ?>
            @endif

            <tr data-id={{$e->userid}}>
                <td width='100'>
                    {{$e->fullname}} {{$row}}
                </td>
        @endif
            <td class="{{$e->class1}} {{$e->class2}} {{$e->class3}}">
                {{$e->shorttext}} @if($e->comment <> '')<br><span class='badge'>K</span> @endif
            </td>
        @if ( $e->monthday == $numberdays )
            </tr>
        @endif
    @endforeach
</table>
<h2>Legende</h2>
@foreach ($styles as $s)
<span class="item{{$s->id}}" style="padding: 2px;">{{$s->shorttext}}</span>: {{$s->name}};
@endforeach
@endsection
