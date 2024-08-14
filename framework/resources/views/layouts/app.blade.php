<!DOCTYPE html>
@if(Auth::user()->getMeta('language')!= null)
  @php ($language = Auth::user()->getMeta('language'))
@else
  @php($language = Hyvikk::get("language"))
@endif


<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ Hyvikk::get('app_name') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset('favicon1.ico') }}" type="image/x-icon">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('assets/css/ionicons.min.css')}}">
  <!-- fullCalendar 2.2.5-->
  <link rel="stylesheet" href="{{asset('assets/plugins/fullcalendar/fullcalendar.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/plugins/fullcalendar/fullcalendar.print.css')}}" media="print">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cdn/buttons.dataTables.min.css')}}">
    <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/css/dist/adminlte.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('assets/plugins/iCheck/flat/blue.css')}}">
    <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{asset('assets/plugins/iCheck/all.css')}}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{asset('assets/plugins/morris/morris.css')}}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{asset('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="{{ asset('assets/css/fonts/fonts.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/pnotify.custom.min.css')}}" media="all" rel="stylesheet" type="text/css" />
  @yield("extra_css")
  <script>
  window.Laravel = {!! json_encode([
  'csrfToken' => csrf_token(),
  'subscription_url' => asset('assets/push_notification/push_subscription.php'),
  'serviceWorkerUrl' => asset("serviceWorker.js")
  ]) !!};
  </script>
  <!-- browser notification -->
  <script type="text/javascript" src="{{asset('assets/push_notification/app.js')}}"></script>
  
  <style type="text/css">
    tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
        font-size: 0.6em;
        height: 35px !important;
    }
    .cursor-pointer{cursor: pointer;}
  </style>
  @if($language == "Arabic-ar")
    <style type="text/css">
      .sidebar{
        text-align: right;
      }
      .nav-sidebar .nav-link>p>.right {
        position: absolute;
        right: 0rem;
        top: 12px;
      }
      .nav-sidebar>.nav-item {
        margin-right: -20px;
      }
    </style>
  @endif
</head>

<body class="hold-transition sidebar-mini" @if($language == "Arabic-ar") dir="rtl" @endif>
  {!! Form::hidden('loggedinuser',Auth::user()->id,['id'=>'loggedinuser']) !!}
  {!! Form::hidden('user_type',Auth::user()->user_type,['id'=>'user_type']) !!}
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
          @if(Auth::user()->user_type == "S")
            @php($r = 0)
            @php($i = 0)
            @php($l = 0)
            @php($d = 0)
            @php($s = 0)
            @php($user= Auth::user())
            @foreach ($user->unreadNotifications as $notification)
            @if($notification->type == "App\Notifications\RenewRegistration")
              @php($r++)
              @elseif($notification->type == "App\Notifications\RenewInsurance")
              @php($i++)
              @elseif($notification->type == "App\Notifications\RenewVehicleLicence")
              @php($l++)
              @elseif($notification->type == "App\Notifications\RenewDriverLicence")
              @php($d++)
              @elseif($notification->type == "App\Notifications\ServiceReminderNotification")
              @php($s++)
              @endif
            @endforeach
          @php($n = $r + $i +$l + $d + $s)
      {{-- <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-bell-o"></i>
          <span class="badge badge-warning navbar-badge">@if($n>0) {{$n}} @endif</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          @if($n>0)<span class="dropdown-item dropdown-header"> {{$n}} Notifications </span>
          <div class="dropdown-divider"></div>@endif
          <a href="{{url('admin/vehicle_notification',['type'=>'renew-registrations'])}}" class="dropdown-item">
            <i class="fa fa-id-card-o mr-2"></i> @lang('fleet.renew_registration')
            <span class="float-right text-muted text-sm">@if($r>0) {{$r}} @endif</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{url('admin/vehicle_notification',['type'=>'renew-insurance'])}}" class="dropdown-item">
            <i class="fa fa-file-text mr-2"></i> @lang('fleet.renew_insurance')
            <span class="float-right text-muted text-sm">@if($i>0) {{$i}} @endif</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{url('admin/vehicle_notification',['type'=>'renew-licence'])}}" class="dropdown-item">
            <i class="fa fa-file-o mr-2"></i> @lang('fleet.renew_licence')
            <span class="float-right text-muted text-sm">@if($l>0) {{$l}} @endif</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{url('admin/driver_notification',['type'=>'renew-driving-licence'])}}" class="dropdown-item">
            <i class="fa fa-file-text-o mr-2"></i> @lang('fleet.renew_driving_licence')
            <span class="float-right text-muted text-sm">@if($d>0) {{$d}} @endif</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{url('admin/reminder',['type'=>'service-reminder'])}}" class="dropdown-item">
            <i class="fa fa-clock-o mr-2"></i> @lang('fleet.serviceReminders')
            <span class="float-right text-muted text-sm">@if($s>0) {{$s}} @endif</span>
          </a>
        </div>
      </li> --}}
      @endif
    <!-- logout -->
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-user-circle"></i>
          <span class="badge badge-danger navbar-badge"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              @if(Auth::user()->user_type == 'D' && Auth::user()->getMeta('driver_image') != null)
              @if(starts_with(Auth::user()->getMeta('driver_image'),'http'))
                @php($src = Auth::user()->getMeta('driver_image'))
                @else
                @php($src=asset('uploads/'.Auth::user()->getMeta('driver_image')))
                @endif
                <img src="{{$src}}" class="img-size-50 mr-3 img-circle" alt="User Image">
                @elseif(Auth::user()->user_type == 'S' || Auth::user()->user_type == 'O')
                  @if(Auth::user()->getMeta('profile_image') == null)
                  <img src="{{ asset("assets/images/no-user.jpg")}}" class="img-size-50 mr-3 img-circle" alt="User Image">
                  @else
                  <img src="{{asset('uploads/'.Auth::user()->getMeta('profile_image'))}}" class="img-size-50 mr-3 img-circle" alt="User Image">
                  @endif
                @elseif(Auth::user()->user_type == 'C' && Auth::user()->getMeta('profile_pic') != null)
                @if(starts_with(Auth::user()->getMeta('profile_pic'),'http'))
                @php($src = Auth::user()->getMeta('profile_pic'))
                @else
                @php($src=asset('uploads/'.Auth::user()->getMeta('profile_pic')))
                @endif
                <img src="{{$src}}" class="img-size-50 mr-3 img-circle" alt="User Image">
                @else
                <img src="{{ asset("assets/images/no-user.jpg")}}" class="img-size-50 mr-3 img-circle" alt="User Image">
                @endif

              <div class="media-body">
                <h3 class="dropdown-item-title">
                  {{Auth::user()->name}}

                  <span class="float-right text-sm text-danger">

                  </span>
                </h3>
                <p class="text-sm text-muted">{{Auth::user()->email}}</p>
                <p class="text-sm text-muted"></p>

              </div>
            </div>
            <div>
            <div style="margin: 5px;">
              <a href="{{ url('admin/change-details/'.Auth::user()->id)}}" class="btn btn-secondary btn-flat"><i class="fa fa-edit"></i> @lang('fleet.editProfile')</a>

              <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-secondary btn-flat pull-right"> <i class="fa fa-sign-out"></i>
              @lang('menu.logout')
              </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
            </div>
            <div class="clear"></div>
            </div>
            <!-- Message End -->
          </a>
        </div>
      </li>
      {{-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fa fa-th-large"></i></a>
      </li> --}}
    <!-- logout -->
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-info elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('admin/')}}" class="brand-link">
      <img src="{{ asset('assets/images/'. Hyvikk::get('icon_img') ) }}" alt="Fleet Logo" class="brand-image"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{{  Hyvikk::get('app_name')  }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
           @if(Auth::user()->user_type == 'D' && Auth::user()->getMeta('driver_image') != null)
           @if(starts_with(Auth::user()->getMeta('driver_image'),'http'))
            @php($src = Auth::user()->getMeta('driver_image'))
            @else
            @php($src=asset('uploads/'.Auth::user()->getMeta('driver_image')))
            @endif
            <img src="{{$src}}" class="img-circle elevation-2" alt="User Image">
            @elseif(Auth::user()->user_type == 'S' || Auth::user()->user_type == 'O')
              @if(Auth::user()->getMeta('profile_image') == null)
              <img src="{{ asset("assets/images/no-user.jpg")}}" class="img-circle elevation-2" alt="User Image">
              @else
              <img src="{{asset('uploads/'.Auth::user()->getMeta('profile_image'))}}" class="img-circle elevation-2" alt="User Image">
              @endif
            @elseif(Auth::user()->user_type == 'C' && Auth::user()->getMeta('profile_pic') != null)
            @if(starts_with(Auth::user()->getMeta('profile_pic'),'http'))
            @php($src = Auth::user()->getMeta('profile_pic'))
            @else
            @php($src=asset('uploads/'.Auth::user()->getMeta('profile_pic')))
            @endif
            <img src="{{$src}}" class="img-circle elevation-2" alt="User Image">
            @else
            <img src="{{ asset("assets/images/no-user.jpg")}}" class="img-circle elevation-2" alt="User Image">
            @endif

        </div>
        <div class="info">
          <a href="{{ url('admin/change-details/'.Auth::user()->id)}}" class="d-block">{{Auth::user()->name}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <!-- customer -->
          @if(Auth::user()->user_type=="C")

            @if(Request::is('admin/bookings*'))
            @php($class="menu-open")
            @php($active="active")
            @else
            @php($class="")
            @php($active="")
            @endif
          <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-address-card"></i>
              <p>
                @lang('menu.bookings')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('bookings.create')}}" class="nav-link @if(Request::is('admin/bookings/create')) active @endif">
                  <i class="fa fa-address-book nav-icon "></i>
                  <p> @lang('menu.newbooking')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('bookings.index')}}" class="nav-link @if((Request::is('admin/bookings*')) && !(Request::is('admin/bookings/create')) && !(Request::is('admin/bookings_calendar'))) active @endif">
                  <i class="fa fa-tasks nav-icon"></i>
                  <p> @lang('menu.manage_bookings')</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{ url('admin/change-details/'.Auth::user()->id)}}" class="nav-link @if(Request::is('admin/change-details*')) active @endif">
              <i class="nav-icon fa fa-edit"></i>
              <p>
                @lang('fleet.editProfile')
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('admin/addresses') }}" class="nav-link @if(Request::is('admin/addresses*')) active @endif">
              <i class="nav-icon fa fa-map-marker"></i>
              <p>
                @lang('fleet.addresses')
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('admin/') }}" class="nav-link @if(Request::is('admin')) active @endif">
              <i class="nav-icon fa fa-money"></i>
              <p>
                @lang('fleet.expenses')
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          @endif
          <!-- customer -->
          <!-- user-type S or O -->
          @if(Auth::user()->user_type=="S" || Auth::user()->user_type=="O")
          <li class="nav-item">
            <a href="{{ url('admin/')}}" class="nav-link @if(Request::is('admin')) active @endif">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                @lang('menu.Dashboard')
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('reports.global-search')}}" class="nav-link @if(Request::is('admin/global-search')) active @endif">
              <i class="nav-icon fa fa-globe"></i>
              <p>
                @lang('menu.global_search')
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          {{-- <li class="nav-item">
            <a href="{{ route('emi.index')}}" class="nav-link @if(Request::is('admin/emi*')) active @endif">
              <i class="nav-icon fa fa-credit-card"></i>
              <p>
                @lang('menu.emi')
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li> --}}
          @endif
          <!-- user-type S or O -->

          <!-- driver -->
          @if(Auth::user()->user_type=="D")

          <li class="nav-item">
            <a href="{{url('admin/')}}" class="nav-link @if(Request::is('admin/')) active @endif">
              <i class="nav-icon fa fa-user"></i>
              <p>
                @lang('fleet.myProfile')
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('my_bookings')}}" class="nav-link @if(Request::is('admin/my_bookings')) active @endif">
              <i class="nav-icon fa fa-book"></i>
              <p>
                @lang('menu.my_bookings')
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('admin/change-details/'.Auth::user()->id)}}" class="nav-link @if(Request::is('admin/change-details*')) active @endif">
              <i class="nav-icon fa fa-edit"></i>
              <p>
                @lang('fleet.editProfile')
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
            @if(Request::is('admin/notes*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-sticky-note"></i>
              <p>
                @lang('fleet.notes')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('notes.index') }}" class="nav-link @if((Request::is('admin/notes*') && !(Request::is('admin/notes/create')))) active @endif">
                  <i class="fa fa-flag nav-icon"></i>
                  <p> @lang('fleet.manage_note')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route("notes.create") }}" class="nav-link @if(Request::is('admin/notes/create')) active @endif">
                  <i class="fa fa-plus-square nav-icon"></i>
                  <p>@lang('fleet.create_note')</p>
                </a>
              </li>
            </ul>
          </li>
            @if(Request::is('admin/driver-reports*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-book"></i>
              <p>
                @lang('menu.reports')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route("dreports.monthly")}}" class="nav-link @if(Request::is('admin/driver-reports/monthly')) active @endif">
                  <i class="fa fa-calendar nav-icon"></i>
                  <p>@lang('menu.monthlyReport')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route("dreports.yearly")}}" class="nav-link @if(Request::is('admin/driver-reports/yearly')) active @endif">
                  <i class="fa fa-calendar nav-icon"></i>
                  <p>@lang('fleet.yearlyReport')</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
          <!-- driver -->

          <!-- sidebar menus for office-admin and super-admin -->
        @if(Auth::user()->user_type == "S" || Auth::user()->user_type == "O")
        @php($modules=unserialize(Auth::user()->getMeta('module'))) <!--array of selected modules of logged in user-->
        @else
        @php($modules=array())
        @endif

        @if (!Auth::guest() &&  Auth::user()->user_type!="D" && Auth::user()->user_type != "C" )

            @if((Request::is('admin/drivers*')) || (Request::is('admin/users*')) || (Request::is('admin/customers*')) )
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          @if(in_array(0,$modules) || Auth::user()->user_type == "S") <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-users"></i>
              <p>
                @lang('menu.users')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if(in_array(0,$modules))
              <li class="nav-item">
                <a href="{{ route('drivers.index')}}" class="nav-link @if(Request::is('admin/drivers*')) active @endif">
                  <i class="fa fa-id-card nav-icon"></i>
                  <p>@lang('menu.drivers')</p>
                </a>
              </li>
              @endif
              @if(Auth::user()->user_type=="S")
              <li class="nav-item">
                <a href="{{ route('users.index')}}" class="nav-link @if(Request::is('admin/users*')) active @endif">
                  <i class="fa fa-user nav-icon"></i>
                  <p>@lang('fleet.users')</p>
                </a>
              </li>
              @if(in_array(0,$modules))
              <li class="nav-item">
                <a href="{{ route('customers.index')}}" class="nav-link @if(Request::is('admin/customers*')) active @endif">
                  <i class="fa fa-address-card nav-icon"></i>
                  <p>@lang('fleet.customers')</p>
                </a>
              </li>
              @endif
              @endif
            </ul>
          </li> @endif
          @if((Request::is('admin/payroll*')) || Request::is('admin/manage-payroll*') || (Request::is('admin/leave*')) || Request::is('admin/bulk_leave*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          @if(in_array(1,$modules)) <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-user-circle-o"></i>
              <p>
                @lang('menu.hrms')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('payroll.index')}}" class="nav-link @if(Request::is('admin/payroll*')) active @endif">
                  <i class="fa fa-inr nav-icon"></i>
                  <p>@lang('menu.payroll')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('payroll.managepayroll')}}" class="nav-link @if(Request::is('admin/manage-payroll*')) active @endif">
                  <i class="fa fa-th-list nav-icon"></i>
                  <p>Manage Payroll</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('leave.index')}}" class="nav-link @if(Request::is('admin/leave*') && !Request::is('admin/leave/report')) active @endif">
                  <i class="fa fa-paperclip nav-icon"></i>
                  <p>@lang('menu.leave')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('bulk_leave.index')}}" class="nav-link @if(Request::is('admin/bulk_leave*')) active @endif">
                  <i class="fa fa-thumb-tack nav-icon"></i>
                  <p>@lang('menu.bulk_leave')</p>
                </a>
              </li>
            </ul>
          </li> @endif
          @if(Request::is('admin/accounting*') || Request::is('admin/bank-account*') || Request::is('admin/ob-balance*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          @if(in_array(1,$modules)) <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-money"></i>
              <p>
                @lang('menu.accounting')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('bank-account.index')}}" class="nav-link @if(Request::is('admin/bank-account*') &&  !Request::is('admin/bank-account/bulk_pay') && !Request::is('admin/bank-account/bulk_receive') && !Request::is('admin/bank-account/bulk_pay/manage') && !Request::is('admin/bank-account/deposit*')) active @endif">
                  <i class="fa fa-university nav-icon"></i>
                  <p>@lang('menu.bankAccount')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('bank-account.deposit')}}" class="nav-link @if(Request::is('admin/bank-account/deposit*')  &&  !Request::is('admin/bank-account/bulk_pay') && !Request::is('admin/bank-account/bulk_pay/manage')) active @endif">
                  <i class="fa fa-sticky-note-o nav-icon"></i>
                  <p>@lang('menu.deposit')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('bank-account.bulk_pay')}}" class="nav-link @if(Request::is('admin/bank-account/bulk_pay')) active @endif">
                  <i class="fa fa-list-ol nav-icon"></i>
                  <p>@lang('menu.bulkPay')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('bank-account.bulk_receive')}}" class="nav-link @if(Request::is('admin/bank-account/bulk_receive')) active @endif">
                  <i class="fa fa-list-ul nav-icon"></i>
                  <p>@lang('menu.bulkReceive')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('bulk_pay.manage')}}" class="nav-link @if(Request::is('admin/bank-account/bulk_pay/manage')) active @endif">
                  <i class="fa fa-newspaper-o nav-icon"></i>
                  <p>@lang('menu.manageBulkPay')</p>
                </a>
              </li>
              {{-- <li class="nav-item">
                <a href="{{ route('ob-balance.index')}}" class="nav-link @if(Request::is('admin/ob-balance/*')) active @endif">
                  <i class="fa fa-balance-scale nav-icon"></i>
                  <p>@lang('menu.ob_balance')</p>
                </a>
              </li> --}}
              <li class="nav-item">
                <a href="{{ route('accounting.index')}}" class="nav-link @if(Request::is('admin/accounting*') && !Request::is('admin/accounting/report*') && !Request::is('admin/accounting/transaction-bank') && !Request::is('admin/accounting/transaction-search')) active @endif">
                  <i class="fa fa-inr nav-icon"></i>
                  <p>@lang('menu.transactions')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('accounting.index-bank')}}" class="nav-link @if(Request::is('admin/accounting/transaction-bank')) active @endif">
                  <i class="fa fa-university nav-icon"></i>
                  <p>@lang('menu.transactions_bank')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('transaction.search')}}" class="nav-link @if(Request::is('admin/accounting/transaction-search')) active @endif">
                  <i class="fa fa-search nav-icon"></i>
                  <p>@lang('menu.transactions_search')</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
          @if((Request::is('admin/daily-advance*')) || (Request::is('admin/daily-advance/report*')) || Request::is('admin/other-advance*') || Request::is('admin/other-adjust*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          @if(in_array(1,$modules)) <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-paper-plane-o"></i>
              <p>
                @lang('menu.dailyAdvance')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('daily-advance.index')}}" class="nav-link @if(Request::is('admin/daily-advance*') && !Request::is('admin/daily-advance/report*')) active @endif">
                  <i class="fa fa-hashtag nav-icon"></i>
                  <p>Salary Advance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('other-advance.index')}}" class="nav-link @if(Request::is('admin/other-advance*') && !Request::is('admin/other-advance/report*')) active @endif">
                  <i class="fa fa-money nav-icon"></i>
                  <p>Other Advance</p>
                </a>
              </li>
            </ul>
          </li> @endif
            @if((Request::is('admin/driver-logs')) || (Request::is('admin/vehicle-types*')) || (Request::is('admin/vehicles*')) || (Request::is('admin/vehicle_group*')) || (Request::is('admin/vehicle-reviews*')) || (Request::is('admin/view-vehicle-review*')) || (Request::is('admin/vehicle-review*')) || Request::is('admin/vehicle-docs*') || Request::is('admin/vehicles/vehicle-emi*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          @if(in_array(1,$modules)) <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-taxi"></i>
              <p>
                @lang('menu.vehicles')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('vehicles.index')}}" class="nav-link @if(Request::is('admin/vehicles*') && !Request::is('admin/vehicles/report') && !Request::is('admin/vehicles/vehicle-emi*')) active @endif">
                  <i class="fa fa-truck nav-icon"></i>
                  <p>@lang('menu.manageVehicles')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('vehicle-emi.index')}}" class="nav-link @if(Request::is('admin/vehicles/vehicle-emi*')) active @endif">
                  <i class="fa fa-credit-card nav-icon"></i>
                  <p>@lang('menu.vehicle_emi')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('vehicle-types.index')}}" class="nav-link @if(Request::is('admin/vehicle-types*')) active @endif">
                  <i class="fa fa-th-list nav-icon"></i>
                  <p>@lang('fleet.manage_vehicle_types')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/driver-logs')}}" class="nav-link @if(Request::is('admin/driver-logs*')) active @endif">
                  <i class="fa fa-history nav-icon"></i>
                  <p>@lang('fleet.driver_logs')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('vehicle_group.index')}}" class="nav-link @if(Request::is('admin/vehicle_group*')) active @endif">
                  <i class="fa fa-inbox nav-icon"></i>
                  <p>@lang('fleet.manageGroup')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/vehicle-docs')}}" class="nav-link @if(((Request::is('admin/vehicle-docs*')) || (Request::is('admin/vehicle-docs-report*'))) && !Request::is('admin/vehicle-docs/report') && !Request::is('admin/vehicle-docs/upcoming-renewal-report')) active @endif">
                  <i class="fa fa-id-card-o nav-icon"></i>
                  <p>@lang('fleet.vehicle_docs')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/vehicle-reviews')}}" class="nav-link @if((Request::is('admin/vehicle-reviews*')) || (Request::is('admin/view-vehicle-review*')) || (Request::is('admin/vehicle-review*'))) active @endif">
                  <i class="fa fa-briefcase nav-icon"></i>
                  <p>@lang('fleet.vehicle_inspection')</p>
                </a>
              </li>
            </ul>
          </li> @endif

            @if((Request::is('admin/income')) || (Request::is('admin/expense')) || (Request::is('admin/transaction')) || (Request::is('admin/income_records')) || (Request::is('admin/expense_records')) )
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          {{-- @if(in_array(2,$modules)) <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-money"></i>
              <p>
                @lang('menu.transactions')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('income.index')}}" class="nav-link @if((Request::is('admin/income'))|| (Request::is('admin/income_records'))) active @endif">
                  <i class="fa fa-newspaper-o nav-icon"></i>
                  <p>@lang('fleet.manage_income')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('expense.index')}}" class="nav-link @if((Request::is('admin/expense')) || (Request::is('admin/expense_records'))) active @endif">
                  <i class="fa fa-newspaper-o nav-icon"></i>
                  <p>@lang('fleet.manage_expense')</p>
                </a>
              </li>
            </ul>
          </li> @endif --}}

            @if((Request::is('admin/bookings*'))  || (Request::is('admin/bookings_calendar')) || (Request::is('admin/booking-quotation*')))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")

            @endif
          @if(in_array(3,$modules)) <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-address-card"></i>
              <p>
                @lang('menu.bookings')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('bookings.create')}}" class="nav-link @if(Request::is('admin/bookings/create')) active @endif">
                  <i class="fa fa-address-book nav-icon "></i>
                  <p>
                  @lang('menu.newbooking')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('bookings.index')}}" class="nav-link @if((Request::is('admin/bookings*')) && !(Request::is('admin/bookings/create')) && !(Request::is('admin/bookings_calendar'))) active @endif">
                  <i class="fa fa-tasks nav-icon"></i>
                  <p>
                  @lang('menu.manage_bookings')</p>
                </a>
              </li>
              {{-- <li class="nav-item">
                <a href="{{ route('booking-quotation.index')}}" class="nav-link @if(Request::is('admin/booking-quotation*')) active @endif">
                  <i class="fa fa-quote-left nav-icon"></i>
                  <p>
                  @lang('fleet.booking_quotes')</p>
                </a>
              </li> --}}
              <li class="nav-item">
                <a href="{{ route('bookings.calendar')}}" class="nav-link @if(Request::is('admin/bookings_calendar')) active @endif">
                  <i class="fa fa-calendar nav-icon"></i>
                  <p>
                  @lang('menu.calendar')</p>
                </a>
              </li>
            </ul>
          </li> @endif

            @if(Request::is('admin/reports*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          @if(in_array(4,$modules)) <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-book"></i>
              <p>
                @lang('menu.reports')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if(in_array(3,$modules))
              <li class="nav-item">
                <a href="{{ route('reports.statement') }}" class="nav-link @if(Request::is('admin/reports/statement')) active @endif">
                  <i class="fa fa-newspaper-o nav-icon"></i>
                  <p>Account Statement</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.booking') }}" class="nav-link @if(Request::is('admin/reports/booking')) active @endif">
                  <i class="fa fa-book nav-icon"></i>
                  <p>@lang('menu.bookingReport')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.customerPayment') }}" class="nav-link @if(Request::is('admin/reports/customer-payment')) active @endif">
                  <i class="fa fa-book nav-icon"></i>
                  <p>@lang('menu.customerPayment')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.vehicle-docs') }}" class="nav-link @if(Request::is('admin/reports/vehicle-docs')) active @endif">
                  <i class="fa fa-book  nav-icon"></i>
                  <p>Documents Renew</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.drivers-advance-report')}}" class="nav-link @if(Request::is('admin/reports/drivers-advance-report')) active @endif">
                  <i class="fa fa-file-text-o nav-icon"></i>
                  <p>Driver Advance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.drivers-report')}}" class="nav-link @if(Request::is('admin/reports/drivers-report')) active @endif">
                  <i class="fa fa-id-card nav-icon"></i>
                  <p>Driver Payroll</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.fuel') }}" class="nav-link @if(Request::is('admin/reports/fuel')) active @endif">
                  <i class="fa fa-truck nav-icon"></i>
                  <p>@lang('fleet.fuelReport')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.fuel-type') }}" class="nav-link @if(Request::is('admin/reports/fuel-type')) active @endif">
                  <i class="fa fa-exclamation nav-icon"></i>
                  <p>Fuel Type Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.leave')}}" class="nav-link @if(Request::is('admin/reports/leave')) active @endif">
                  <i class="fa fa-book nav-icon"></i>
                  <p>@lang('menu.leave_report')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.other-adjust')}}" class="nav-link @if(Request::is('admin/reports/other-adjust')) active @endif">
                  <i class="fa fa-file-text-o tag nav-icon"></i>
                  <p>Other Adjust</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.other-advance')}}" class="nav-link @if(Request::is('admin/reports/other-advance')) active @endif">
                  <i class="fa fa-file-text-o tag nav-icon"></i>
                  <p>Other Advance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.service-reminder') }}" class="nav-link @if(Request::is('admin/reports/service-reminder')) active @endif">
                  <i class="fa fa-file-text-o  nav-icon"></i>
                  <p>Reminder Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.salary-advance')}}" class="nav-link @if(Request::is('admin/reports/salary-advance*')) active @endif">
                  <i class="fa fa-file-text-o tag nav-icon"></i>
                  <p>Salary Advance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.salary-processing') }}" class="nav-link @if(Request::is('admin/reports/salary-processing')) active @endif">
                  <i class="fa fa-paper-plane nav-icon"></i>
                  <p>@lang('menu.salary_processing')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.salary-report') }}" class="nav-link @if(Request::is('admin/reports/salary-report')) active @endif">
                  <i class="fa fa-align-justify nav-icon"></i>
                  <p>@lang('menu.salary_report')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.stock') }}" class="nav-link @if(Request::is('admin/reports/stock')) active @endif">
                  <i class="fa fa-book nav-icon"></i>
                  <p>Stock Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.transaction')}}" class="nav-link @if(Request::is('admin/reports/transaction')) active @endif">
                  <i class="fa fa-file-text-o nav-icon"></i>
                  <p>@lang('menu.transactions_report')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.upcoming-report') }}" class="nav-link @if(Request::is('admin/reports/upcoming-renewal')) active @endif">
                  <i class="fa fa-book  nav-icon"></i>
                  <p>Upcoming Renew</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.vehicle-advance-report')}}" class="nav-link @if(Request::is('admin/reports/vehicle-advance-report')) active @endif">
                  <i class="fa fa-file-text-o nav-icon"></i>
                  <p>Vehicle Advance Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.vehicle-emi') }}" class="nav-link @if(Request::is('admin/reports/vehicle-emi')) active @endif">
                  <i class="fa fa-book nav-icon"></i>
                  <p>@lang('menu.vehicle_emi')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.vehicle-fuel-type') }}" class="nav-link @if(Request::is('admin/reports/vehicle-fuel-type')) active @endif">
                  <i class="fa fa-file-text-o nav-icon"></i>
                  <p>Vehicle Fuel Type</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.vehicles-overview') }}" class="nav-link @if(Request::is('admin/reports/vehicles-overview')) active @endif">
                  <i class="fa fa-file-text-o  nav-icon"></i>
                  <p>Vehicle Overview</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.vendorPayment') }}" class="nav-link @if(Request::is('admin/reports/vendor-payment')) active @endif">
                  <i class="fa fa-book nav-icon"></i>
                  <p>@lang('menu.vendorPayment')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.vendor-report') }}" class="nav-link @if(Request::is('admin/reports/vendor-report')) active @endif">
                  <i class="fa fa-file-text-o nav-icon"></i>
                  <p>Vendor Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('reports.vendor-work-order') }}" class="nav-link @if(Request::is('admin/reports/vendor-work-order')) active @endif">
                  <i class="fa fa-flag-o nav-icon"></i>
                  <p>Work-Vendor Report</p>
                </a>
              </li>
              @endif
            </ul>
          </li> @endif

            @if(Request::is('admin/fuel*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          @if(in_array(5,$modules)) <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-filter"></i>
              <p>
                @lang('fleet.fuel')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('fuel.create') }}" class="nav-link @if(Request::is('admin/fuel/create')) active @endif">
                  <i class="fa fa-plus-square nav-icon"></i>
                  <p> @lang('fleet.add_fuel')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('fuel.index') }}" class="nav-link @if(Request::is('admin/fuel*') && !Request::is('admin/fuel/create') && !Request::is('admin/fuel/report') && !Request::is('admin/fuel/report-vehicle')) active @endif">
                  <i class="fa fa-history nav-icon"></i>
                  <p>@lang('fleet.manage_fuel')</p>
                </a>
              </li>
            </ul>
          </li> @endif

            @if(Request::is('admin/vendors*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          @if(in_array(6,$modules)) <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-cubes"></i>
              <p>
                @lang('fleet.vendors')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('vendors.create') }}" class="nav-link @if(Request::is('admin/vendors/create') && !Request::is('admin/vendors/report')) active @endif">
                  <i class="fa fa-plus-square nav-icon"></i>
                  <p> @lang('fleet.add_vendor')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('vendors.index') }}" class="nav-link @if((Request::is('admin/vendors*') && !(Request::is('admin/vendors/create')) && !Request::is('admin/vendors/report'))) active @endif">
                  <i class="fa fa-cube nav-icon"></i>
                  <p>@lang('fleet.manage_vendor')</p>
                </a>
              </li>
            </ul>
          </li> @endif


            @if((Request::is('admin/parts*') || Request::is('admin/manufacturer*') || Request::is('admin/unit*')) && !Request::is('admin/parts-used*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
           @if(in_array(14,$modules))<li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-gears"></i>
              <p>
                @lang('fleet.parts')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              {{-- <li class="nav-item">
                <a href="{{ route('parts.create') }}" class="nav-link @if(Request::is('admin/parts/create')) active @endif">
                  <i class="fa fa-plus-square nav-icon"></i>
                  <p> @lang('fleet.addParts')</p>
                </a>
              </li> --}}
              <li class="nav-item">
                <a href="{{ route('parts.index') }}" class="nav-link @if(Request::is('admin/parts*') && !(Request::is('admin/parts-category*')) && !Request::is('admin/parts/create') && !Request::is('admin/parts-invoice*') && !Request::is('admin/parts-sell*')) active @endif"> 
                  <i class="fa fa-gears nav-icon"></i>
                  <p>@lang('menu.manageParts')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('parts-sell.index') }}" class="nav-link @if(Request::is('admin/parts-sell*')) active @endif">
                  <i class="fa fa-gears nav-icon"></i>
                  <p>@lang('menu.partSell')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('parts-invoice.index') }}" class="nav-link @if(Request::is('admin/parts-invoice*')) active @endif">
                  <i class="fa fa-gears nav-icon"></i>
                  <p>Manage @lang('menu.parts_inv')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('parts-category.index') }}" class="nav-link @if(Request::is('admin/parts-category*')) active @endif">
                  <i class="fa fa-list nav-icon"></i>
                  <p>@lang('fleet.partsCategory')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('unit.index') }}" class="nav-link @if(Request::is('admin/unit*')) active @endif">
                  <i class="fa fa-cubes nav-icon"></i>
                  <p>@lang('fleet.unit')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('manufacturer.index') }}" class="nav-link @if(Request::is('admin/manufacturer*')) active @endif">
                  <i class="fa fa-cube nav-icon"></i>
                  <p>@lang('fleet.manufacturer')</p>
                </a>
              </li>
            </ul>
          </li>@endif

            @if(Request::is('admin/work_order*') || Request::is('admin/parts-used*') || Request::is('admin/work-order-category*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          @if(in_array(7,$modules)) <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-shopping-cart"></i>
              <p>
                @lang('fleet.work_orders')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('work_order.create') }}" class="nav-link @if(Request::is('admin/work_order/create')) active @endif">
                  <i class="fa fa-plus-square nav-icon"></i>
                  <p> @lang('fleet.add_order')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('work_order.index') }}" class="nav-link @if((Request::is('admin/work_order*')) && !(Request::is('admin/work_order/create')) && !(Request::is('admin/work_order/logs')) && !Request::is('admin/work_order/report-vendor') || Request::is('admin/parts-used*')) active @endif">
                  <i class="fa fa-inbox nav-icon"></i>
                  <p>@lang('fleet.manage_work_order')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('work-order-category.index') }}" class="nav-link @if((Request::is('admin/work-order-category*'))) active @endif">
                  <i class="fa fa-level-down nav-icon"></i>
                  <p>Manage @lang('fleet.order_head')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/work_order/logs') }}" class="nav-link @if(Request::is('admin/work_order/logs') && !Request::is('admin/work_order/report-vendor')) active @endif">
                  <i class="fa fa-history nav-icon"></i>
                  <p>@lang('fleet.work_order_logs')</p>
                </a>
              </li>
            </ul>
          </li> @endif

            @if(Request::is('admin/notes*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          @if(in_array(8,$modules)) <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-sticky-note"></i>
              <p>
                @lang('fleet.notes')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('notes.index') }}" class="nav-link @if((Request::is('admin/notes*') && !(Request::is('admin/notes/create')))) active @endif">
                  <i class="fa fa-flag nav-icon"></i>
                  <p> @lang('fleet.manage_note')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route("notes.create") }}" class="nav-link @if(Request::is('admin/notes/create')) active @endif">
                  <i class="fa fa-plus-square nav-icon"></i>
                  <p>@lang('fleet.create_note')</p>
                </a>
              </li>
            </ul>
          </li> @endif

            @if((Request::is('admin/service-reminder*')) || (Request::is('admin/service-item*')))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          @if(in_array(9,$modules)) <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-clock-o"></i>
              <p>
                @lang('fleet.serviceReminders')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('service-reminder.index') }}" class="nav-link @if(Request::is('admin/service-reminder')) active @endif">
                  <i class="fa fa-arrows-alt nav-icon"></i>
                  <p>@lang('fleet.manage_reminder')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('service-reminder.create')}}" class="nav-link @if(Request::is('admin/service-reminder/create')) active @endif">
                  <i class="fa fa-check-square-o nav-icon"></i>
                  <p>@lang('fleet.add_service_reminder')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('service-item.index') }}" class="nav-link @if(Request::is('admin/service-item*')) active @endif">
                  <i class="fa fa-warning nav-icon"></i>
                  <p>@lang('fleet.service_item')</p>
                </a>
              </li>
            </ul>
          </li> @endif
            {{-- @if(Request::is('admin/testimonials*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          @if(in_array(15,$modules)) <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-quote-left"></i>
              <p>
                @lang('fleet.testimonials')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('testimonials.index') }}" class="nav-link @if((Request::is('admin/testimonials*') && !(Request::is('admin/testimonials/create')))) active @endif">
                  <i class="fa fa-tasks nav-icon"></i>
                  <p> @lang('fleet.manage_testimonial')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route("testimonials.create") }}" class="nav-link @if(Request::is('admin/testimonials/create')) active @endif">
                  <i class="fa fa-plus-square nav-icon"></i>
                  <p>@lang('fleet.add_testimonial')</p>
                </a>
              </li>
            </ul>
          </li> @endif --}}

          @endif 
          <!-- for user-type O or S -->
          @if(Auth::user()->user_type=="S")
            @if(Request::is('admin/team*'))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-users"></i>
              <p>
                @lang('fleet.team')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('team.index') }}" class="nav-link @if((Request::is('admin/team*') && !(Request::is('admin/team/create')))) active @endif">
                  <i class="fa fa-tasks nav-icon"></i>
                  <p> @lang('fleet.manage_team')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route("team.create") }}" class="nav-link @if(Request::is('admin/team/create')) active @endif">
                  <i class="fa fa-user-plus nav-icon"></i>
                  <p>@lang('fleet.addMember')</p>
                </a>
              </li>
            </ul>
          </li>
            @if(Request::is('admin/settings*') || Request::is('admin/fare-settings') || Request::is('admin/api-settings') || (Request::is('admin/expensecategories*')) || (Request::is('admin/incomecategories*')) || (Request::is('admin/expensecategories*')) || (Request::is('admin/send-email')) || (Request::is('admin/set-email')) || (Request::is('admin/cancel-reason*')) || (Request::is('admin/frontend-settings*')) || (Request::is('admin/company-services*')) || (Request::is('admin/payment-settings*')))
            @php($class="menu-open")
            @php($active="active")

            @else
            @php($class="")
            @php($active="")
            @endif
          <li class="nav-item has-treeview {{$class}}">
            <a href="#" class="nav-link {{$active}}">
              <i class="nav-icon fa fa-gear"></i>
              <p>
                @lang('menu.settings')
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('settings.index') }}" class="nav-link @if(Request::is('admin/settings')) active @endif">
                  <i class="fa fa-gear nav-icon"></i>
                  <p>@lang('menu.general_settings')</p>
                </a>
              </li>
              {{-- <li class="nav-item">
                <a href="{{ url('admin/api-settings')}}" class="nav-link @if(Request::is('admin/api-settings')) active @endif">
                  <i class="fa fa-gear nav-icon"></i>
                  <p>@lang('menu.api_settings')</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{ url('admin/payment-settings')}}" class="nav-link @if(Request::is('admin/payment-settings')) active @endif">
                  <i class="fa fa-gear nav-icon"></i>
                  <p>@lang('fleet.payment_settings')</p>
                </a>
              </li> --}}
              <li class="nav-item">
                <a href="{{ route('cancel-reason.index')}}" class="nav-link @if(Request::is('admin/cancel-reason*')) active @endif">
                  <i class="fa fa-ban nav-icon"></i>
                  <p>@lang('fleet.cancellation')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/send-email')}}" class="nav-link @if(Request::is('admin/send-email')) active @endif">
                  <i class="fa fa-envelope nav-icon"></i>
                  <p>@lang('menu.email_notification')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/set-email')}}" class="nav-link @if(Request::is('admin/set-email')) active @endif">
                  <i class="fa fa-envelope-open nav-icon"></i>
                  <p>@lang('menu.email_content')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/fare-settings')}}" class="nav-link @if(Request::is('admin/fare-settings')) active @endif">
                  <i class="fa fa-gear nav-icon"></i>
                  <p>@lang('menu.fare_settings')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('expensecategories.index') }}" class="nav-link @if(Request::is('admin/expensecategories*')) active @endif">
                  <i class="fa fa-tasks nav-icon"></i>
                  <p>@lang('menu.expenseCategories')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('incomecategories.index') }}" class="nav-link @if(Request::is('admin/incomecategories*')) active @endif">
                  <i class="fa fa-tasks nav-icon"></i>
                  <p>@lang('menu.incomeCategories')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/frontend-settings')}}" class="nav-link @if(Request::is('admin/frontend-settings')) active @endif">
                  <i class="fa fa-address-card nav-icon"></i>
                  <p>@lang('fleet.frontend_settings')</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/company-services')}}" class="nav-link @if(Request::is('admin/company-services*')) active @endif">
                  <i class="fa fa-tasks nav-icon"></i>
                  <p>@lang('fleet.companyServices')</p>
                </a>
              </li>
            </ul>
          </li>

          {{-- @if(in_array(12,$modules) && Hyvikk::api('api_key') != null) <li class="nav-item">
            <a href="{{ url('admin/driver-maps')}}" class="nav-link @if(Request::is('admin/driver-maps') || Request::is('admin/track-driver*')) active @endif">
              <i class="nav-icon fa fa-map"></i>
              <p>
                @lang('fleet.maps')
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li> @endif --}}
          @endif <!-- super-admin -->

          @if(Hyvikk::api('api') && Hyvikk::api('driver_review') == 1 && in_array(10,$modules)) <li class="nav-item">
            <a href="{{ url('admin/reviews')}}" class="nav-link @if(Request::is('admin/reviews')) active @endif">
              <i class="nav-icon fa fa-star"></i>
              <p>
                @lang('fleet.reviews')
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li> @endif
          @if(in_array(Auth::user()->user_type, ['S','O']))
          {{-- <li class="nav-item">
            <a href="{{ url('admin/messages')}}" class="nav-link @if(Request::is('admin/messages')) active @endif">
              <i class="nav-icon fa fa-comments"></i>
              <p>
                @lang('fleet.inquiries')
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>--}}
          @endif

          @if(in_array(13,$modules))
          {{-- <li class="nav-item">
            <a href="https://goo.gl/forms/PtzIirmT3ap8m5dY2" target="_blank" class="nav-link">
              <i class="nav-icon fa fa-comment"></i>
              <p>
                @lang('fleet.helpus')
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li> --}} @endif
          <!-- sidebar menus for office-admin and super-admin -->
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">@yield('heading') </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              @if(!(Request::is('admin')))
              <li class="breadcrumb-item"><a href="{{ url('admin/')}}">@lang('fleet.home')</a></li>
              @endif
              @yield('breadcrumb')
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>@lang('fleet.copyright') &copy; 2020-{{date("Y")}} <a href="https://hyvikk.com">ScriptX Technologies</a>.</strong>
    @lang('fleet.all_rights_reserved')
    <div class="float-right d-none d-sm-inline-block">
      <b>@lang('fleet.version')</b> 1.0.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
