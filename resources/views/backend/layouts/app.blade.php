      <!DOCTYPE html>
      <head>
        <meta charset="utf-8">
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
        <title>Dashboard </title>
          <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

          <meta name="keywords" content="" />
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href={{ asset('css/templatemo_main.css') }}>
          <link rel="stylesheet" href={{ asset('css/navbar.css') }}>
        <script
        src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
        crossorigin="anonymous"></script>
      </head>
      <body>
        @include('backend.layouts.header')
          <div class="template-page-wrapper">
            @include('backend.layouts.sidebar')
            <div class="templatemo-content-wrapper">
              <div class="templatemo-content">              
                @yield('content')
              </div>
            </div>
            @include('backend.layouts.footer')


      </body>
      </html>
