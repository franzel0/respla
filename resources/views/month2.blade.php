@extends('app')

@section('headercsrf')

@endsection

@section('links')
<link href="{{ asset('/css/jquery-ui.min.css') }}" rel="stylesheet">
<link href="{{ asset('/css/jquery-ui.theme.min.css') }}" rel="stylesheet">
<link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('/css/dataTables.fixedColumns.min.css') }}" rel="stylesheet">

<script src="{{asset('/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/js/dataTables.fixedColumns.min.js')}}"></script>
<script src="{{asset('/js/month.js')}}"></script>
@endsection

@section('css')
<style type="text/css">
.orange{
    background-color: thistle;
}
@foreach($styles as $style)
.item{{$style->id}} {
  color: {{($style->present) ? $style->textcolor : '#000000'}};
  background-color: {{($style->present) ? 'transparent': $style->bgcolor}};
  background-image: url({{($style->wish) ? URL::asset('/img/bg.png') : ''}});
  {{$style->wish}}
}
@endforeach
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
$dt = Carbon::now();
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($date, $interval, $endofmonth);
$ev =$events->toArray();
?>
{!! HTML::script('/js/overview.js'); !!}
<div id='xtoken' class="hidden">{!! csrf_token() !!}</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 padding5">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {!!Form::open(['action' => 'itemController@showMonth', 'method' => 'post', 'class' => 'form-inline'])!!}
                        <div class="form-group" style='width:100px; font-size: 16px;'>
                            {!!Form::label('overview', 'ÜBERSICHT ', array('style' => 'width:150px, font-size: 16px;'))!!}
                        </div>
                        <div class="form-group">
                            {!!Form::label('month', 'Monat', array('class' => 'hidden-xs'))!!}
                            {!!Form::select('month', $monate, $date->month, array('class' => 'form-control'))!!}
                        </div>    
                        <div class="form-group">
                            {!!Form::label('year', 'Jahr', array('class' => 'hidden-xs'))!!}
                            {!!Form::text('year', $date->year, array('size' => '4', 'placeholder' => 'Jahr', 'class' => 'form-control'))!!}
                        </div>
                        <div class="form-group">
                            {!!Form::submit('auswählen', array('class' => 'btn btn-primary'))!!}
                        </div> 
                        <div class="form-group">
                            {!!Form::submit('Monat vorher', array('name' => 'back', 'class' => 'btn btn-primary'))!!}
                        </div>
                        <div class="form-group">
                            {!!Form::submit('Monat nachher', array('name' => 'next', 'class' => 'btn btn-primary'))!!}
                        </div> 
                    {!!Form::close() !!}
                    
             </div>
             <div class="panel-body" style="padding: 0px 15px">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 padding0">
                            <?php
                            $s=current($ev);
                            ?>
                            <table id="table-events" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                      Datum
                                    </th>
                                    @foreach($period as $dt)
                                    <th class="@if($dt->isWeekend()) orange @endif">
                                    {{$dt->format('d.')}}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr data-id="{{$user->id}}">
                                  <td>
                                    {{$user->name}}
                                  </td>
                                    @foreach($period as $dt)
                                      @if($s['date']==$dt->format('Y-m-d') && $s['user_id']==$user->id)
                                        <td class="item{{$s['reason_id']}} @if($dt->isWeekend()) orange @endif" data-reasonid="{{$s['reason_id']}}" data-date="{{$dt->format('Y-m-d')}}">
                                          {{$s['reason']['shorttext']}}
                                        </td>
                                        <?php
                                        while($s['date']==$dt->format('Y-m-d') && $s['user_id']==$user->id){
                                          $s=next($ev);
                                        }  
                                        ?>
                                      @else
                                        <td class="@if($dt->isWeekend()) orange @endif" data-reasonid="0" data-date="{{$dt->format('Y-m-d')}}"></td>
                                      @endif
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--@foreach($events as $e)
    {{$e['user_id']}}/{{$e['date']}}
    <br>
    @endforeach-->
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
<div class="modal fade" id="item_selection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Auswahl des Eintrags</h4>
      </div>
      <div class="modal-body">
        <div class="form-group" style="margin-top: 10px;">
            <div class="input-group">
                <input type="text" name="comment" class="form-control" id="comment" placeholder="Kommentar eingeben">
                <span class="input-group-btn">
                    <button id="insertcomment" class="btn btn-default" type="button" value=-1>K!</button>
                </span>
            </div><!-- /input-group -->
        </div>
            @foreach($styles as $style)
            <button type='button' class="item{{$style->id}} btn selected-event" value="{{$style->id}}">{{$style->name}}</button>
            @endforeach
            <button type="button" class="btn selected-event" value="0" style="background-color:black; border:1px solid green; color:white" value=0>Löschen</button><br>
       </div>
      <div class="modal-footer">
        <button id="schliessen" type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
      </div>
    </div><!-- /.modal-content -->
 </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