@yield('script2')
<!-- jQuery -->
<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- Bootstrap 4 -->
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('assets/plugins/fastclick/fastclick.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('assets/js/cdn/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/buttons.print.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/js/adminlte.js')}}"></script>

<script type="text/javascript">
$(document).ready(function() {
  $('#data_table tfoot th').each( function () {
    // console.log($('#data_table tfoot th').length);
    if($(this).index() != 0 && $(this).index() != $('#data_table tfoot th').length - 1) {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="'+title+'" />' );
    }
  });

  $('#data_table1 tfoot th').each( function () {
    // console.log($(this).index());
    if($(this).index() != 0 && $(this).index() != $('#data_table1 tfoot th').length - 1){
    var title = $(this).text();
    $(this).html( '<input type="text" placeholder="'+title+'" />' );
  }

  });

  var table1 = $('#data_table1').DataTable({
    // dom: 'Bfrtip',
    buttons: [
          {
        extend: 'print',
        text: '<i class="fa fa-print"></i> {{__("fleet.print")}}',

        exportOptions: {
           columns: ([1,2,3,4,5,6,7,8,9,10]),
        },
        customize: function ( win ) {
                $(win.document.body)
                    .css( 'font-size', '10pt' )
                    .prepend(
                        '<h3>{{__("fleet.bookings")}}</h3>'
                    );
                $(win.document.body).find( 'table' )
                    .addClass( 'table-bordered' );
                // $(win.document.body).find( 'td' ).css( 'font-size', '10pt' );

            }
          }
    ],
    "language": {
             "url": '{{ __("fleet.datatable_lang") }}',
          },
    columnDefs: [ { orderable: false, targets: [0] } ],
    // individual column search
   "initComplete": function() {
            table1.columns().every(function () {
              var that = this;
              $('input', this.footer()).on('keyup change', function () {
                  that.search(this.value).draw();
              });
            });
          }
  });

  var table = $('#data_table').DataTable({
    "language": {
        "url": '{{ __("fleet.datatable_lang") }}',
     },
     columnDefs: [ { orderable: false, targets: [0] } ],
     // individual column search
     "initComplete": function() {
              table.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                  // console.log($(this).parent().index());
                    that.search(this.value).draw();
                });
              });
            }
  });

  $('[data-toggle="tooltip"]').tooltip();

});
</script>
<script type="text/javascript" src="{{ asset('assets/js/pnotify.custom.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
{{-- <script src="{{ asset('assets/js/demo.js') }}"></script> --}}
@yield('script')
</body>
</html>