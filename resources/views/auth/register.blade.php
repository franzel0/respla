@extends('app')

@section('links')
<!-- additional files-->
<link href="{{ asset('/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('/css/register.css') }}" rel="stylesheet">
<script src="{{ asset('/js/bootstrap-colorpicker.min.js') }}"></script>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
	$('.bgcolor').colorpicker({
		format: 'hex'
	});        
        
    $('.textcolor').colorpicker({
    	format: 'hex'
    }); 
})
</script>
@endsection

@section('content')
<style>
.well-cream{
	background-color: #F8F8FF;
}
.bottomMargin{
	margin-bottom: 10px !important;
}
.form-group{
	margin-right: 20px;
}
</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1 col-md-12 col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">Registrieren</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>
							Whoops! Es gab Probleme bei der Eingabe!<br>
							Haben Sie alle Felder ausgefüllt?
							</strong>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-inline" role="form" method="POST" action="{{ url('/auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="well">
							<div class="row">
								<div class="alert alert-info" role="alert">
								Zunächst müssen Sie eine Firma oder Einrichtung anlegen! Wenn Sie angemeldet sind, sollten Sie für die Einrichtung Feiertage 	festlegen. Ebenfalls können weitere Abteilungen, Unterabteilungen und Positionen hinzugefügt werden. 
								</div>
								
								<!--Company & Department-->
								<div class="col-md-12 well well-cream">
									<div class="form-group bottomMargin @if($errors->first('company_name')) has-error @endif">
  									  	<label for="company_name">Name der Organisation</label>
										<input type="text" class="form-control" name="company_name" id="company_name" size="30" value="{{ old('company_name') }}">
  									</div>
  									<div class="form-group bottomMargin @if($errors->first('department_name')) has-error @endif">
  									  	<label for="department_name">Abteilung / Bereich</label>
										<input type="text" class="form-control" name="department_name" id="department_name" size="30" value="{{ old('department_name') }}">
  									</div>
  									<p class="text-info">Die Organisation, Firma oder der Verein muss einen Namen haben. Anschließend geben Sie bitte eine Abteilung oder Bereich ein. Dieser Abteilung könnnen Sie später Mitarbeiter/ Mitglieder hinzufügen. Sie können natürlich beliebig viele Abteilungen zu einer Organisation hinzufügen!</p>
								</div>
			
								<!--section-->
								<div class="col-md-12 well well-cream">
									<div class="form-group bottomMargin @if($errors->first('section_fullname')) has-error @endif">
										<label for="section_fullname"> Name der Sektion / Station / Unterabteilung: </label>
										<input type="text" class="form-control" name="section_fullname" id="section_fullname" size="30" value="{{ old('section_fullname') }}">
									</div>
									<div class="form-group bottomMargin @if($errors->first('section_shortname')) has-error @endif">
										<label for="section_shortname">Kürzel</label>
										<input type="text" class="form-control" name="section_shortname" id="section_shortname" size="6" value="{{ old('section_shortname') }}">
									</div>
									<p class="text-info">Die Organisation kann in den einzelnen Abteilungen / Departments noch einmal Unterbereiche oder Sektionen haben, die einzelnen Mitarbeiter zugewiesen sind (z.B. bestimmte Arbeitsplätze).</p>
								</div>
	
								<!--position-->
								<div class="col-md-12 well well-cream">
									<div class="form-group bottomMargin @if($errors->first('position_name')) has-error @endif">
										<label for="position">Position</label>
										<input type="text" class="form-control" name="position_name" value="{{ old('position_name') }}">
									</div>
									<p class="text-info">Mit Position kann z.B. die hierarchische Stellung innerhalb einer Gruppe beschrieben werden. Nach der Position kann in einigen Ansichten gefiltert oder sortiert werden.</p>	
								</div>							
	
								<!--entry-->
								<div class="col-md-12 well well-cream">
									<div class="row">
										<div class="col-md-12">
											<h5>Grund / Eintrag für Ab- und Anwesenheiten</h5>
										</div>

										<div class="col-md-12">	
											<div class="form-group bottomMargin @if($errors->first('entry_name')) has-error @endif">
												<label for="entry_name">Abwesenheitsgrund</label>
												<input type="text" class="form-control" name="entry_name" value="{{ old('entry_name') }}">
											</div>
											<div class="form-group bottomMargin @if($errors->first('entry_shorttext')) has-error @endif">
												<label for="entry_shorttext">Kürzel</label>
												<input type="text" class="form-control" name="entry_shorttext" value="{{ old('entry_shorttext') }}">
											</div>
										</div>
										
										<div class="col-md-12"> 
                        	    			<div class="input-group bgcolor @if($errors->first('bgcolor')) has-error @endif">
                        	    				<span class="input-group-addon">Hintergrundfarbe</span>
                        	    				{!! Form::text('bgcolor', null, ['class' => 'form-control ', 'required']) !!}
											    <span class="input-group-addon"><i><span class="glyphicon glyphicon-tint" aria-hidden="true"></span></i></span>
											</div>

                        	    			<div class="input-group textcolor @if($errors->first('textcolor')) has-error @endif">
                        	    				<span class="input-group-addon">Textfarbe</span>
                        	    				{!! Form::text('textcolor', null, ['class' => 'form-control ', 'required']) !!}
											    <span class="input-group-addon"><i><span class="glyphicon glyphicon-tint" aria-hidden="true"></span></i></span>
											</div>

                        	    			<div class="input-group  @if($errors->first('present')) has-error @endif">
    										  	<span class="input-group-addon">
                        	    			        {!! Form::checkbox('present', 1, null, ['id' => 'present']) !!}
    										  	</span>
    										  	<span class="form-control" title="Der Eintrag bedeutet, das der User anwesend ist">Anwesend</span>
                        	    			    <small class="text-danger">{{ $errors->first('present') }}</small>
    										</div>

    										<div class="input-group @if($errors->first('right')) has-error @endif">
    										  	<span class="input-group-addon">
                        	    			        {!! Form::checkbox('right', 1, null, ['id' => 'right']) !!}
    										  	</span>
    										  	<span class="form-control">Freigaberechte erforderlich</span>
    										</div>

    										<div class="input-group @if($errors->first('onweekend')) has-error @endif">
    										  	<span class="input-group-addon">
                        	    			    	{!! Form::checkbox('onweekend', 1, null, ['id' => 'onweekend']) !!} 
    										  	</span>
    										  	<span class="form-control">An Wochenenden</span>
    										</div>
											<p class="text-info">Einen Grund für eine Abwesenheit / Anwesenheit benötigen Sie, um zu beginnen. Später können beliebig viele Gründen / Einträge hinzugefügt werden. Soll der Eintrag auch an Wochenenden und Feiertagen möglich sein? Und müssen die Einträge (z. B. Urlaub) noch einmal genehmigt oder freigegeben werden? Diese Recht können Sie auf einzelne Mitarbeiter beschränken.</p>	
										</div>
									</div>
								</div>

                            </div>
						</div>
                        	    
						<!--User-->
						<div class="well">
							<div class="row">
								<div class="col-md-12 alert alert-info" role="alert">
									Und jetzt die Benutzerdaten!<br>
									Dieser Benutzer ist automatisch der Administrator für die ganze Einrichtung. Er kann neue Mitarbeiter und Abteilungen anlegen.
								</div>
								<div class="col-md-6 well-cream">
									<br>
									<div class="form-group width100 bottomMargin @if($errors->first('name')) has-error @endif">
										<label class="width200p" for="name">Login-Name</label>
										<input type="text" class="form-control" name="name" value="{{old('name')}}">
									</div>

									<div class="form-group width100 bottomMargin @if($errors->first('lastname')) has-error @endif">
										<label class="width200p" for="lastname">Nachname</label>
										<input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}">
									</div>
	
									<div class="form-group width100 bottomMargin @if($errors->first('firstname')) has-error @endif">
										<label class="width200p" or="firstname">Vorname</label>
										<input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}">
									</div>
								</div>
								<div class="col-md-6 well-cream">
								<br>
									<div class="form-group width100 bottomMargin @if($errors->first('email')) has-error @endif">
										<label class="width200p" for="email">E-Mail Addresse</label>
										<input type="email" class="form-control" name="email" value="{{ old('email') }}">
									</div>
	
									<div class="form-group width100 bottomMargin @if($errors->first('password')) has-error @endif">
										<label class="width200p" for="password">Passwort</label>
										<input type="password" class="form-control" name="password">
									</div>
	
									<div class="form-group width100 bottomMargin @if($errors->first('password_confirmation')) has-error @endif">
										<label class="width200p" for="password_confirmation">Passwort bestätigen</label>
										<input type="password" class="form-control" name="password_confirmation">
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-1 col-md-offset-10">
								<button type="submit" class="btn btn-primary">
									Registrieren
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
