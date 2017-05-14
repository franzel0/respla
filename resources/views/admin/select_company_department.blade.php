@if(Auth::user()->can('changecompany'))

<div class="input-group minwidth120">
	{!! Form::label('company_id', 'Krankenhaus', array('class' => 'input-group-addon')) !!}
	{!! Form::select('company_id', \App\Company::orderBy('name')->lists('name', 'id'), $company_id, array('class' => 'form-control select select2')) !!}
</div>
@else
<div class="input-group minwidth120">
	<span class="input-group-addon">Krankenhaus</span>
	<span class="form-control">{{Auth::user()->department->company->name}}</span>
</div>
{!! Form::hidden('company_id', $company_id, ['id' => 'company_id']) !!}
@endif

@if(Auth::user()->can('changedepartment'))
<div class="input-group minwidth120">
	{!! Form::label('department_id', 'Abteilung', array('class' => 'input-group-addon')) !!}
	{!! Form::select('department_id', \App\Company::find($company_id)->departments->sortBy('name')->lists('name', 'id'), $department_id, array('class' => 'form-control select select2'))!!}
	<small class="text-danger">{{ $errors->first('active') }}</small>
</div>
@else
<div class="input-group minwidth120">
	<span class="input-group-addon">Abteilung</span>
	<span class="form-control">{{Auth::user()->department->name}}</span>
</div>
{!! Form::hidden('department_id', $department_id, ['id' => 'department_id']) !!}
@endif

<hr>
    								 