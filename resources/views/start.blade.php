<?php
namespace App\Http\Controllers;
Use DB;
use Auth;
use Carbon\Carbon;
use App\Classes\lists;

?>

@extends('app')

@section('links')
	<link href="{{ asset('/css/lightbox.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/lightbox.min.js') }}"></script>
@endsection

@section('content')
<style>
table{
	table-layout: fixed;
}
.drop{
	height: 31px;
}
.drag{
	border: 1px solid lightgrey;
	background-color: #CC9999;
	border-radius: 12px;
	padding: 4px;
}
</style>
<script>
$(document).ready(function(){
	function makeDraggable(el)
	{
		el.draggable({
			helper: "clone",
			append: "body",
    	  	zIndex: 10
		})
	}

	makeDraggable($(".drag"));

	$(".drop").droppable({
		accept: ".drag",
		drop: function(event, ui){
      		if($(ui.draggable).closest("table").attr("id") == "target")
      		{
      			var element = ui.draggable;
      		}
      		else
      		{
        		var element=$(ui.draggable).clone();
      		}
    		$(this).append(element);
    		makeDraggable(element);
    	}
	})
})

</script>
<?php
$weekday = array(2 => "Montag",
                 3 => "Dienstag",
                 4 => "Mittwoch",
                 5 => "Donnerstag",
                 6 => "Freitag",
                 7 => "Samstag",
                 1 => "Sonntag",
                 8 => "Feiertag");
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
				{{Auth::user()->department->company->id}}
				</div>

				<div class="panel-body">

				<div class="col-md-4">
					<table class="table table-bordered">
					@foreach (\App\Department::find(1)->users as $u)
					<tr>
						<td>
							<span class="drag">
								{{$u->lastname}}, {{$u->firstname}}
							</span>
						</td>
					</tr>
					@endforeach
					</table>
				</div>
				<div class="col-md-4" style="overflow-y: scroll; height:300px;">
					<table id="target" class="table table-bordered table-striped">
					@for ($i=0; $i<=10; $i++)
					<tr>
						<td>
							{{$i}}
						</td>
						<td>
							<div class="drop">
							</div>
						</td>
					</tr>
					@endfor
					</table>
				</div>
				<?php
				$date = '2016-01-01';
				$start = new Carbon($date);
				$s = $start->startOfMonth()->toDateString();
				$end = new Carbon($date);
				$en = $end->endOfMonth()->toDateString();
                $events3 = \App\Department::find(1)->events()->whereBetween('events.date', array($s, $en))
							->distinct()
							->dayType(1)
							->selectRaw('events.user_id as uid, events.date as edate, if(shortname<>"", 8, dayofweek(events.date)) as wd, count(if(shortname<>"", 8, dayofweek(events.date))) as daytype')
							->groupBy('events.user_id')
							->groupBy('wd')
							->orderBy('uid')
							->orderBy('wd')
                     		->toSql();
                ?>
                <?php
				$events = \App\Department::find(1)->events()->whereBetween('events.date', array($s, $en))
							->where('events.entry_id', '=', 12)
							->dayType(1)
							->selectRaw('events.user_id as uid, events.date as edate, count(if(shortname<>"", 8, dayofweek(events.date))) as daytype, count(shortname) as shortie, if(shortname<>"", 8, dayofweek(events.date)) as wd')
							->groupBy('events.user_id')
							->groupBy('wd')
							->orderBy('uid')
							->orderBy('wd')
                     		->get();

                $events2 = \App\Department::find(1)->events()->whereBetween('events.date', array($s, $en))
							->distinct()
							->dayType(1)
							->selectRaw('events.user_id as uid, events.id as eid, events.date as edate, if(shortname<>"", 8, dayofweek(events.date)) as wd')
							->where('events.user_id', '=', 1)
							->orderBy('uid')
							->orderBy('wd')
                     		->get();
				?>
				<div class="drop col-md-4" style="overflow-y: scroll; height:300px;">
				{{$start->toDateString()}}/{{$end->toDateString()}}/
				<br>
				<ul>
					@foreach($events as $e)
					<li>
					User: {{$e->uid}}|{{$weekday[$e->wd]}}|Anzahl: {{$e->daytype}}||Shortie: {{$e->shortie}}
					</li>
					@endforeach
				</ul>
				</div>
				<div class="col-md-12">
					<ul>
					@foreach($events2 as $e)
						<li>
							User: {{$e->uid}}|Datum: {{$e->edate}}|ID: {{$e->eid}}|Wochentag: {{$e->wd}}
						</li>
					@endforeach
					</ul>
					<hr>
				</div>
				<div class="col-md-12 well">
					<h3>{{$events3}}</h3>
				</div>
				<div>
			      <a class="example-image-link" href="http://lokeshdhakar.com/projects/lightbox2/images/image-1.jpg" data-lightbox="example-1"><img class="example-image" src="http://lokeshdhakar.com/projects/lightbox2/images/thumb-1.jpg" alt="image-1" /></a>
			      <a class="example-image-link" href="http://lokeshdhakar.com/projects/lightbox2/images/image-2.jpg" data-lightbox="example-2" data-title="Optional caption."><img class="example-image" src="http://lokeshdhakar.com/projects/lightbox2/images/thumb-2.jpg" alt="image-1"/></a>
			    </div>

			    <hr />

			    <h3>A Four Image Set</h3>
			    <div>
			      <a class="example-image-link" href="http://lokeshdhakar.com/projects/lightbox2/images/image-3.jpg" data-lightbox="example-set" data-title="Click the right half of the image to move forward."><img class="example-image" src="http://lokeshdhakar.com/projects/lightbox2/images/thumb-3.jpg" alt=""/></a>
			      <a class="example-image-link" href="http://lokeshdhakar.com/projects/lightbox2/images/image-4.jpg" data-lightbox="example-set" data-title="Or press the right arrow on your keyboard."><img class="example-image" src="http://lokeshdhakar.com/projects/lightbox2/images/thumb-4.jpg" alt="" /></a>
			      <a class="example-image-link" href="http://lokeshdhakar.com/projects/lightbox2/images/image-5.jpg" data-lightbox="example-set" data-title="The next image in the set is preloaded as you're viewing."><img class="example-image" src="http://lokeshdhakar.com/projects/lightbox2/images/thumb-5.jpg" alt="" /></a>
			      <a class="example-image-link" href="http://lokeshdhakar.com/projects/lightbox2/images/image-6.jpg" data-lightbox="example-set" data-title="Click anywhere outside the image or the X to the right to close."><img class="example-image" src="http://lokeshdhakar.com/projects/lightbox2/images/thumb-6.jpg" alt="" /></a>
			    </div>
			</div>
		</div>
	</div>
</div> <!---->

@endsection
