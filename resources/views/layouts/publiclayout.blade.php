<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title Page-->
    <title>{{isset($page_title) ? $page_title : 'Login'}}</title>

    <link rel="stylesheet" href="{{ mix('/css/reg.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
</head>

<body>
    <div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins" id="reg-container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand pull-left" href="/"><img src="/img/small_logo.png" alt="brushamania-logo"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expand="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="nav-container">
               <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="navbar-nav">
                        <a href="/school-registration" class="nav-item nav-link">School Registration</a>
                        <a href="/dentist-registration" class="nav-item nav-link">Dentist Registration</a>
                        <a href="/rotarian-registration" class="nav-item nav-link">Rotarian Registration</a>
                        <a href="/parent-registration" class="nav-item nav-link">Parent Registration</a>
                        <a href="/parent-login" class="nav-item nav-link">Brushtracker</a>
                        <a href="/login" class="nav-item nav-link">Login</a>
                    </div>
                </div> 
            </div>
        </nav>
        <div class="wrapper wrapper--w680">
            <div class="card card-4">
                <img src="/img/logo2.jpg" alt="logo" style="width: 100%;" class="print-img">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ mix('/js/reg.js') }}"></script>
    <script>
	

	jQuery(document).ready(function($) {
       function formatNumber(str){
        let cleaned = ('' + str).replace(/\D/g, '');
  
  //Check if the input is of correct length
  let match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);

  if (match) {
    return '(' + match[1] + ') ' + match[2] + '-' + match[3]
  };

  return str;
       }
		$('#phone_login').keyup(function() {
			$('#phone_login').val(formatNumber($('#phone_login').val()));
		})
	});
</script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>
<!-- end document-->