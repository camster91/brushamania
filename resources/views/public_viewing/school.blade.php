@extends('layouts.publiclayout')

@section('content')
	<school-public-details-component :school="{{$school}}">
		<div class="col-md-12 print-row" style="margin-bottom: 50px;">
			<h2 class="title" style="display: inline">{{$school->school_name}}</h2>
			<button class="btn btn-primary pull-right" onclick="window.print()">Print</button>
		</div>
	</school-oublic-details-component>
@endsection