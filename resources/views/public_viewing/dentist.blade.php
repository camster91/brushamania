@extends('layouts.publiclayout')

@section('content')
	<dentist-detail-component :dentist="{{$dentist}}">
		<div class="col-md-12 print-row" style="margin-bottom: 50px;">
			<h2 class="title" style="display: inline">{{$dentist->dentist_name}}</h2>
			<button class="btn btn-primary pull-right" onclick="window.print()">Print</button>
		</div>
	</dentist-detail-component>
@endsection