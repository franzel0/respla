@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Kliniken / einrichtungen</div>

				<div class="panel-body">
					<h1>Hallo, {{ Auth::user()->name }}</h1>
					<h2>Du arbeitest in der {{\App\Department::find(Auth::user()->department->id)->company->name}}</h2>
					@foreach(\App\Company::all() as $company)
					        <p>{{ $company->name }}</p>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>
@endsection