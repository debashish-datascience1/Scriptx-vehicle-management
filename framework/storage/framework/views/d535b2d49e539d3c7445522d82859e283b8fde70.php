<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Vehicle Management</title>
  <!-- Bootstrap core CSS -->
  <link href="<?php echo e(asset('assets/css/invoice.css')); ?>" rel="stylesheet" />
  <link href="css/responsive.css" rel="stylesheet" />
  <link href="<?php echo e(asset('assets/css/cdn/bootstrap.min.css')); ?>" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
  <section class="lg-fr">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="tr-fr">
            <div class="l-logo">
              <a href="#"><img src="<?php echo e(asset('assets/css/images/rgc.png')); ?>" /></a>
            </div>
            <form class="form-horizontal" role="form" method="POST" action="<?php echo e(route('login')); ?>">
              <?php echo e(csrf_field()); ?>

              <h5>Login</h5>
              <div class="wrap-input100 validate-input m-b-10" data-validate="Email is required">
                <input type="email" class="input100"  name="email" placeholder="Email" required/>
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                  <i class="fa fa-user"></i>
                </span>
              </div>

              <div class="wrap-input100 validate-input m-b-10" data-validate="Password is required">
                <input class="input100" type="password" name="password" placeholder="Password" required/>
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                  <i class="fa fa-lock"></i>
                </span>
              </div>

              <div class="container-login100-form-btn p-t-10">
                <button type="submit" class="login100-form-btn" name="login">Sign In</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="ft">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="ft-lt">
            06843 : 265512 (0) ,265512 (Fax) , 9437965062 (Mob)
          </div>
          <div class="ft-rt">
            AT/PO : Purunakatak, Dist : Boudh, Email :
            ashokagrawallarpp@gmail.com
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="ft-ud">
    &copy; Copyright <strong>ScriptX Technologies</strong>. All Rights
    Reserved
  </div>
</body>

</html><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/auth/login.blade.php ENDPATH**/ ?>