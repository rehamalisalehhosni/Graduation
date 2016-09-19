      <!DOCTYPE html>
      <head>
        <meta charset="utf-8">
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
        <title>Jeeran </title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script
        src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
        crossorigin="anonymous"></script>
        <link rel="stylesheet" href={{ asset('css/style.css') }}>
        <link rel="stylesheet" href={{ asset('css/bootstrap.min.css') }}>
        <link rel="stylesheet" href={{ asset('css/font-awesome.min.css') }}>

      </head>
      <body>
         <div class="fluid-container">
             <header>
               <div class="logo"><img src='{{ asset('img/home_logo.png') }}' /></div>

             </header>
             <div class="content">
               @yield('content')
             </div>
             <footer >
               <div class="row" style="direction:rtl">
               <div class="col-md-4  col-md-offset-4">

               <p>يمكنك تحميل تطبيق جيران من خلال </p>
               <div>
                  <a href="" class="btn btn-default newbtn fa-lg"><i class="fa fa-apple" aria-hidden="true"></i> App Store </a>
                  <a href="" class="btn btn-default newbtn fa-lg"><i class="fa fa-android" aria-hidden="true"></i> Google Play </a>
               </div>
               <ul class="list-inline">
                 <li><a href="{{ Request::root() }}/app"><i class="fa fa-home" aria-hidden="true"></i> الرئيسية </a>  |  </li>
                 <li><a href="{{ Request::root() }}/app/policy"><i class="fa fa-lock" aria-hidden="true"></i> سياسه الخصوصية </a> | </li>
                 <li><a href="{{ Request::root() }}/app/contact"><i class="fa fa-envelope" aria-hidden="true"></i> اتصل بنا </a></li>
               </ul>
               <p class="clearfix"></p>
               <ul class="list-unstyled">
                 <li><a href="{{ Request::root() }}/en"><i class="fa fa-language" aria-hidden="true"></i> English </a>  </li>
               </ul>
               <p class="clearfix"></p>
               <p>Copyright &copy; 2016 Good news Solutions</p>
             </div>
             </div>
             </footer>
             <script src="{{ asset('js/jquery.min.js') }}"></script>
             <script src="{{ asset('js/bootstrap.min.js') }}"></script>
         </div>
      </body>
      </html>
