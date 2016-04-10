@extends('app')

@section('content')
<?php
$abteilungen = \App\department::all()->lists('name', 'id');
?>
<div class="col-md-1">
    
</div>
<div class="col-md-10">
    <div class="panel panel-default">
        <div class="panel-heading">Neuen Benutzer anlegen</div>
        <div class="panel-body create">
        <div class="row">
          <div class="col-md-12">
            <?php
            //$messages = $validator->errors();
            ?>
            <ul>
              @foreach($errors->all() as $message)
              <li>{{$message}}</li>  
              @endforeach
            </ul>
          </div>
          {!!Form::open(array('class' => 'fform-inline'))!!}
          <div class="form-group col-md-3">
              {!!Form::label('firstname', 'Vorname', array('class' => 'form-label'))!!}
              {!!Form::text('firstname', old('firstname'), array('class' => 'form-control'))!!}
              @if ($errors->has('firstname'))<p style="color:red;">Bitte Vorname eingeben</p>@endif
          </div>
          <div class="form-group col-md-3">
              {!!Form::label('lastname', 'Nachname', array('class' => 'form-label'))!!}
              {!!Form::text('lastname', old('lastname') , array('class' => 'form-control'))!!}
              @if ($errors->has('lasttname'))<p style="color:red;">Bitte Nachname eingeben</p>@endif
          </div>
          <div class="form-group col-md-3">
              {!!Form::label('name', 'Name', array('class' => 'form-label'))!!}
              {!!Form::text('name',old('name') , array('class' => 'form-control'))!!}
              @if ($errors->has('name'))<p style="color:red;">Bitte login-Name eingeben</p>@endif
          </div>
          <div class="form-group col-md-3">
              {!!Form::label('email', 'e-Mail Adresse', array('class' => 'form-label'))!!}
              {!!Form::text('email',old('email') , array('class' => 'form-control'))!!}
          </div>
          <div class="form-group col-md-3 ">
            <div class="input-group" style="margin-top:25px">
              <span class="input-group-addon">
                {!!Form::checkbox('canlogin', 'loggedin', false, array('class' => 'checkbox', 'type' => 'checkbox'))!!}
              </span>
              <span class="form-control">Anmelden</span>
            </div><!-- /input-group -->
          </div>
          <div class="form-group col-md-3">
              {!!Form::label('hospital_id', 'Krankenhaus', array('class' => 'form-label'))!!}
              {!!Form::select('hospital_id', array('0' => '') + \App\institution::all()->lists('name', 'id'), '', array('class' => 'form-control', 'placeholder' => 'Bitte Krankenhaus einegeben'))!!}
          </div>
          <div class="form-group col-md-3">
              {!!Form::label('department_id', 'Abteilung', array('class' => 'form-label'))!!}
              {!!Form::select('department_id', \App\department::all()->lists('name', 'id'), '', array('class' => 'form-control'))!!}
          </div>
          <div class="form-group col-md-3">
              {!!Form::submit('Anlegen', array('class' => 'btn btn-primary', 'style' =>'margin-top: 24px;'))!!}
          </div>          
          {!!Form::close()!!}
        </div>
        </div>
    </div>
</div>
<div class="col-md-1">
    
</div>

@endsection