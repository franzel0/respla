<div  id="panel-body" class="panel-body plan-panel-body">
    <div class="row">
        <div class="col-md-3 col-sm-4">
            <div class="row">
                <div class="col-md-12 plan-title-overflow">
                    <div class="colorapproved" style="padding: 5px; margin-bottom: 5px; border: 1px solid #cccccc; background-color: @if($ev = $events->where('entry_id', $entry) and $ev->count()>0 and $ev->first()->approved) #449D44 @else #C9302C @endif">
                        <h3 id="textapproved" style="text-align: center;">@if($ev = $events->where('entry_id', $entry) and $ev->count()>0 and $ev->first()->approved) Freigegeben @else Nicht freigegeben @endif</h3>
                        <input id="approve" @if($ev = $events->where('entry_id', $entry) and $ev->count()>0 and $ev->first()->approved) checked @endif data-toggle="toggle" data-width="100%" data-on="Freigabe für Dienstplan aufheben!" data-off="Dienstplan Freigeben!" data-onstyle="warning" data-offstyle="info" type="checkbox">
                        {!! Form::hidden('inputname', $startofmonth->toDateString(), ['id' => 'date']) !!}
                    </div>
                    <hr>
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
                                    <th width="100%">Termine</th>
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
                                        @if($ev = $events->filter(function($item) use ($day, $u) { if($item->date == $day->toDateString() && $item->user_id == $u->id) return true;}) and count($ev)>0)
                                            <td class="item{{$ev->first()->entry_id}}" style="border: 1px solid #dddddd;">
                                                {{$ev->first()->entry_name}}
                                        @else
                                            <td style="border: 1px solid #dddddd;">
                                        @endif
                                        </td>
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
