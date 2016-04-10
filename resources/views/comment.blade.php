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
			var route = '{{ route("company.department.comment.index", [":company", ":department"]) }}';
    		route = route.replace(':company', $('#company_id').val());
    		route = route.replace(':department', 0);
    		window.location.href = route;
		});

		$('#department_id').on('change', function(e){
			var route = '{{ route("company.department.comment.index", [":company", ":department"]) }}';
    		route = route.replace(':company', $('#company_id').val());
    		route = route.replace(':department', $('#department_id').val());
    		window.location.href = route;
    	});
        //datepicker
        $.datepicker.setDefaults({
            renderer: $.ui.datepicker.defaultRenderer,
            monthNames: ['Januar','Februar','März','April','Mai','Juni',
            'Juli','August','September','Oktober','November','Dezember'],
            monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
            'Jul','Aug','Sep','Okt','Nov','Dez'],
            dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
            dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
            dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            prevText: '&#x3c;zurück', prevStatus: '',
            prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
            nextText: 'Vor&#x3e;', nextStatus: '',
            nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
            currentText: 'heute', currentStatus: '',
            todayText: 'heute', todayStatus: '',
            clearText: '-', clearStatus: '',
            closeText: 'schließen', closeStatus: '',
            yearStatus: '', monthStatus: '',
            weekText: 'Wo', weekStatus: '',
            dayStatus: 'DD d MM',
            defaultStatus: ''
        });

        $( ".from" ).datepicker({
            minDate: 0,
            onSelect: function (date) {
                var date = $('.from').datepicker('getDate');
                $('.to').datepicker('setDate', date);
                //sets minDate to to date
                $('.to').datepicker('option', 'minDate', date);
            }
        });

        $( ".to" ).datepicker();

	});
</script>
@endsection

@section('content')
<div id='xtoken' class="hidden">{!! csrf_token() !!}</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Anmerkungen zur Abteilung</div>
				<div class="panel-body">					
  					<div class="row">
						@if (isset($comment))
                        {!! Form::model($comment, ['method' => 'PATCH', 'route' => ['company.department.comment.update', $company_id, $department_id, $comment->id]]) !!}
                        @else
                        {!! Form::open(['route' => ['company.department.comment.store', $company_id, $department_id]]) !!}
                        @endif
  					
  						<div class="col-md-6 col-padding-top-5">
  							<div class="panel panel-default">
  							
								<div class="panel-heading">Ferien</div>
								<div class="panel-body listitems">
									@if(Session::has('flash_message'))
								    <div class="alert alert-success">
								        {{ Session::get('flash_message') }}
								    </div>
									@endif

									@include('admin.select_company_department')

    								<div class="input-group">
    									<a href="{{ route('company.department.comment.create', [$company_id, $department_id])  }}" class="btn btn-success">Kommentar hinzufügen</a>
    								</div>
   
    								@if(isset($holiday_list))
    								<div class="input-group">
        								<span class="input-group-addon">Filtern</span>
    									{!! Form::text('search', null, array('id' => 'search', 'class' => 'form-control')) !!}
      								</div>

      								<ul  id="sortable">
									@foreach ($holiday_list as $hol)
										<li data-id="{{ $hol->id }}">
											<a href="{{ route('company.department.comment.edit', [$company_id, $department_id, $hol->id]) }}">{{ $hol->name }},  {{date("Y", strtotime($hol->date_from))}}</a>
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
                                        <div class="form-group">
                                            {!! Form::label('name', 'Name') !!}
                                            {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                            <small class="text-danger">{{ $errors->first('name') }}</small>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group bgcolor">
                                                    <span class="input-group-addon">Von...</span>
                                                        @if (isset($comment))
                                                        {!! Form::text('date_from', date("d.m.Y", strtotime($comment->date_from)), ['class' => 'form-control from', 'required' => 'required']) !!}
                                                        @else
                                                        {!! Form::text('date_from', null, ['class' => 'form-control from', 'required' => 'required']) !!}
                                                        @endif
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>
                                                <small class="text-danger">{{ $errors->first('date_from') }}</small>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group bgcolor">
                                                    <span class="input-group-addon">Von...</span>
                                                        @if (isset($comment))
                                                        {!! Form::text('date_to', date("d.m.Y", strtotime($comment->date_to)), ['class' => 'form-control to', 'required' => 'required']) !!}
                                                        @else
                                                        {!! Form::text('date_to', null, ['class' => 'form-control to', 'required' => 'required']) !!}
                                                        @endif
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>
                                                <small class="text-danger">{{ $errors->first('date_to') }}</small>
                                            </div>
                                        </div>
                                        
                                        @if(isset($comment))
    
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