<style>
button[class^="item"] {
    border: 1px solid grey;
    margin: 5px;
}
</style>

<!-- modal for inserting events after selected dates-->
<div class="modal fade" id="item_selection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Auswahl des Eintrags</h4>
      </div>
        <div class="modal-body">
            <div class="form-group" style="margin-top: 10px;">
                <div class="input-group">
                    <input type="text" name="comment" class="form-control" id="comment" value="" placeholder="Kommentar eingeben">
                    <span class="input-group-btn">
                        <button id="insertcomment" class="btn btn-default selected-event" type="button" value=-1>K!</button>
                    </span>
                </div><!-- /input-group -->
            </div>
            @if(Auth::user()->can('changeentries'))
            <div class="input-group">
                <div class="input-group-addon">
                    {!! Form::checkbox('approve', 1, null, ['id' => 'approve']) !!} 
                </div>
                <span class="form-control">
                    Genehmigen
                </span>
                <span class="input-group-btn">
                    <button class="btn btn-default selected-event" value=-2 type="button">Freigeben!!</button>
                </span>
            </div>
            <hr>
            @endif
            @foreach($entrylist as $el)
            <button type='button' class="item{{$el->id}} btn selected-event" value="{{$el->id}}">
                {{$el->name}}
                </button>
            @endforeach
            <button type="button" class="btn selected-event" value="0" el="background-color:black; border:1px solid green; color:white" value=0>Löschen</button><br>
        </div>
      <div class="modal-footer">
        <button id="schliessen" type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
      </div>
    </div><!-- /.modal-content -->
 </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--modal for manually inserting events-->
<div class="modal fade" id="insert_events">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Manueller Eintrag</h4>
            </div>
            <div class="modal-body">
                {!! Form::open() !!}
                
                    @if(Auth::user()->can('changeevents'))
                    <div class="input-group">
                        <div class="input-group-addon">
                            Mitarbeiter
                        </div>
                        {!! Form::select('selectUser', $userlist, null, ['class' => 'form-control select2', 'id' => 'selectUser', 'required' => 'required']) !!}
                        <small class="text-danger">{{ $errors->first('select_user') }}</small>
                    </div>
                    @else
                    {!! Form::hidden('selectUser', Auth::user()->id, ['id' =>'selectUser']) !!}
                    @endif

                    <div class="input-group">
                        <div class="input-group-addon">
                            Eintrag auswählen
                        </div>
                        <select class="form-control select2 hidden-sm hidden-xs" name="entry" id="entry" required="required"> 
                        <?php
                            foreach($options as $o)
                            {
                                echo $o;
                            }
                        ?>
                        </select>
                        <small class="text-danger">{{ $errors->first('entry') }}</small>
                    </div>
                    <div class="input-group pull-left" style="width: 50%">
                        <div class="input-group-addon">
                            Von
                        </div>
                        {!! Form::text('date_from', null, ['class' => 'form-control dates', 'id' => 'date_from', 'required' => 'required']) !!}
                        <small class="text-danger">{{ $errors->first('date_from') }}</small>
                    </div>

                    <div class="input-group" style="width: 50%; padding-left: 5px">
                        <div class="input-group-addon">
                            Bis
                        </div>
                        {!! Form::text('date_to', null, ['class' => 'form-control dates', 'id' => 'date_to', 'required' => 'required']) !!}
                        <small class="text-danger">{{ $errors->first('date_to') }}</small>
                    </div>

                    <div class="input-group">
                        <div class="input-group-addon">
                            Kommentar
                        </div>
                        {!! Form::text('comment2', null, ['class' => 'form-control', 'id' => 'comment2']) !!}
                        <small class="text-danger">{{ $errors->first('comment2') }}</small>
                    </div>

                    @if(Auth::user()->can('changeentries'))
                    <div class="input-group" style="width:120px">
                        <span class="input-group-addon">
                            Genehmigen
                        </span>
                        <div class="form-control">
                            {!! Form::checkbox('approve2', 1, null, ['id' => 'approve2']) !!} 
                        </div>
                    </div>
                    @endif

                
                {!! Form::close() !!}
                <div id="errors">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warining save_events" value=0>Löschen</button>
                <button type="button" class="btn btn-primary save_events" value=-3>Speichern</button>
                <button type="button" class="btn btn-primary save_events" value=-1>Kommentar speichern</button>
                <button type="button" class="btn btn-primary save_events" value=-2>Nur freigeben</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->