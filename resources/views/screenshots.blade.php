@extends('app')

@section('links')
    <link href="{{ asset('/css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/lightbox.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/lightbox.min.js') }}"></script>
@endsection

@section('scripts')
<script>
$(document).ready(function(){

function el_height(){
    //inactivated as drag & drop does not work anymore with this function activated
    var h = $( window ).height();
    try {
        var p = $(".panel-body").offset();
        var height = h - p.top - 20 ;

        $(".screenshot-overflow").css("height", height);

    }
    catch(err){
        alert(err.message);
    }
}

/*
* Change height for element
*/
el_height();

/*
* Recompute the height fo divs when changing the size of the window
*/
$( window ).resize(function(){
  el_height();
    });

});

lightbox.option({
    'maxWidth': 300,
    'resizeDuration': 200,
    'wrapAround': true
    })

</script>

@endsection

@section('content')
<style>
.screenshot-overflow{
	overflow-y: scroll;
}
</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Screenshots
				</div>

				<div class="panel-body screenshot-overflow">
					<div class="well">

                        <div class="row">
                            <div class="col-md-12">
                                <h3>Monatsübersicht</h3>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ asset('/images/sc1.png') }}" data-lightbox="roadtrip" data-title="Übersichtliche Darstellung der Benutzer" class="example-image-link">
                                    <img src="{{ asset('/images/sc1.png') }}" class="img-responsive example-image" alt="Übersicht" width="400" data-lightbox="roadtrip" />
                                </a>
                            </div>
                            <div class="col-md-8 info_body">
                                Übersichtliche Darstellung aller Einträge in der Monatsansicht
                            </div>
                            <div class="col-md-12">
                                <h3>Auswählen</h3>
                            </div>
                            <div class="col-md-8 info_body">
                                In der Monatsansicht kannst Du problemlos durch einfaches Selektieren der einzelnen Tage Einträge hinzufügen
                            </div>
                            <div class="col-md-4">
                                <a href="{{ asset('/images/sc2.png') }}" data-lightbox="roadtrip" data-title="Einfaches Eintragen durch Drag 'n Drop">
                                    <img src="{{ asset('/images/sc2.png') }}" class="img-responsive example-image" alt="Eintragen" width="400" data-lightbox="roadtrip">
                                </a>
                            </div>
                            <div class="col-md-12">
                                <h3>Tagesansicht</h3>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ asset('/images/sc3.png') }}" data-lightbox="roadtrip" data-title="Die Tagesansicht zeigt übersichtlich die An- und Abwesenheiten für einen bestimmten Tag">
                                    <img src="{{ asset('/images/sc3.png') }}" class="img-responsive example-image" alt="Tagesansicht" width="400" data-lightbox="roadtrip">
                                </a>
                            </div>
                            <div class="col-md-8 info_body">
                                Jeder Tag kann einzeln angezeigt werden. Die Ansicht kann nach verschiedenen Kategorien sortiert werden. Ein Feld ermöglicht Dir, individuelle Kommentare hinzuzufügen.
                            </div>
                            <div class="col-md-12">
                                <h3>Planung</h3>
                            </div>
                            <div class="col-md-8 info_body">
                                In der Planungsansicht kann für einen bestimmten Eintrag oder Abwesenheitsgrund (z. B. Urlaub, Früdienst etc.) eine übersichtliche Planung durchgeführt werden. Jeder Benutzer kann sofort sehen, ob er andere Einträge oder Termine zu dem zu planenenden Zeitpunkt hat. Eine Statsik gibt Auskunft über die Anzahl der Einträge.
                            </div>
                            <div class="col-md-4">
                                <a href="{{ asset('/images/sc4.png') }}" data-lightbox="roadtrip" data-title="Planungsansicht (z. B. Urlaube)">
                                    <img src="{{ asset('/images/sc4.png') }}" class="img-responsive example-image" alt="PLanung" width="400" data-lightbox="roadtrip">
                                </a>
                            </div>
                            <div class="col-md-12">
                                <h3>Auswertung</h3>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ asset('/images/sc5.png') }}" data-lightbox="roadtrip" data-title="Individuelle Auswertung für jeden Benutzer">
                                    <img src="{{ asset('/images/sc5.png') }}" class="img-responsive example-image" alt="Auswertung" width="400" data-lightbox="roadtrip">
                                </a>
                            </div>
                            <div class="col-md-8 info_body">
                                Übersichtliche Darstellung der einzelenen Einträge für jeden Mitarbeiter.
                            </div>
                            <div class="col-md-12">
                                <h3>... und vieles mehr!</h3>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div> <!---->

@endsection

@section('scripts_end')
    <script src="{{ asset('/js/lightbox.min.js') }}"></script>
@endsection
