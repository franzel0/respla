@extends('app')

@section('scripts')
<!-- additional files-->
<link href="{{ asset('/css/orderoncalls.css') }}" rel="stylesheet">
<script src="{{ asset('/js/orderoncalls.js') }}"></script

<!--Add the token for AJAX-rquests-->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Reihenfolge der Dienste ändern</div>
				<div class="panel-body">
  					<div class="row">
						<div class="col-md-6 col-padding-top-5">
							<h4>Vorhandene Einträge / Dienste <button id="resort" class="btn btn-sm btn-info pull-right">Neu sortieren</button></h4>
  							<ul id="sortable1" class="connectedSortable">
                                @foreach($oncallsall as $key => $oncall)
                                    <li data-id="{{$oncall->id}}">{{$oncall->name}} |
                                        @if(isset($oncall->companyname)) Krankenhaus {{$oncall->companyname}}
                                        @else Abteilung {{$oncall->departmentname}}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
  						</div>
						<div class="col-md-6 col-padding-top-5">
							<h4>Sichtbare Einträge / Dienst</h4>
  							<ul id="sortable2" class="connectedSortable">
                                @foreach($oncallsvisible as $key => $oncall)
                                    <li data-id="{{$oncall->id}}">{{$oncall->name}} |
                                        @if(isset($oncall->companyname)) Krankenhaus {{$oncall->companyname}}
                                        @else Abteilung {{$oncall->departmentname}}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
  						</div>
  					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
