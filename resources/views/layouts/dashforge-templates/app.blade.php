<!DOCTYPE html>
<html lang="en">
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Twitter -->
    <meta name="twitter:site" content="@redtech">
    <meta name="twitter:creator" content="@redtech">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Webcore">
    <meta name="twitter:description" content="Responsive Bootstrap 4 Dashboard Template">
    <meta name="twitter:image" content="https://via.placeholder.com/1260x950?text=Webcore">

    <!-- Facebook -->
    <meta property="og:url" content="https://dandisy.github.io">
    <meta property="og:title" content="Webcore">
    <meta property="og:description" content="Webcore - Web Backend Generate">

    <meta property="og:image" content="https://via.placeholder.com/1260x950?text=Webcore">
    <meta property="og:image:secure_url" content="https://via.placeholder.com/1260x950?text=Webcore">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Webcore - Web Backend Generate">
    <meta name="author" content="Redtech">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.png') }}">

    <title>Webcore - Web Backend Generate</title>

    <!-- vendor css -->
    <link href="{{ asset('vendor/dashforge/lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/dashforge/lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">

    <!-- include Summernote -->
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote.css') }}">

    <!-- include Fancybox -->
    <link rel="stylesheet" href="{{ asset('vendor/fancybox/jquery.fancybox.min.css') }}">

    <!-- include Fileuploader -->
    <link rel="stylesheet" href="{{ asset('vendor/fileuploader/jquery.fileuploader.css') }}">

    <!-- include Dropify -->
    <link rel="stylesheet" href="{{ asset('vendor/dropify/css/dropify.min.css') }}">

    <!-- include Datetimepicker -->
    <link rel="stylesheet" href="{{ asset('vendor/datetimepicker/css/bootstrap-datetimepicker.min.css') }}">

    <!-- DashForge CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/dashforge/assets/css/dashforge.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dashforge/assets/css/dashforge.dashboard.css') }}">

    <style>
        .aside.minimize img {
            display: none;
        }

        .file-item {
            margin-top: 15px;
            /* padding: 5px; */
            border: 1px solid #d2d6de;
        }
        .file-item > div:first-child {
            margin-left: -10px;
        }

        #image-thumb {
            margin-bottom: 10px;
            border: 1px solid #ddd;
        }
    </style>

    <style>
        .select2-container {
            width: 100% !important;
        }

        /* .main-header .navbar .main-menu .dropdown-menu {
            max-height: calc(100vh - 70px);
            overflow-y: auto;
        } */

        @media screen and (max-width: 767px) {
            /* .main-header .sidebar-toggle:before {
                content: "\f065";
            }

            .main-sidebar, .left-side {
                padding-top: 50px;
            }

            .sidebar-open .main-header .sidebar-toggle:before {
                content: "\f066";
            }

            .main-header .logo {
                background-color: transparent !important;
                position: absolute;
                z-index: 9999;
                width: 100px;
                left: 50%;
                transform: translateX(-50%);
            }

            .main-header .navbar .main-menu .dropdown-menu li a {
                color: #333;
            }

            .main-header .navbar .main-menu .dropdown-menu li.divider {
                background-color: #eee;
            } */
        }

        @media screen and (max-width: 576px) {
            /* .content-header > h1.pull-right {
                margin-top: 15px;
            } */
        }
    </style>

    @yield('styles')
    @yield('style')
    @yield('css')
  </head>
  <body>
    @include('layouts.dashforge-templates.sidebar')

    <div class="content ht-100v pd-0">
      <div class="content-header">
        <div class="content-search">
          <i data-feather="search"></i>
          <input type="search" class="form-control" placeholder="Search...">
        </div>
        <nav class="nav">
          <a href="" class="nav-link"><i data-feather="help-circle"></i></a>
          <a href="" class="nav-link"><i data-feather="grid"></i></a>
          <a href="" class="nav-link"><i data-feather="align-left"></i></a>
        </nav>
      </div><!-- content-header -->

      @yield('contents')
    </div>

    <script src="{{ asset('vendor/dashforge/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/jquery.flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/jquery.flot/jquery.flot.resize.js') }}"></script>

    <script src="{{ asset('vendor/dashforge/assets/js/dashforge.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/assets/js/dashforge.aside.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/assets/js/dashforge.sampledata.js') }}"></script>

    <!-- Summernote -->
    <script src="{{ asset('vendor/summernote/summernote.min.js') }}"></script>

    <!-- Fancybox -->
    <script src="{{ asset('vendor/fancybox/jquery.fancybox.min.js') }}"></script>

    <!-- Fileuploader -->
    <script src="{{ asset('vendor/fileuploader/jquery.fileuploader.min.js') }}"></script>

    <!-- Dropify -->
    <script src="{{ asset('vendor/dropify/js/dropify.min.js') }}"></script>

    <!-- Inputmask -->
    <script src="{{ asset('vendor/inputmask/jquery.inputmask.bundle.min.js') }}"></script>

    <!-- Moment -->
    <script src="{{ asset('vendor/moment/moment.min.js') }}"></script>

    <!-- Datetimepicker -->
    <script src="{{ asset('vendor/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>

    <!-- append theme customizer -->
    <script src="{{ asset('vendor/dashforge/lib/js-cookie/js.cookie.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/assets/js/dashforge.settings.js') }}"></script>
    <script>

    </script>
    <script crossorigin="anonymous"
            src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.11/tinymce.min.js"
            integrity="sha512-3tlegnpoIDTv9JHc9yJO8wnkrIkq7WO7QJLi5YfaeTmZHvfrb1twMwqT4C0K8BLBbaiR6MOo77pLXO1/PztcLg==">
    </script>
    <script>
        var editor_config = {
            skin: "lightgray",
            height: 300,
            min_height: 300,
            path_absolute : "/",
            selector: "textarea.my-editor",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            relative_urls: false,
            file_browser_callback : function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'filemanager?field_name=' + field_name;
                cmsURL = cmsURL + "&type=Files";

                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                });
            }
        };

        tinymce.init(editor_config);
    </script>

    @yield('scripts')
    @yield('script')
    @yield('js')
  </body>
</html>
