<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{Hyvikk::get('app_name')}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <!-- Bootstrap 3.3.7 -->
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cdn/bootstrap.min.css')}}" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/css/cdn/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link href="{{ asset('assets/css/cdn/ionicons.min.css')}}" rel="stylesheet">
  <!-- Theme style -->
   <link href="{{ asset('assets/css/AdminLTE.min.css') }}" rel="stylesheet">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="{{ asset('assets/css/cdn/fonts.css')}}">
  <style type="text/css">
    body {
      height: auto;
    }
  </style>
</head>
<body onload="window.print();">
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

  <div class="wrapper">
  <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
          <span class="logo-lg">
          <img src="{{ asset('assets/images/'. Hyvikk::get('icon_img') ) }}" class="navbar-brand" style="margin-top: -15px">
          {{  Hyvikk::get('app_name')  }}
          </span>

            <small class="pull-right"> <b>@lang('fleet.date') : </b> {{date($date_format_setting)}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Vehicle Overview Report</h3>
          @if(!empty($vehicle_id))
          <h4>{{$vehicle->make}}-{{$vehicle->model}}- <strong>{{$vehicle->license_plate}}</strong></h4>
          @endif
          <small>{{$from_date}} - {{$to_date}}</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover"  id="myTable1">
            {{-- Vehicle Overview Report --}}
            <tr>
              <td align="center" style="font-size:23px;">
                <strong>{{$vehicle->make}}-{{$vehicle->model}}-{{$vehicle->license_plate}}</strong>
                @if(!empty($vehicle->driver))
                  <br><span>{{ucwords(strtolower($vehicle->driver->assigned_driver->name))}}</span>
                @endif
                @if(!empty($vehicle->driver))
                  <h6>{{Helper::getCanonicalDate($from_date)}} - {{Helper::getCanonicalDate($to_date)}}</h6>
                @endif
              </td>
            </tr>
            {{-- Fuel,Booking,Driver Advance,Expenes, Income --}}
            <tr>
              <table class="table table-bordered table-striped">
                
                <thead>
                  <tr>
                    <td colspan="4" align="center" style="font-size:18px;font-weight: 600;">Bookings</td>
                  </tr>
                  <tr>
                    <th>No. of Booking(s)</th>
                    <th>Total KM</th>
                    <th>Total Fuel</th>
                    <th>Total Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @if($book->totalbooking!=0 && !empty($book->totalbooking))
                  <tr>
                    <td>{{$book->totalbooking}} bookings</td>
                    <td>{{$book->totalkms}} {{Hyvikk::get('dis_format')}}</td>
                    <td>{{$book->totalfuel}} {{Hyvikk::get('fuel_unit')}}</td>
                    <td>{{Hyvikk::get('currency')}} {{$book->totalprice}}</td>
                  </tr>
                  @else
                  <tr>
                    <td colspan="4" align='center' style="color: red">No Records Found...</td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </tr>
            <tr>
              <table class="table table-bordered table-striped">
                
                <thead>
                  <tr>
                    <td colspan="4" align="center" style="font-size:18px;font-weight: 600;">Fuel</td>
                  </tr>
                  <tr>
                    <th>Fuel Type</th>
                    <th>No. of Refuel(s)</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @if(!empty($fuels))
                  @foreach($fuels as $k=>$fs)
                  <tr>
                    <td>{{$k}}</td>
                    <td>{{count($fs->id)}} time(s)</td>
                    <td>{{array_sum($fs->ltr)}} {{ $k!='Lubricant' ? Hyvikk::get('fuel_unit') : 'pc'}}</td>
                    <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals(array_sum($fs->total))}}</td>
                  </tr>
                  @endforeach
                  @else
                  <tr>
                    <td colspan="4" align='center' style="color: red">No Records Found...</td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </tr>
            <tr>
              <table class="table table-bordered table-striped">
                
                <thead>
                  <tr>
                    <td colspan="3" align="center" style="font-size:18px;font-weight: 600;">Driver Advance</td>
                  </tr>
                </thead>
                <tbody>
                  @if(!empty($advances->details))
                  {{-- @foreach($advances as $k=>$ad) --}}
                  <tr>
                    {{-- <td rowspan="{{array_sum($advances->details)}}">{{$advances->times}} times</td>
                    <td>{{array_sum($advances->amount)}}</td> --}}
                    <td>
                      <table class="table tabl-bordered table-striped">
                        <thead>
                          <th>#</th>
                          <th>Head</th>
                          <th>No. of Time(s)</th>
                          <th>Amount</th>
                        </thead>
                        <tbody>
                          @foreach($advances->details as $k=>$det)
                          <tr>
                            <td>{{$k+1}}</td>
                            <td>{{$det->label}}</td>
                            <td>{{$det->times}}</td>
                            <td>{{Hyvikk::get('currency')}} {{!empty($det->amount) ? Helper::properDecimals($det->amount) : Helper::properDecimals(0)}}</td>
                          </tr>
                          @endforeach
                          <tr>
                            <th colspan="3" style="text-align:right;">Total</th>
                            <th>{{Hyvikk::get('currency')}} {{!empty($advances->amount) ? Helper::properDecimals(array_sum($advances->amount)) : Helper::properDecimals(0)}}</th>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                  {{-- @endforeach --}}
                  @else
                  <tr>
                    <td colspan="4" align='center' style="color: red">No Records Found...</td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </tr>
            <tr>
              <table class="table table-bordered table-striped">
                
                <thead>
                  <tr>
                    <td colspan="6" align="center" style="font-size:18px;font-weight: 600;">Work Order</td>
                  </tr>
                  <tr>
                    <th>No. of Work Order(s)</th>
                    <th>GST</th>
                    <th>Total</th>
                    <th>No. of Vendors</th>
                    <th>Status</th>
                    <th>Parts Used</th>
                  </tr>
                </thead>
                <tbody>
                  @if(!empty($wo->count) && $wo->count!=0)
                  {{-- @foreach($wo as $k=>$w) --}}
                  <tr>
                    <td>{{$wo->count}}</td>
                    <td>
                      <table class="table table-striped">
                        <tr>
                          <th>CGST</th>
                          <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($wo->cgst)}}</td>
                        </tr>
                        <tr>
                          <th>SGST</th>
                          <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($wo->sgst)}}</td>
                        </tr>
                      </table>
                    </td>
                    <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($wo->grand_total)}}</td>
                    <td>{{$wo->vendors}}</td>
                    <td>
                      <table class="table table-striped">
                        @foreach($wo->status as $k=>$s)
                        <tr>
                          <th>{{$k}}</th>
                          <td>{{count($s)}}</td>
                        </tr>
                        @endforeach
                      </table>
                    </td>
                    <td>
                      <table class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Part</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if(empty($partsUsed))
                          @foreach($partsUsed as $pu)
                          <tr>
                            <td>{{$pu->part->title}}</td>
                            <td>{{$pu->qty}}</td>
                            <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($pu->total)}}</td>
                          </tr>
                          @endforeach
                          @else
                          <tr>
                            <td colspan="3" align='center' style="color: red">No Parts Used...</td>
                          </tr>
                          @endif
                        </tbody>
                      </table>
                    </td>
                  </tr>
                  {{-- @endforeach --}}
                  @else
                  <tr>
                    <td colspan="6" align='center' style="color: red">No Records Found...</td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </tr>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html>