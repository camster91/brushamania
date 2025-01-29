<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
		<link rel="stylesheet" href="{{ mix('/css/all.css') }}">
		<title>Brush-a-mania</title>
	</head>
	<body>
		<div class="wrapper" id="app-wrapper">
			@include('layouts.sidebar')
			@yield('content')
			@if(!$updated_password)
				<update-password-modal-component></update-password-modal-component>
			@endif
		</div>
		<script src="{{ mix('/js/all.js') }}"></script>
	</body>
</html>