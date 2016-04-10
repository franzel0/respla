@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">Passwort ändern{{$user}}</div>
				<div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
  					{!! Form::open(['method' => 'PATCH', 'route' => ['password.update', Auth::user()->id], 'class' => 'form-horizontal']) !!}
                    
                        <div class="input-group minwidth170">
                            <span class="input-group-addon">Passwort</span>
                            {!! Form::text('password', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        </div>
                        <small class="text-danger">{{ $errors->first('password') }}</small>
                        <div class="input-group  minwidth170">
                            <span class="input-group-addon">Passwort wiederholen</span>
                            {!! Form::text('password2', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        </div>
                        <small class="text-danger">{{ $errors->first('password2') }}</small>
                                            
                        <div class="btn-group pull-right">
                            {!! Form::submit("Ändern", ['class' => 'btn btn-success']) !!}
                        </div>
                    
                    {!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection