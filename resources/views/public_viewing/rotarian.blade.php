@extends('layouts.publiclayout')

@section('content')
	<rotarian-detail-component :rotarian="{{$rotarian}}">
		<div class="col-md-12 print-row" style="margin-bottom: 50px;">
			<h2 class="title" style="display: inline">{{$rotarian->rotarian_name}}</h2>
			<button class="btn btn-primary pull-right" onclick="window.print()">Print</button>
		</div>
	</rotarian-detail-component>
@endsection