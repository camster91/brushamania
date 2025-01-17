<html>

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <meta charset="UTF-8">

    <style type="text/css">
        @page {
            margin: 0px;
            padding: 0px;
        }

        #cert-body {
            /* background-image: url("/img/certificate2021.jpg"); */
            /* background-image: {{ public_path('img/certificate2021.jpg') }}; */
            background-repeat: no-repeat;
            background-position: 0;
            background-size: 100%;
            height: 85%;
            width: 100%;
        }

        #child_name {
            padding-top: 500px;
            text-align: center;
            font-family: Helvetica, sans-serif;
            font-size: 50px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div id="cert-body">
        <img src="{{ public_path('img/certificate' . $year . '.jpg') }}" alt=""
            style="position: absolute; z-index: 1;">
        @if (strlen($firstname . ' ' . $lastname) < 35)
            <p id="child_name" style="z-index: 100 adding-top: 400px;">
                {{ $firstname . ' ' . $lastname }}
            </p>
        @else
            <p id="child_name" style="z-index: 100; font-size: 45px; padding-top: 400px;">
                {{ $firstname . ' ' . $lastname }}
            </p>
        @endif

    </div>
</body>

</html>
