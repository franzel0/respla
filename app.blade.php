<!DOCTYPE html>
<html lang="de">
    <head>
    	<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	@yield('headercsrf')
    	<title>respla @if (Auth::check())- {{Auth::user()->company->name}} @endif</title>

    	<!--styles-->
    	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/styles.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/jquery-ui.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/jquery-ui.theme.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/select2-bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/select2.css') }}" rel="stylesheet">

    	<!-- Fonts -->
    	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

        @yield('css')

        <!--scripts-->
        <script src="{{ asset('/js/jquery-1.11.1.min.js') }}"></script>
        <script src="{{ asset('/js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('/js/select2.min.js') }}"></script>
        <script src="{{ asset('/js/jquery.ui.touch-punch.min.js') }}"></script>
        <!--<script src="{{ asset('/js/html5shiv.js') }}"></script>
        <script src="{{ asset('/js/respond.js') }}"></script>-->

        @yield('links')


        <script type="text/javascript">

        $(document).ready(function(){

            //datepicker settings
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

            $(".select2").select2();

            /*
             * scrolling the differnet divs in month view
             */
            $("#right-bottom").scroll(function () {
                $("#right-top").scrollLeft($(this).scrollLeft());
                $("#left-bottom").scrollTop($(this).scrollTop());
            });

            $("#right-top").scroll(function () {
                $("#right-bottom").scrollLeft($(this).scrollLeft());
            });

            $("#left-bottom").scroll(function () {
                $("#right-bottom").scrollTop($(this).scrollTop());
            });

        })
        </script>

        @yield('scripts')


        @yield('token')
    	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    	<!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
    	<!--[if lt IE 9]>
    		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    	<![endif]-->
    </head>
    <body>
    	<nav class="navbar navbar-default">
    		<div class="container-fluid">
    			<div class="navbar-header">
    				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
    					<span class="sr-only">Toggle Navigation</span>
    					<span class="icon-bar"></span>
    					<span class="icon-bar"></span>
    					<span class="icon-bar"></span>
    				</button>
    				@if (Auth::check())
    				<a class="navbar-brand" href="/">respla beta @if(!\Request::route()->getName()=='overviewoncalls') - {{ Auth::user()->department->name }}@endif</a>
    				@else
    				<a class="navbar-brand" href="/">respla beta</a>
    				@endif
    			</div>

    			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    				@if (Auth::check())

    				<ul class="nav navbar-nav">
                        <li><a href="{{ route('overviewOncalls', [Auth::user()->company->id])}}">Gemeinsame Dienste</a></li>
    					<li><a href="{{ url('/month') }}">Monat</a></li>
    					<li><a href="{{ url('/week') }}">Woche</a></li>
                        <li><a href="{{ url('/day') }}">Tag</a></li>
                        <li><a href="{{ url('/test') }}">test</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Planung..<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('/planDepartment') }}">.. innerhalb der Abteilung</a></li>
                                <li><a href="{{ url('/planCompany') }}">.. übergreifend</a></li>
                            </ul>
                        </li>
    					<li><a href="{{ url('stats') }}">Stats</a></li>
    				</ul>

                    <ul class="nav navbar-nav pull-right">
                    	<li class="dropdown ">
    						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Benutzer {{ Auth::user()->name }}<span class="caret"></span></a>
    						<ul class="dropdown-menu" role="menu">
    							<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
    						    <li><a href="{{ route('password.index') }}">Passwort ändern</a></li>
    							@if (Auth::user()->can('editdepartmentsettings'))
    							<li><a href="{{ route('company.department.user.index', [Auth::user()->department->company->id, Auth::user()->department_id]) }}">Mitarbeiter</a></li>
    						    @else
    							<li>
                                    <a href="{{ route('company.department.user.edit', [Auth::user()->department->company->id, Auth::user()->department_id, Auth::user()->id]) }}">
                                        Eigene Daten
                                    </a>
                                </li>
                                @endif

                                @permission('changedepartment')
                                <li class="divider" role="separator"></li>
                                <li><a href="{{ route('company.show', [Auth::user()->company->id]) }}">Klinik</a></li>
                                <li><a href="{{ route('company.department.index', [Auth::user()->department->company->id]) }}">Abteilung</a></li>
                                @endpermission

                                @permission('editoncalls')
                                <li><a href="{{ route('company.oncall.index', [Auth::user()->department->company->id]) }}">Gemeinsame Dienste</a></li>
                                <li><a href="{{ route('company.oncallssorder', [Auth::user()->department->company->id]) }}">Dienstübersicht</a></li>
                                @endpermission

                                @permission('editdepartmentsettings')
                                <li><a href="{{ route('company.department.position.index', [Auth::user()->department->company->id, Auth::user()->department_id])}}">Positionen</a></li>
                                <li><a href="{{ route('company.department.section.index', [Auth::user()->department->company->id, Auth::user()->department_id])}}">Stationen</a></li>
                                <li><a href="{{ route('company.department.holiday.index', [Auth::user()->department->company->id, Auth::user()->department_id])}}">Ferien</a></li>
                                <li><a href="{{ route('company.department.entry.index', [Auth::user()->department->company->id, Auth::user()->department_id])}}">Einträge</a></li>
                                <li><a href="{{ route('company.department.info.index', [Auth::user()->department->company->id, Auth::user()->department_id]) }}">Klinik-Info</a></li>
                                @endpermission
                                
                                @role('admin')
                                <li><a href="{{ url('/admin/settings') }}">Rights&Perms</a></li>
    							<li><a href="{{ route('role.index') }}">Rollen</a></li>
    							<li><a href="{{ route('permission.index') }}">Rechte</a></li>
                                <li><a href="{{ url('/start') }}">Demo-Einrichtungen</a></li>
                                @endrole
    						</ul>
    					</li>
                    </ul>
                    @endif
                    @if (Auth::guest())
                        <ul class="nav navbar-nav pull-right">
                            <li><a href="{{ url('auth/register') }}">Registrieren</a></li>
                            <li><a href="{{ url('screenshots') }}">Screenshots</a></li>
                            <li><a href="{{ url('demo') }}">Demo</a></li>
                            <li><a href="{{ route('impressum') }}">Impressum</a></li>
                        </ul>
                    @endif
    			</div>
    		</div>
    	</nav>

    	@yield('content')

    	@yield('modal')

    	<!-- Scripts -->
    	<script src="{{ asset('/js/bootstrap.min.js') }}"></script>

        @yield('scripts_end')

    </body>
</html>
