<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="{{url('font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
      <title>{{ config('app.name', 'Laravel') }}</title>
      <link href="{{ url('/') }}/css/bootstrap.min.css" rel="stylesheet">
      <link href="{{ url('/') }}/css/styles.css" rel="stylesheet">
      <link href="{{ url('/') }}/css/select2.css" rel="stylesheet">
   </head>
   <body>
    @php
    if(Auth::user())
    {
        $old_transaction = Auth::user()->transactions()->orderBy('created_at','desc')->get()->first();
        if ($old_transaction)
        {
            $balance = $old_transaction->balance;
        }
        else
        {
            $balance = 0;
        }
    }
    @endphp
      <nav class="navbar navbar-default navbar-fixed-top">
         <div class="container">
            <div class="navbar-header">
               <a class="navbar-brand" href="{{ url('/') }}">
                  {{ config('app.name', 'Laravel') }}
               </a>
            </div>
            <div class="collapse navbar-collapse" id="app-navbar-collapse">



               <!-- Left Side Of Navbar -->
               <ul class="nav navbar-nav">
                  <li><a href="{{ url('/') }}">Home</a></li>
               </ul>
      <div class="col-sm-4 col-md-4">
        <form class="navbar-form" role="search">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
        </form>
        </div>
               <ul class="nav navbar-nav navbar-right">
                  @if (Auth::guest())
                      <li><a  href="{{ url('/login?ref=add-post') }}">Add Article</a></li>
                      <li><a href="{{ url('/login') }}">Login</a></li>
                      <li><a href="{{ url('/register') }}">Register</a></li>
                  @else
                      <li> <a style="background-color:#11998e;color:#fff;margin:0px;" href="">{{$balance}} Points Avaible</a> </li>
                      <li><a href="{{ url('/blog/create') }}">Add Article</a></li>
                      <li><a href="{{ url('/blog/manage') }}">Manage My Articles</a></li>
                  @if(Auth::user()->profile_photo)
                      <li style="margin-top: 10px;"><img align="bottom" alt="User Image" class="img-circle" width="30" height="30" src="{{ url('files/user/profile_photo/resized/'.Auth::user()->profile_photo) }}"></li>
                  @endif
                  <li class="dropdown">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                     </a>
                     <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ url('/profile/edit/'.Auth::user()->id) }}">Profile</a></li>
                        <li><a href="{{ url('/tags') }}">Tags</a></li>
                        <li><a href="{{ url('/favorite') }}">My favorite</a></li>
                        <li><a href="{{ url('/notification') }}">Notification</a></li>
                        <li><a href="{{ url('/settings') }}">Settings</a></li>
                        <li><a href="{{ url('/my_followers') }}">My Followers</a></li>
                        <li><a href="{{ url('/my_followings') }}">My Followings</a></li>
                        @if(Auth::user()->type == 1)
                        <li><a href="{{ url('/users/list') }}">Users</a></li>
                        @endif
                        <li>
                           <a href="{{ url('/logout') }}"
                              onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                              Logout
                           </a>
                           <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                           </form>
                        </li>
                     </ul>
                  </li>
                  @endif
               </ul>
            </div>
         </div>
      </nav>
      @yield('content')
      <hr>
      <footer>
         <div class="container">
            <div class="row">
               <div class="col-lg-12">
               </div>
            </div>
         </div>
      </footer>
      <!-- Scripts -->
      <!-- <script src="{{ url('/') }}/js/app.js"></script> -->
      <script src="{{ url('/') }}/js/jquery.js"></script>
      <!-- Bootstrap Core JavaScript -->
      <script src="{{ url('/') }}/js/bootstrap.min.js"></script>
      <script src="{{ url('/') }}/js/select2.min.js"></script>
      <script type="text/javascript">
         $(".js-example-basic-multiple").select2({
            placeholder: "Select one more categories"
         });
      </script>
      <script src="{{ url('/') }}/js/jscroll/jquery.jscroll.js"></script>

      <script type="text/javascript">
    $('ul.pagination').hide();
    $(function() {
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<img class="center-block" src="{{ url('/') }}/images/loading.gif" alt="Loading...." />',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').remove();
            }
        });
    });
</script>

  <script src="{{url('js/tinymce/tinymce.min.js')}}"></script>
  <script>tinymce.init({
  selector: '.editor',
  height: 200,
  menubar: false,
  branding:false,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor textcolor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table contextmenu paste code help wordcount'
  ],
  toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tinymce.com/css/codepen.min.css']
});</script>
   </body>
</html>
