@extends('app')

@section('links')
<link href="{{ asset('/css/login.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-5 col-md-offset-1 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">Was ist respla?</div>
				<div class="panel-body">
					<div class="well">
						<h3>Organisiere Mitarbeiter oder Mitglieder in einer Abteilung, Firma oder Verein einfach und übersichtlich</h3>
						<h3>Wie funktioniert's?</h3>
						<ul>
							<li>
								<p class="list_title">
									<span class="glyphicon glyphicon-user" aria-hidden="true"></span>An- und Abwesenheiten
								</p>
								<p class="list_body">Mit respla können Mitarbeiter in verschieden Abteilungen dargestellt werden</p>
							</li>
							<li>
								<p class="list_title">
									<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>Einfach eintragen
								</p>
								<p class="list_body">Ab- und Anwesenheiten könen einfach grafisch per Drag & Drop eingetragen werden. Für alles kann ein Grund oder eine Position oder 	sonstige Zuordnung festgelegt werden</p>
							</li>
							<li>
								<p class="list_title">
									<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>Praktische Ansichten
								</p>
								<p class="list_body">Darstellung in praktischer Monats-, Wochen- oder Tagesansicht</p>
							</li>
							<li>
								<p class="list_title">
									<span class="glyphicon glyphicon-th" aria-hidden="true"></span>Auswertungen
								</p>
								<p class="list_body">Jeder Benutzer kann Auswertungen seiner Einträge einsehen</p>
							</li>
							<li>
								<p class="list_title">
									<span class="glyphicon glyphicon-tags" aria-hidden="true"></span>Kommentare
								</p>
								<p class="list_body">Du kannst alle möglichen Kommentare hinzufügen</p>
							</li>
							<li>
								<p class="list_title">
									<span class="glyphicon glyphicon-picture" aria-hidden="true"></span>Feiertage und Ferienzeiten
								</p>
								<p class="list_body">Feiertage werden berücksichtigt; Ferien und andere Veranstaltungen können eingefügt werden</p>
							</li>
							<li>
								<p class="list_title">
									<span class="glyphicon glyphicon-sort-by-order" aria-hidden="true"></span>Sortieren & Filtern
								</p>
								<p class="list_body">Sortiere nach Anwesenheiten, Positionen und v. m.. Ansichten kannst Du filtern</li>
							<li>
								<p class="list_title">
									<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Genehmigte Einträge
								</p>
								<p class="list_body">Einträge (z. B. Urlaube oder Fortbildungen) können z. B. von Vorgesetzten genehmigt werden</p>
							</li>
						</ul>
						<h3><a href="{{ url('screenshots') }}">Screenshots</a></h3>
						<h3>Wie gehts?</h3>
						<p class="list_body" style="color: #34495e;font-size: 16px;">Einfach registrieren oder schreib uns eine E-Mail an <a href="mailto:kontakt@respla.de" target="_top">kontakt@respla.de</a>.</p> 
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-5 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">Login</div>
				<div class="panel-body">
					<div class="well">
						@if(Session::has('registered'))
                    	<div class="alert alert-info">
                    		{{ Session::get('registered') }}</p>
                    	</div>
                    	@endif
                    	@if(Session::has('verification'))
                    	<div class="alert alert-info">
                    		{{ Session::get('verification') }}</p>
                    	</div>
                    	@endif
						@if (count($errors) > 0)
							<div class="alert alert-danger">
								<strong>Whoops!</strong> Es gab Probleme mit der Anmeldung.<br><br>
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						<form class="form-horizontal" role="form" method="POST" action="/auth/login">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<div class="form-group">
								<label class="col-md-4 control-label">Login-Name</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="name" value="{{ old('name') }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Passwort</label>
								<div class="col-md-6">
									<input type="password" class="form-control" name="password">
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="remember"> Angemeldet bleiben
										</label>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary">Login</button>

									<a class="btn btn-link" href="{{ url('/password/email') }}">Passwort vergessen?</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
