<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ Hyvikk::get('app_name') }}</title>
  <link rel="icon" href="{{ asset('favicon1.ico') }}" type="image/png">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/css/dist/adminlte.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('assets/plugins/iCheck/square/blue.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  @yield("extra_css")
  @php
      $asset = asset('uploads/bg.jpeg');
  @endphp
  <style>
    .login-page, .register-page {
    /* background: #e9ecef; */
    background-image: url(<?=$asset?>) !important;
    background-size: cover;
    background-repeat: no-repeat;
}
  </style>
  <script>
    window.Laravel = {!! json_encode([
    'csrfToken' => csrf_token(),
    ]) !!};
  </script>
</head>
<body class="hold-transition login-page">
  <!-- fleet manager version 4.0.2 -->
<div class="login-box">
  <div class="login-logo">
    <center> <img src="{{ asset('assets/images/'. Hyvikk::get('logo_img') ) }}" height="140px" width="300px" /> </center>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>
      <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        <div class="form-group has-feedback">
          <div class="input-group {{ $errors->has('email') ? ' has-error' : '' }}">
          <div class="input-group-prepend">
            <span class="fa fa-envelope form-control-feedback input-group-text"></span>
          </div>
          <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" id="email" autofocus required>

            @if ($errors->has('email'))
              <span class="help-block">
                  <strong class="text-danger">{{ $errors->first('email') }}</strong>
              </span>
            @endif
          </div>
        </div>
        <div class="form-group has-feedback">
          <div class="input-group {{ $errors->has('password') ? ' has-error' : '' }}">
          <div class="input-group-prepend">
            <span class="fa fa-lock form-control-feedback input-group-text"></span>
          </div>
          <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
            @if ($errors->has('password'))
              <span class="help-block">
                  <strong class="text-danger">{{ $errors->first('password') }}</strong>
              </span>
            @endif
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- /.social-auth-links -->
      <p class="mb-1">
        <a href="{{ route('password.request') }}">I forgot my password</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass   : 'iradio_square-blue',
      increaseArea : '20%' // optional
    })
  })
</script>
</body>
</html>