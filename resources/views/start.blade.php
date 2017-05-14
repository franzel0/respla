@extends('app')

@section('scripts')
<script type="text/javascript">
$( document ).ready(function() {
	$( ".load_button" ).click(function() {
		$("#loading").show();
	});
});
</script>
@endsection

@section('content')

<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
				Angemeldet als {{Auth::user()->name}}
				</div>

				<div class="panel-body">
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<div id="loading" class="alert alert-info" style="display:none">
								Bittte warten... <img src="{{ asset('/images/loading.gif') }}" />
							</div>
							@if (isset($company))
    							<div class="alert alert-success">
        							{{ $company->name}} erstellt!
    							</div>
							@endif
							@if (isset($info))
    							<div class="alert alert-success">
        							Daten von {{ $deletedcompany->name}}, id: {{$deletedcompany->id}} gelöscht!
    							</div>
							@endif
						</div>
						<div class="col-md-8 col-md-offset-2">
							<div class="col-md-4">
								{!! Form::open(['method' => 'GET', 'action' => 'StartController@create', 'class' => 'form-horizontal float-left', 'id' => 'load_company']) !!}
									<div class="form-group">
							    		{!! Form::submit('Neue Klinik anlegen', ['class' => 'btn btn-info load_button']) !!}
									</div>
								{!! Form::close() !!}
							</div>
						</div>
						<div class="col-md-8 col-md-offset-2 well">
							{!! Form::open(['action' => 'StartController@destroy', 'method' => 'POST', 'class' => 'form-horizontal']) !!}

							<div class="form-group{{ $errors->has('company') ? ' has-error' : '' }}">
								{!! Form::label('company', 'Company') !!}
								{!! Form::select('company', $options, 1, ['class' => 'form-control', 'required' => 'required', 'size' => 10, 'multiple']) !!}
								<small class="text-danger">{{ $errors->first('company') }}</small>
							</div>

					    	<div class="form-group">
					    		{!! Form::submit("Delete", ['class' => 'btn btn-danger']) !!}
					    	</div>

							{!! Form::close() !!}
						</div>
						@if(isset($company))
							@include('demofacts')
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div> <!---->

@endsection
