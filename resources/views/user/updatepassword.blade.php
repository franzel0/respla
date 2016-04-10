@extends('app')

@section('content')
<?php
$user = Auth::user();
?>
<div class="col-md-3"></div>
<div class="col-md-6">
	<div class="panel panel-default">
        <div class="panel-heading">Passwort ändern</div>
        <div class="panel-body create">
        	<div class="row">
        		{!!Form::open(array('action' => 'userController@updatepassword'))!!}
        		<div class="form-group col-md-12">
              		{!!Form::label('oldpw', 'Altes Passwort', array('class' => 'form-label'))!!}
              		{!!Form::text('oldpw','', array('class' => 'form-control'))!!}
          		</div>
          		<div class="form-group col-md-12">
              		{!!Form::label('newpw1', 'Neues Passwort', array('class' => 'form-label'))!!}
              		{!!Form::text('newpw1','', array('class' => 'form-control'))!!}
          		</div>
          		<div class="form-group col-md-12">
              		{!!Form::label('newpw2', 'Bitte neues Passwort wiederholen', array('class' => 'form-label'))!!}
              		{!!Form::text('newpw2','', array('class' => 'form-control'))!!}
          		</div>
          		<div class="form-group col-md-3">
              		{!!Form::submit('Speichern', array('class' => 'btn btn-primary'))!!}
          		</div> 
        		{!!Form::close()!!}
        	</div>
        </div>
    </div>
</div>
<div class="col-md-3"></div>    

@endsection