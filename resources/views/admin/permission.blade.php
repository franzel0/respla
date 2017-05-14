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
				<div class="panel-heading">Rechte</div>
				<div class="panel-body">					
  					<div class="row">
  						<div class="col-md-6 col-padding-top-5">
  							<div class="panel panel-default">
								<div class="panel-heading">Rechte <span class='pull-right'>Suchen {!! Form::text('search', null, array('id' => 'search')) !!}</span></div>
								<div class="panel-body listitems">
									@if(Session::has('flash_message'))
									    <div class="alert alert-success">
									        {{ Session::get('flash_message') }}
									    </div>
									@endif
									@if ( !\App\Permission::all()->count() )
        							<h2>Sie haben keine Rechte</h2>
    								@else
    								<ul>
    							    	<li><a href="{{ route('permission.create') }}">Anlegen</a></li>
    								</ul>
    								<hr>
    							    <ul>
    							        @foreach( \App\Permission::all()->sortBy('name') as $permission )
    							        <li>
											<a href="{{ route('permission.edit', $permission->id) }}">{{ $permission->name }}</a>
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

									@if (isset($formpermission) || isset($create))
									@if (isset($formpermission))
  										{!! Form::model($formpermission, ['method' => 'PATCH', 'route' => ['permission.update', $formpermission->id]]) !!}
  									@else
  										{!! Form::open(['route' => 'permission.store']) !!}
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