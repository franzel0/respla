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

		//changing the department when updating selectform
		$('#company_id').on('change', function(e){
			var route = '{{ route("company.department.index", [":company"]) }}';
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
				<div class="panel-heading">Abteilung</div>
				<div class="panel-body">                
  					<div class="row">
						@if (isset($department))
                        {!! Form::model($department, ['method' => 'PATCH', 'route' => ['company.department.update', $company_id, $department_id]]) !!}
                        @else
                        {!! Form::open(['route' => ['company.department.store', $company_id]]) !!}
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
                                        {!! Form::select('company_id', $company_list, $company_id, array('class' => 'form-control select select2')) !!}
                                    </div>

                                    @else
                                    
                                    <div class="input-group minwidth120">
                                        <span class="input-group-addon">Krankenhaus</span>
                                        <span class="form-control">{{Auth::user()->department->company->name}}</span>
                                    </div>
                                    {!! Form::hidden('company_id', $company_id) !!}
                                    
                                    @endif

    								<div class="input-group">
    									<a href="{{ route('company.department.create', $company_id)  }}" class="btn btn-success">Abteilung hinzufügen</a>
    								</div>
   
    								@if(isset($department_list))
                                    <div class="input-group">
                                        <span class="input-group-addon">Filtern</span>
                                        {!! Form::text('search', null, array('id' => 'search', 'class' => 'form-control')) !!}
                                    </div>

                                    <ul  id="sortable">
                                    @foreach ($department_list as $dep)
                                        <li data-id="{{ $dep->id }}">
                                            <a href="{{ route('company.department.edit', [$company_id, $dep->id]) }}">{{ $dep->name }}</a>
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

                                    @if($errors->any())
                                        <div class="alert alert-danger">
                                            @foreach($errors->all() as $error)
                                                <p>{{ $error }}</p>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!--Check if a recordset is new ($create9 or to be updated-->
                                    @if (isset($department) || isset($create))
                                    @if (isset($department))
                                        {!! Form::model($department, ['method' => 'PATCH', 'route' => ['company.department.update', $company_id, $department->id]]) !!}
                                    @else
                                        {!! Form::open(['route' => 'company.department.store', $company_id]) !!}
                                    @endif
                                        <div class="form-group">
                                        {!! Form::label('name', 'Name') !!}
                                        {!! Form::text('name', null, array('class' => 'form-control')) !!}
                                        </div>
                                        <div class="form-group">

                                        </div>
                                        @if (Auth::user()->can('changehospital'))
                                        <?php
                                        $id = (isset($create)) ? null : Auth::user()->company_id;
                                        ?>
                                        <div class="form-group">
                                        {!! Form::label('company_id', 'Krankenhaus') !!}
                                        {!! Form::select('company_id', $selected_companies, null, array('class' => 'form-control')) !!}
                                        </div>
                                        @else
                                        {!! Form::hidden('company_id') !!}
                                        @endif
                                        <div class="checkbox">
                                            <label>
                                                @if (isset($department))
                                                {!! Form::checkbox('active', '1', $department->active) !!} Abteilung existiert
                                                @else
                                                {!! Form::checkbox('active', '1', true) !!} Abteilung existiert
                                                @endif
                                            </label>
                                        </div>
                                        
                                        {!! Form::submit('Anlegen / Ändern', array('class' => 'form-control btn btn-primary')) !!}
                                        
                                    {!! Form::close() !!}
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