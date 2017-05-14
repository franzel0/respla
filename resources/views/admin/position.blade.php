@extends('app')

@section('scripts')
<!--Add the token for AJAX-rquests-->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
	$( document ).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

		$.expr[":"].contains = $.expr.createPseudo(function(arg) {
			return function( elem ) {
				return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
			};
		});
		
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
			var route = '{{ route("company.department.position.index", [":company", ":department"]) }}';
    		route = route.replace(':company', $('#company_id').val());
    		route = route.replace(':department', 0);
    		window.location.href = route;
		});

		$('#department_id').on('change', function(e){
			var route = '{{ route("company.department.position.index", [":company", ":department"]) }}';
    		route = route.replace(':company', $('#company_id').val());
    		route = route.replace(':department', $('#department_id').val());
    		window.location.href = route;
    	});

    	//sort the positions
    	$( "#sortable" ).sortable({
    			update: function(event, ui) { 
    				var $positions = $( "li" ).map(function() {
    					return $(this).data("id");
  					})
  					.get();
            
  					$.ajax({
      					url: '/changeOrder',
    				  	type: "post",
    				  	data: {
                  'company_id': $('#company_id').val(),
                  'department_id': $('#department_id').val(),
                  'positions':$positions},
    				  	success: function(data){
    				    	 alert('Reihenfolge geändert!'); 
    				  }
    				}); 
    			
    			}
    			
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
				<div class="panel-heading">Abteilung</div>
				<div class="panel-body">					
  					<div class="row">
						@if (isset($position))
                        {!! Form::model($position, ['method' => 'PATCH', 'route' => ['company.department.position.update', $company_id, $department_id, $position->id]]) !!}
                        @else
                        {!! Form::open(['route' => ['company.department.position.store', $company_id, $department_id]]) !!}
                        @endif
  					
  						<div class="col-md-6 col-padding-top-5">
  							<div class="panel panel-default">
  							
								<div class="panel-heading">Abteilung</div>
								<div class="panel-body listitems">
									@if(Session::has('flash_message'))
								    <div class="alert alert-success">
								        {{ Session::get('flash_message') }}
								    </div>
									@endif

									@include('admin.select_company_department')

    								<div class="input-group">
    									<a href="{{ route('company.department.position.create', [$company_id, $department_id])  }}" class="btn btn-success">Position hinzufügen</a>
    								</div>

    								@if(isset($positions))
    								<div class="input-group">
        								<span class="input-group-addon">Filtern</span>
    									{!! Form::text('search', null, array('id' => 'search', 'class' => 'form-control')) !!}
      								</div>

      								<ul  id="sortable">
									@foreach ($positions as $pos)
										<li data-id="{{ $pos->id }}">
											<span class="ui-icon ui-icon-arrowthick-2-n-s" style="float:left;">
											</span>
											<a href="{{ route('company.department.position.edit', [$company_id, $department_id, $pos->id]) }}">{{ $pos->name }}</a>
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
                                        {!! Form::label('name', 'Name') !!}
                                        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                        <small class="text-danger">{{ $errors->first('name') }}</small>
                                    </div>

                                    <div class="input-group">
      									<div class="input-group-addon">
                                            {!! Form::checkbox('active', null, null, ['id' => 'active']) !!} 
                                        </div>
      									<span class="form-control">
      										Die Position ist aktiv
      									</span>
                                        <small class="text-danger">{{ $errors->first('active') }}</small>
                                    </div>

                                    @if(isset($position))

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