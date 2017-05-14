@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
				Demoklinik
				</div>

				<div class="panel-body">
                    <h2>Mit dieser Klinik dürfen Sie spielen</h2>

                    @include('demofacts')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
