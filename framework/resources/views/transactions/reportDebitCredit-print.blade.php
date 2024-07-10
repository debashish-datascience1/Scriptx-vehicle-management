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

            {{-- <small class="pull-right"> <b>@lang('fleet.date') : </b> {{date($date_format_setting)}}</small> --}}
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Transaction Details Report</h3>
           {{-- <small>{{Helper::getCanonicalDate($date['from_date'])}}</small> to
          <small>{{Helper::getCanonicalDate($date['to_date'])}}</small>   --}}
         
          
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-hover">
           
    <thead class="thead-inverse">
        <thead>
            <tr>
              <th>SL#</th>
              <th>Date</th>
              <th>Head</th>
              <th>Towards</th>
              <th>Method</th>
              <th>Payment Type</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
             @foreach($transaction as $k=>$row) 
            <tr>
              <td>{{$k+1}}</td>
              <td nowrap>
                {{Helper::getCanonicalDate($row->dateof,'default')}}
              </td>
              <td>
                @if($row->param_id==18)
                  <a class="badge badge-success where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="@lang('fleet.view')">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  {{-- <span class="badge badge-success">{{!empty($row->params) ? $row->params->lable : 'N/A'}}</span> --}}
                  <br>
                  @if($row->advance_for==21)
                  <a class="badge badge-warning advance_for" data-id="{{$row->id}}" data-toggle="modal" data-target="#advanceForModal" title="@lang('fleet.view')">{{$row->advancefor->label}}</a>
                  {{-- <span class="badge badge-warning">{{$row->advancefor->label}}</span> --}}
                  @endif
                @elseif($row->param_id==19)
                  <a class="badge badge-info where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                @elseif($row->param_id==20)
                  <a class="badge badge-fuel where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                @elseif($row->param_id==25)
                  <a class="badge badge-driver-adv where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                @elseif($row->param_id==26)
                  <a class="badge badge-parts where_from" data-id="{{$row->id}}" data-partsw={{$row->id}} data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                @elseif($row->param_id==27)
                  <a class="badge badge-refund where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                @elseif($row->param_id==28)
                  <a class="badge badge-info where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                @elseif($row->param_id==29)
                  <a class="badge badge-starting-amt where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                @elseif($row->param_id==30)
                  <a class="badge badge-starting-amt where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                  @elseif($row->param_id==30)
                  <a class="badge badge-deposit where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                @elseif($row->param_id==31)
                  <a class="badge badge-revised where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                @elseif($row->param_id==32)
                  <a class="badge badge-liability where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                @elseif($row->param_id==35)
                  <a class="badge badge-renewdocs where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a><br>
                  <span class="badge badge-vehicleDoc">{{$row->vehicle_document->document->label}}</span>
                @elseif($row->param_id==43)
                  <a class="badge badge-otherAdv where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                @elseif($row->param_id==44)
                  <a class="badge badge-advanceRefund where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                @elseif($row->param_id==49)
                  <a class="badge badge-vehicle-downpayment where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a><br>
                  <span class="badge badge-vehicle-purchase">Vehicle Purchase</span>
                @elseif($row->param_id==50)
                  <a class="badge badge-vehicle-emi where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a><br>
                  <span class="badge badge-vehicle-purchase">Vehicle Purchase</span>
                @else{{dd($row->param_id)}}
               @endif</td>
              <td>
                <strong>({{$row->transaction_id}})</strong>
                @if (!empty($row->org))
                    <br>{{$row->org}}
                @endif
              </td>
              <td>{{$row->incExp->method->label}}</td>
              <td>{{$row->pay_type->label}}</td>
              <td nowrap>{{bcdiv($row->total,1,2)}}</td>
            </tr>
            @endforeach
            <tr>
              <th colspan="5"></th>
              <th>Grand Total</th>
              <th nowrap>{{Hyvikk::get('currency')}} {{bcdiv($sumoftotal,1,2)}}</th>
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