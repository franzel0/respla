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

		//changing the department when updating selectform
		$('#company_id').on('change', function(e){
			var route = '{{ route("company.department.info.index", [":company", ":department"]) }}';
    		route = route.replace(':company', $('#company_id').val());
    		route = route.replace(':department', 0);
    		window.location.href = route;
		});

		$('#department_id').on('change', function(e){
			var route = '{{ route("company.department.info.index", [":company", ":department"]) }}';
    		route = route.replace(':company', $('#company_id').val());
    		route = route.replace(':department', $('#department_id').val());
    		window.location.href = route;
    	});

	});
</script>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Informationen über {{\App\Company::find($company_id)->name}}</h4>
				</div>

				<div class="panel-body">

					@include('admin.select_company_department')

					@if($department_id == -1)
					<h1>Es existiert keine Abteilung für diese Klinik!</h1>
					@else
						<h2>Company: {{\App\Company::find($company_id)->name}}, ID: {{\App\Company::find($company_id)->id}}</h2>
						<h3>Department: {{\App\Department::find($department_id)->name}}, ID: {{\App\Department::find($department_id)->id}}</h3>
						<hr>
						<h2>Einträge</h2>
						<table class="table table-bordered">
                    	    <tr>
                    	        <th>Name</th>
                    	        <th>Kürzel</th>
                    	        <th>ID</th>
                    	        <th>Hintergrund</th>
                    	        <th>Textfarbe</th>
                    	        <th>Department</th>
                    	        <th>Recht</th>
                    	        <th>anwesend</th>
                    	        <th>Wochende</th>
                    	    </tr>
                    	    @foreach(\App\Department::find($department_id)->entries->sortBy('name') as $e)
                    	    <tr>
                    	        <td>{{$e->name}}</td>
                    	        <td>{{$e->shorttext}}</td>
                    	        <td>{{$e->id}}</td>
                    	        <td>{{$e->bgcolor}}</td>
                    	        <td>{{$e->textcolor}}</td>
                    	        <td>{{$e->department->name}}</td>
                    	        <td>{{$e->right}}</td>
                    	        <td>{{$e->present}}</td>
                    	        <td>{{$e->onweekend}}</td>
                    	    </tr>
                    	    @endforeach
                    	</table>
						<hr>
						<h2>Positions</h2>
						<table class="table table-bordered">
							<tr>
								<th>Name</th>
								<th>ID</th>
								<th>Department</th>
								<th>Reihenfolge</th>
								<th>Aktiv</th>
							</tr>
							@foreach(\App\Department::find($department_id)->positions->sortBy('name') as $p)
							<tr>
								<td>{{$p->name}}</td>
								<td>{{$p->id}}</td>
								<td>{{$p->department->name}}</td>
								<td>{{$p->priority}}</td>
								<td>{{$p->active}}</td>
							</tr>
							@endforeach
						</table>
						<hr>
						<h2>Stationen</h2>
						<table class="table table-bordered">
							<tr>
								<th>Name</th>
								<th>Kürzel</th>
								<th>ID</th>
								<th>Department</th>
							</tr>
							@foreach(\App\Department::find($department_id)->sections->sortBy('fullname') as $p)
							<tr>
								<td>{{$p->fullname}}</td>
								<td>{{$p->shortname}}</td>
								<td>{{$p->id}}</td>
								<td>{{$p->department->name}}
							</tr>
							@endforeach
						</table>
						<hr>
						<h2>Ferien</h2>
						<table class="table table-bordered">
							<tr>
								<th>Name</th>
								<th>ID</th>
								<th>von</th>
								<th>bis</th>
								<th>Department</th>
							</tr>
							@foreach(\App\Department::find($department_id)->holidays->sortBy('name') as $p)
							<tr>
								<td>{{$p->name}}</td>
								<td>{{$p->id}}</td>
								<td>{{$p->date_from}}</td>
								<td>{{$p->date_to}}</td>
								<td>{{$p->department->name}}</td>
							</tr>
							@endforeach
						</table>
						<h2>User</h2>
						<table class="table table-bordered">
							<tr>
								<th>Name</th>
								<th>Vorname</th>
								<th>Nachame</th>
								<th>E-Mail</th>
								<th>ID</th>
								<th>Station</th>
								<th>Position</th>
								<th>Department</th>
								<th>Rolle</th>
							</tr>
							@foreach(\App\Department::find($department_id)->users->sortBy('name') as $u)
							<tr>
								<td>{{$u->name}}</td>
								<td>{{$u->firstname}}</td>
								<td>{{$u->lastname}}</td>
								<td>{{$u->email}}</td>
								<td>{{$u->id}}</td>
								<td>{{$u->section->shortname}}</td>
								<td>{{$u->position->name}}</td>
								<td>{{$u->department->name}}</td>
								<td>{{$u->roles()->first()->name}}</td>
							</tr>
							@endforeach
						</table>
					@endif	 
				</div>
			</div>
		</div>
	</div>
</div>
@endsection