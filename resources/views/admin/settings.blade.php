@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Rollen und Rechte<br><a href="{{ url('/entrust') }}">Entrust</a></div>
				<div class="panel-body">
  					<table class="table table-bordered">
  					<thead>
  						<tr>
  							<th>
  								Rolle
  							</th>
  							<th>
  								Rolle ID
  							</th>
							<th>
  								permission
  							</th>
  							<th>
  								permission ID
  							</th>
  							<th>
  								permission display name
  							</th>
  						</tr>
  					</thead>

  					@foreach(\App\Role::all() as $role)
					     <tr>
					     	<th>{{$role->name}}</th><th>{{$role->id}}</th>
					     	
					     </tr>
					     @foreach($role->perms as $perms)
					     	<tr>
					     		<td>
					     		</td>
					     		<td>
					     		</td>
					     		<td>
					     			{{$perms->name}} 
					     		</td>
					     		<td>
					     			{{$perms->id}}
					     		</td>
					     		<td>
					     			{{$perms->display_name}}
					     		</td>
					     	</tr>
					     @endforeach
					@endforeach
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection