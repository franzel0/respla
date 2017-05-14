@extends('app')

@section('scripts')
<!-- additional files-->
<link href="{{ asset('/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('/js/bootstrap-colorpicker.min.js') }}"></script

<!--Add the token for AJAX-rquests-->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
   $( document ).ready(function() {
        $.expr[":"].contains = $.expr.createPseudo(function(arg) {
            return function( elem ) {
                return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
            };
        });

        $('.bgcolor').colorpicker();

        $('.textcolor').colorpicker();

		$("#search").keyup(function(){
			var x = $(this).val();
			if (x != "") {
				$( ".panel-body li" ).hide();
				$( ".panel-body li:contains('"+ x + "')" ).show();
			}
			else{
				$(".panel-body li").show();
			}
		});

		//changing the company when updating selectform
		$('#company_id').on('change', function(e){
			var route = '{{ route("company.oncall.index", [":company"]) }}';
    		route = route.replace(':company', $('#company_id').val());
    		window.location.href = route;
		});

	});
</script>
@endsection

@section('content')
<div id='xtoken' class="hidden">{!! csrf_token() !!}</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Gemeinsame Dienste oder Einträge</div>
				<div class="panel-body">
  					<div class="row">
						@if (isset($oncall))
                        {!! Form::model($oncall, ['method' => 'PATCH', 'route' => ['company.oncall.update', $company_id, $oncall->id]]) !!}
                        @else
                        {!! Form::open(['route' => ['company.oncall.store', $company_id]]) !!}
                        @endif

  						<div class="col-md-6 col-padding-top-5">
  							<div class="panel panel-default">

								<div class="panel-heading">Einträge</div>
								<div class="panel-body listitems">
									@if(Session::has('flash_message'))
								    <div class="alert alert-success">
								        {{ Session::get('flash_message') }}
								    </div>
									@endif

                                    @if(Auth::user()->can('changecompany'))
                                    <div class="input-group minwidth120">
                                    	{!! Form::label('company_id', 'Krankenhaus', array('class' => 'input-group-addon')) !!}
                                    	{!! Form::select('company_id', \App\Company::orderBy('name')->lists('name', 'id'), $company_id, array('class' => 'form-control select select2')) !!}
                                    </div>
                                    @else
                                    <div class="input-group minwidth120">
                                    	<span class="input-group-addon">Krankenhaus</span>
                                    	<span class="form-control">{{Auth::user()->department->company->name}}</span>
                                    </div>
                                    {!! Form::hidden('company_id', $company_id, ['id' => 'company_id']) !!}
                                    @endif
                                    <div class="input-group">
    									<a href="{{ route('company.oncall.create', [$company_id])  }}" class="btn btn-success">Dienst hinzufügen</a>
    								</div>
    								<ul  id="sortable">
									@foreach (\App\Entry::where('company_id', '=', $company_id)->get() as $o)
										<li data-id="{{ $o->id }}">
											<a href="{{ route('company.oncall.edit', [$company_id, $o->id]) }}">{{ $o->name }}</a>
										</li>
									@endforeach
									</ul>
								</div>
							</div>
  						</div>
  						<div class="col-md-6 col-padding-top-5">
  							<div class="panel panel-default">
								<div class="panel-heading">Detail</div>

								<div class="panel-body">

                                    @if(!isset($index))
                                        <div class="form-group">
                                            {!! Form::label('name', 'Name') !!}
                                            {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                            <small class="text-danger">{{ $errors->first('name') }}</small>
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                {!! Form::checkbox('isactive', 1, null, ['id' => 'isactive']) !!}
                                            </div>
                                            <span class="form-control">
                                                Aktiv
                                            </span>
                                            <small class="text-danger">{{ $errors->first('isactive') }}</small>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('phone', 'Telefon') !!}
                                            {!! Form::text('phone', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                            <small class="text-danger">{{ $errors->first('phone') }}</small>
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('shorttext', 'Kürzel') !!}
                                            {!! Form::text('shorttext', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                            <small class="text-danger">{{ $errors->first('shorttext') }}</small>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group bgcolor">
                                                    <span class="input-group-addon">Hintergrundfarbe</span>
                                                    {!! Form::text('bgcolor', null, ['class' => 'form-control bgcolor', 'required' => 'required']) !!}
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>
                                                <small class="text-danger">{{ $errors->first('bgcolor') }}</small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        {!! Form::checkbox('present', 1, null, ['id' => 'present']) !!}
                                                    </div>
                                                    <span class="form-control">
                                                        Anwesend
                                                    </span>
                                                    <small class="text-danger">{{ $errors->first('present') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        {!! Form::checkbox('right', 1, null, ['id' => 'right']) !!}
                                                    </div>
                                                    <span class="form-control">
                                                        Freigaberechte erforderlich
                                                    </span>
                                                    <small class="text-danger">{{ $errors->first('right') }}</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        {!! Form::checkbox('onweekend', 1, null, ['id' => 'onweekend']) !!}
                                                    </div>
                                                    <span class="form-control">
                                                        Auch an Wochendenden
                                                    </span>
                                                    <small class="text-danger">{{ $errors->first('onweekend') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        {!! Form::checkbox('isvisible', 1, null, ['id' => 'isvisible']) !!}
                                                    </div>
                                                    <span class="form-control">
                                                        Sichtbar in Übersicht
                                                    </span>
                                                    <small class="text-danger">{{ $errors->first('visible') }}</small>
                                                </div>
                                            </div>
                                        </div>

                                        @if(isset($oncall))

                                        {!! Form::submit('Speichern', ['class' => 'btn btn-info pull-right']) !!}

                                        @else

                                        <div class="btn-group pull-right">
                                            {!! Form::submit("Hinzufügen & Speichern", ['class' => 'btn btn-info']) !!}
                                        </div>

                                        @endif

                                    @endif

  								</div>
  							</div>
  						</div>

                        {!! Form::close() !!}

  					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
