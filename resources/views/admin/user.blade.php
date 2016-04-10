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
			var route = '{{ route("company.department.user.index", [":company", ":department"]) }}';
    		route = route.replace(':company', $('#company_id').val());
    		route = route.replace(':department', 0);
    		window.location.href = route;
		});

		$('#department_id').on('change', function(e){
			var route = '{{ route("company.department.user.index", [":company", ":department"]) }}';
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
				<div class="panel-heading">Mitarbeiter</div>
				<div class="panel-body">					
  					<div class="row">
					    @if (isset($user))
                        {!! Form::model($user, ['method' => 'PATCH', 'route' => ['company.department.user.update', $company_id, $department_id, $user->id]]) !!}
                        @else
                        {!! Form::open(['route' => ['company.department.user.store', $company_id, $department_id]]) !!}
                        @endif
  					
  						<div class="col-md-6 col-padding-top-5">
  							<div class="panel panel-default">
  							
								<div class="panel-heading">Mitarbeiter</div>
								<div class="panel-body listitems">
									@if(Session::has('flash_message'))
								    <div class="alert alert-success">
								        {{ Session::get('flash_message') }}
								    </div>
									@endif

									@include('admin.select_company_department')

    							@if(Auth::user()->can('edituser'))	
                                <div class="input-group">
    									<a href="{{ route('company.department.user.create', [$company_id, $department_id])  }}" class="btn btn-success">Mitarbeiter hinzufügen</a>
    								</div>

    								@if(isset($users))
    								<div class="input-group">
        								<span class="input-group-addon">Filtern</span>
    									{!! Form::text('search', null, array('id' => 'search', 'class' => 'form-control')) !!}
      							</div>

                                <ul  id="sortable">
                                @foreach ($users as $u)
                                  <li data-id="{{ $u->id }}">
                                    <a href="{{ route('company.department.user.edit', [$company_id, $department_id, $u->id]) }}">{{ $u->fullname }}</a>
                                  </li>
                                @endforeach
                                </ul>
									  @endif
                                @endif <!--endif check edituser-->
								</div>
							</div>
  						</div>
  						<div class="col-md-6 col-padding-top-5">
  							<div class="panel panel-default">
								<div class="panel-heading">Detail</div>

								<div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('firstname', 'Vorname') !!}
                                                {!! Form::text('firstname', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                <small class="text-danger">{{ $errors->first('name') }}</small>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('name', 'Login-Name') !!}
                                                {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                <small class="text-danger">{{ $errors->first('name') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('lastname', 'Nachname') !!}
                                                {!! Form::text('lastname', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                <small class="text-danger">{{ $errors->first('name') }}</small>
                                            </div>
                        
                                            <div class="form-group">
                                                {!! Form::label('email', 'E-Mail') !!}
                                                {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                <small class="text-danger">{{ $errors->first('email') }}</small>
                                            </div>
                                        </div>
                                    </div>
                
                                    @if(Auth::user()->can('edituser'))
                                    
                                    @if(!isset($user))
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('password', 'Passwort') !!}
                                                {!! Form::text('password', 'secret', ['class' => 'form-control', 'required' => 'required']) !!}
                                                <small class="text-danger">{{ $errors->first('password') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('passwordrepeat', 'Passwort wiederholen') !!}
                                                {!! Form::text('passwordrepeat', 'secret', ['class' => 'form-control', 'required' => 'required']) !!}
                                                <small class="text-danger">{{ $errors->first('passwordrepeat') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('position_id', 'Position') !!}
                                                {!! Form::select('position_id', $position_list, null, ['class' => 'select2', 'required' =>    '    required']) !!}
                                                <small class="text-danger">{{ $errors->first('position_id') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('section_id', 'Station') !!}
                                                {!! Form::select('section_id', $section_list, null, ['class' => 'select2', 'required' => 'required   '])    !!}
                                                <small class="text-danger">{{ $errors->first('section_id') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                               <div class="input-group-addon">
                                                  {!! Form::checkbox('active', null, null, ['id' => 'active']) !!} 
                                                </div>
                                               <span class="form-control">
                                                Der Mitarbeiter ist aktiv
                                               </span>
                                                <small class="text-danger">{{ $errors->first('active') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
      				              			            <div class="input-group-addon">
                                                  {!! Form::checkbox('visible', null, null, ['id' => 'visible']) !!} 
                                                </div>
      				              			            <span class="form-control">
      				              			            	In Monat und Tag sichtbar
      				              			            </span>
                                                <small class="text-danger">{{ $errors->first('visible') }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="well well-sm">
                                        @if(Auth::user()->can('changeroles') || Auth::user()->can('changecompanyroles') || Auth::user()->can('changedepartmentroles'))
                                            <div class="input-group">
                                              <div class="form-group">
                                                  {!! Form::label('role_id', 'Rolle / Berechtigung') !!}
                                                  {!! Form::select('role_id', $roles, $userrole, ['class' => ' select2', 'required' => 'required']) !!}
                                                  <small class="text-danger">{{ $errors->first('role') }}</small>
                                              </div>
                                            </div>
                                        @else
                                            <div class="input-group">
                                              <div class="input-group-addon">
                                                  Berechtigung 
                                              </div>
                                              <span class="form-control">
                                                  {{Auth::user()->ownrole->display_name}}  
                                              </span>
                                              {!! Form::hidden('role_id', Auth::user()->ownrole->role_id) !!}
                                            </div>
                                        @endif <!--changeroles-->
                                    </div>
                                    @else
                                    <div class="input-group minwidth120">
                                        <span class="input-group-addon">Position</span>
                                        <span class="form-control">{{Auth::user()->position->name}}</span>
                                    </div>
                                    <div class="input-group minwidth120">
                                        <span class="input-group-addon">Station</span>
                                        <span class="form-control">{{Auth::user()->section->fullname}}</span>
                                    </div>
                                    @if(Auth::user()->active)
                                    <div class="input-group minwidth120">
                                        <span class="input-group-addon">Sichtbar</span>
                                        <span class="form-control">... in Monat und Tag</span>
                                    </div>
                                    @endif
                                    {!! Form::hidden('department_id', $department_id) !!}
                                    @endif <!--edituser-->
                                    
                                    @if(isset($user))
                                        {!! Form::submit('Speichern', ['class' => 'btn btn-info pull-right']) !!}
                    
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