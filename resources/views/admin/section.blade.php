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
    		/*$.ajax({
      			url: '/getdepartmentlist',
    		  	type: "post",
    		  	data: {'company_id':$(this).val(), '_token': $('#xtoken').text()},
    		  	success: function(data){
    		    	$('#department_id').empty();
    		    	$('#department_id').html(data);
    		  	}
    		});*/
			var route = '{{ route("company.department.section.index", [":company", ":department"]) }}';
    		route = route.replace(':company', $('#company_id').val());
    		route = route.replace(':department', 0);
    		window.location.href = route;
		});

		$('#department_id').on('change', function(e){
			var route = '{{ route("company.department.section.index", [":company", ":department"]) }}';
    		route = route.replace(':company', $('#company_id').val());
    		route = route.replace(':department', $('#department_id').val());
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
				<div class="panel-heading">Stationen / Sektionen</div>
				<div class="panel-body">					
  					<div class="row">
						@if (isset($section))
                        {!! Form::model($section, ['method' => 'PATCH', 'route' => ['company.department.section.update', $company_id, $department_id, $section->id]]) !!}
                        @else
                        {!! Form::open(['route' => ['company.department.section.store', $company_id, $department_id]]) !!}
                        @endif
  					
  						<div class="col-md-6 col-padding-top-5">
  							<div class="panel panel-default">
  							
								<div class="panel-heading">Station / Sektion</div>
								<div class="panel-body listitems">
									@if(Session::has('flash_message'))
								    <div class="alert alert-success">
								        {{ Session::get('flash_message') }}
								    </div>
									@endif
								  
                  @include('admin.select_company_department')
                  
                  <div class="input-group">
                    <a href="{{ route('company.department.section.create', [$company_id, $department_id])  }}" class="btn btn-success">Station hinzufügen</a>
                  </div>

                  @if(isset($sections))
                  <div class="input-group">
        							<span class="input-group-addon">Filtern</span>
    								{!! Form::text('search', null, array('id' => 'search', 'class' => 'form-control')) !!}
      							</div>

      							<ul  id="sortable">
									@foreach ($sections as $sec)
										<li data-id="{{ $sec->id }}">
											<a href="{{ route('company.department.section.edit', [$company_id, $department_id, $sec->id]) }}">{{ $sec->fullname }}</a>
										</li>
									@endforeach
									</ul>
									@endif	

								</div>
							</div>
  						</div>
  						<div class="col-md-6 col-padding-top-5">
  							<div class="panel panel-default">
								<div class="panel-heading">Detail</div>

								<div class="panel-body">

                                    @if(!isset($index))
                                    <div class="form-group">
                                        {!! Form::label('fullname', 'Name') !!}
                                        {!! Form::text('fullname', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                        <small class="text-danger">{{ $errors->first('fullname') }}</small>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('shortname', 'Kürzel') !!}
                                        {!! Form::text('shortname', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                        <small class="text-danger">{{ $errors->first('shortname') }}</small>
                                    </div>

                                    

                                    @if(isset($section))

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