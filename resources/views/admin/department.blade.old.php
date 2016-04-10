@extends('app')

@section('scripts')
<script>
	$( document ).ready(function() {
		$.expr[":"].contains = $.expr.createPseudo(function(arg) {
			return function( elem ) {
				return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
			};
		});
		
		$("#search").keyup(function(){
			var x = $(this).val();
			if (x != "") {
				$( ".panel-body tr" ).hide();
				$( ".panel-body tr:contains('"+ x + "')" ).show();
			}
			else{
				$(".panel-body tr").show();
			}
		});
	});
</script>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Abteilung</div>
				<div class="panel-body">					
  					<div class="row">
  						<div class="col-md-6 col-padding-top-5">
  							<div class="panel panel-default">
								<div class="panel-heading">Abteilungen</div>
								<div class="panel-body listitems">
									@if(Session::has('flash_message'))
									    <div class="alert alert-success">
									        {{ Session::get('flash_message') }}
									    </div>
									@endif
									@if ( !\App\Department::all()->count() )
        							<h2>Sie haben keine Rechte</h2>
    								@else
    								<?php
									$selected_departments = (Auth::user()->hasRole('admin')) ? \App\Department::all() : \App\Department::company()->where('company_id', '=', Auth::user()->company_id)->get();
									$selected_companies =(Auth::user()->hasRole('admin')) ? \App\Company::all()->lists('name','id') : \App\Company::where('id', '=', Auth::user()->company_id)->lists('name','id');
									?>
    								<ul>
    							    	<li><a href="{{ route('department.create') }}">Anlegen</a></li>
    								</ul>
    								<hr>
    								<div class="input-group">
        								<span class="input-group-addon">Filtern</span>
    									{!! Form::text('search', null, array('id' => 'search', 'class' => 'form-control')) !!}
      								</div>
      								<table class="table table-striped table-hover">
      								<col width="50%">
  									<col width="50%">
    							        @foreach($selected_departments  as $department)
    							        <tr>
    							        	<td>
												<a href="{{ route('department.edit', $department->id) }}">{{ $department->name }}</a>
											</td>
											<td>
												<a href="{{ route('department.edit', $department->id) }}">{{ $department->company['name'] }}</a>
											</td>
										</tr>
										@endforeach
									</table>
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
									@if (isset($formdepartment) || isset($create))
									@if (isset($formdepartment))
  										{!! Form::model($formdepartment, ['method' => 'PATCH', 'route' => ['department.update', $formdepartment->id]]) !!}
  									@else
  										{!! Form::open(['route' => 'department.store']) !!}
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
												@if (isset($formdepartment))
												{!! Form::checkbox('active', '1', $formdepartment->active) !!} Abteilung existiert
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
  					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection