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
{{-- @php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y') --}}

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
          <small class="pull-right"> <b>@lang('fleet.date') : </b> {{Helper::getCanonicalDateTime($date,'default')}} / {{Helper::getCanonicalDateTime($date)}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Vendor Payment Report</h3>
          @if(!empty($vendor_data))
            <h4>{{$vendor_data->name}}</h4>
          @endif
          <small>{{Helper::getCanonicalDate($from_date,'default')}} - {{Helper::getCanonicalDate($to_date,'default')}}</small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <span style="float: right;font-weight:700"> Opening Balance : {{Hyvikk::get('currency')}} {{bcdiv($opening_balance,1,2)}}</span> 
          <table class="table table-bordered table-striped table-hover">
           
    <thead class="thead-inverse">
        <thead>
            <tr>
              <th>SL#</th>
              <th>Date</th>
              <th>Ref. No.</th>
              <th>Particulars</th>
              <th>Debit</th>
              <th>Credit</th>
              <th>Balance</th>
            </tr>
          </thead>
          <tbody>
             @foreach($transactions as $k=>$row) 
            <tr>
              <td>{{$k+1}}</td>
              <td nowrap>{{Helper::getCanonicalDate($row->date,'default')}}</td>
              <td nowrap>{{$row->transaction_id}}</td>
              <td>
                @if($row->param_id==20)
                  {{-- @if (empty($row->fuel))
                      {{dd($row)}}
                  @endif --}}
                  @if ($row->is_bulk!=1)    
                    {{$row->fuel->qty}} ltr {{$row->fuel->fuel_details->fuel_name}} {{$row->fuel->cost_per_unit}} per unit total of  {{bcdiv($row->fuel->qty * $row->fuel->cost_per_unit,1,2)}} fuel filled for {{$row->fuel->vehicle_data->license_plate}} 
                  @endif
                  @if ($row->is_bulk==1) 
                    {{-- {{dd($row->bulk_data)}} --}}
                    Bulk Paid towards Fuel
                    {{-- {{$row->fuel->qty}} ltr {{$row->fuel->fuel_details->fuel_name}} {{$row->fuel->cost_per_unit}} per unit total of  {{bcdiv($row->fuel->qty * $row->fuel->cost_per_unit,1,2)}} fuel filled for {{$row->fuel->vehicle_data->license_plate}}  --}}
                  @endif
                @elseif($row->param_id==26)
                  @if ($row->is_bulk!=1)    
                   {{$row->parts->partsDetails->count()}} items added to sum total of {{$row->parts->partsDetails->sum('total')}}
                  @endif
                  @if ($row->is_bulk==1) 
                    Bulk Paid towards PartsInvoice
                  @endif
                @elseif($row->param_id==28)
                  @if ($row->is_bulk!=1)    
                    WorkOrder having bill {{$row->workorders->bill_no}} amount {{Hyvikk::get('currency')}} {{bcdiv($row->workorders->grand_total,1,2)}} paid to {{$row->workorders->vendor->name}} for {{$row->workorders->vehicle->license_plate}}
                  @endif
                  @if ($row->is_bulk==1) 
                    Bulk Paid towards WorkOrder
                  @endif
                @else
                  {{dd($row)}}
                @endif
              </td>
              <td>
                @if($row->is_bulk!=1)
                    {{bcdiv($row->total,1,2)}}
                @else
                  -
                @endif
              </td>
              <td>
                @if($row->is_bulk==1)
                   {{bcdiv($row->total,1,2)}}
                @else
                  -
                @endif
              </td>
              <td>{{$row->new_total}}</td>
            </tr>
            @endforeach
            <tr>
              <th colspan="3"></th>
              <th>A/C TOTAL</th>
              <th nowrap>{{Hyvikk::get('currency')}} {{bcdiv($transactions->where('is_bulk',null)->sum('total'),1,2)+$vendor_data->opening_balance}}</th>
              <th nowrap>{{Hyvikk::get('currency')}} {{bcdiv($transactions->where('is_bulk',1)->sum('total'),1,2)}}</th>
              <th nowrap>{{Hyvikk::get('currency')}} {{bcdiv($transactions->reverse()->first()->new_total,1,2)}}</th>
          </tr>
          </tbody>
            <tfoot>
          </table>
        </div>
      </div>
    </section>
  </div>
<!-- ./wrapper -->
</body>
</html>