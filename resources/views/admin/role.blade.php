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
				$( ".panel-body li" ).hide();
				$( ".panel-body li:contains('"+ x + "')" ).show();
			}
			else{
				$(".panel-body li").show();
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
				<div class="panel-heading">Rollen</div>
				<div class="panel-body">					
  					<div class="row">
  						<div class="col-md-6 col-padding-top-5">
  							<div class="panel panel-default">
								<div class="panel-heading">Rollen<span class='pull-right'>Suchen {!! Form::text('search', null, array('id' => 'search')) !!}</span></div>
								<div class="panel-body listitems">
									@if(Session::has('flash_message'))
									    <div class="alert alert-success">
									        {{ Session::get('flash_message') }}
									    </div>
									@endif
									@if ( !\App\Role::all()->count() )
        							<h2>Sie haben keine Rollen</h2>
    								@else
    								<ul>
    							    	<li><a href="{{ route('role.create') }}">Anlegen</a></li>
    								</ul>
    								<hr>
    							    <ul>
    							        @foreach( \App\role::all() as $role )
    							        <li>
											<a href="{{ route('role.edit', $role->id) }}">{{ $role->name }}</a>
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

									@if (isset($formrole) || isset($create))
									@if (isset($formrole))
  										{!! Form::model($formrole, ['method' => 'PATCH', 'route' => ['role.update', $formrole->id]]) !!}
  									@else
  										{!! Form::open(['route' => 'role.store']) !!}
  									@endif
   										
   										<div class="form-group">
   										{!! Form::label('name', 'Name') !!}
   										{!! Form::text('name', null, array('class' => 'form-control')) !!}
   										</div>
   										<div class="form-group">
   										{!! Form::label('display_name', 'Anzeigename') !!}
   										{!! Form::text('display_name', null, array('class' => 'form-control')) !!}
   										</div>
   										<div class="form-group">
   										{!! Form::label('description', 'Beschreibung') !!}
   										{!! Form::text('description', null, array('class' => 'form-control')) !!}
   										</div>
   										<div class="form-group">
   										{!! Form::label('permission[]', 'Rechte') !!}
   										@if (isset($formrole))
   										{!! Form::select('permission[]', \App\Permission::all()->lists('name','id'), $formrole->perms()->lists('id')->toArray(), array('multiple' => true, 'class' => 'form-control', 'size' => 10)) !!}
   										@else
   										{!! Form::select('permission[]', \App\Permission::all()->lists('name','id'), null, array('multiple' => true, 'class' => 'form-control', 'size' => 10)) !!}
   										@endif
   										</div>
   										{!! Form::submit('Anlegen / Ändern') !!}

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