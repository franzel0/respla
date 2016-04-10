@extends('app')

@section('scripts')
<!--Add the token for AJAX-rquests-->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
	$( document ).ready(function() {
		$.expr[":"].contains = $.expr.createPseudo(function(arg) {
			return function( elem ) {
				return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
			};
		});

		/*$.ajaxSetup({
			headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        	}
    	});*/
		
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


		//changing the department when updating selectform
		$('#company_id').on('change', function(e){
			var route = '{{ route("company.show", ":company") }}';
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
				<div class="panel-heading">Einrichtung</div>
				<div class="panel-body">					
  					<div class="row">
                        @if (isset($company))
                        {!! Form::model($company, ['method' => 'PATCH', 'route' => ['company.update', $company->id]]) !!}
                        @else
                        {!! Form::open(['route' => ['company.store']]) !!}
                        @endif
  					
  						<div class="col-md-6 col-padding-top-5">
  							<div class="panel panel-default">
  							
								<div class="panel-heading">Einrichtung</div>
								<div class="panel-body listitems">
									@if(Session::has('flash_message'))
								    <div class="alert alert-success">
								        {{ Session::get('flash_message') }}
								    </div>
									@endif

									@if(Auth::user()->can('changecompany'))
                                    <div class="input-group minwidth120">
                                        {!! Form::label('company_id', 'Krankenhaus', array('class' => 'input-group-addon')) !!}
                                        @if(isset($company))
                                            {!! Form::select('company_id', $company_list, $company->id, array('class' => 'form-control select')) !!}
                                        @else
                                            {!! Form::select('company_id', $company_list, Auth::user()->department->company->id, array('class' => 'form-control select')) !!}
                                        @endif
                                    </div>

                                    <div class="input-group">
                                        <a href="{{ route('company.create')  }}" class="btn btn-success">Klinik hinzufügen</a>
                                    </div>

                                    @else

                                    <div class="input-group minwidth120">
                                        <span class="input-group-addon">Krankenhaus</span>
                                        <span class="form-control">{{Auth::user()->department->company->name}}</span>
                                    </div>
                                    {!! Form::hidden('company_id', Auth::user()->department->company->id) !!}

                                    @endif  

								</div>
							</div>
  						</div>
  						<div class="col-md-6 col-padding-top-5">
  							<div class="panel panel-default">
								<div class="panel-heading">Feiertage</div>

								<div class="panel-body">
                                    <div class="form-group">
                                        {!! Form::label('name', 'Name') !!}
                                        {!! Form::text('name', null, array('class' => 'form-control')) !!}
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            @foreach($customdates as $key => $c)
                                            <div class="input-group">
      								        	<div class="input-group-addon">
                                                    {!! Form::checkbox('customdate['.$c->id.']', 1, $companycustomdatesitems->contains($c->id)) !!} 
                                                </div>
      								        	<span class="form-control">
                                                    {{$c->name}}
      								        	</span>
                                                <small class="text-danger">{{ $errors->first('active') }}</small>
                                            </div>
                                            @if($key == 9)
                                            </div>
                                            <div class="col-md-6">
                                            @endif
                                            @endforeach
                                        </div>								
                                       
                                    </div>

                                        @if(isset($company))

                                            <div class="btn-group pull-right">
                                                {!! Form::submit('Speichern', ['class' => 'btn btn-info pull-right']) !!}
                                            </div>

                                        @else
        
                                            <div class="btn-group pull-right">
                                                {!! Form::submit("Hinzufügen & Speichern", ['class' => 'btn btn-info']) !!}
                                            </div>
        
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