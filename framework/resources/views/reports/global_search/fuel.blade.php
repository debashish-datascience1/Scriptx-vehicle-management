<table class="table" id="myTable">
    <thead class="thead-inverse">
      <tr>
        <th>SL#</th>
        <th>Vehicle</th>
        <th>@lang('fleet.date')</th>
        <th>@lang('fleet.fuelType')</th>
        <th>@lang('fleet.qty')</th>
        <th>@lang('fleet.cost')</th>
        <th>CGST</th>
        <th>SGST</th>
        <th>Total</th>
        <th>@lang('fleet.action')</th>
      </tr>
    </thead>
    <tbody>
      @foreach($collection as $k=>$row)
      <tr>
        <td>{{$k+1}}</td>
        <td>
          {{-- @if($row->vehicle_data['vehicle_image'] != null)
            <img src="{{asset('uploads/'.$row->vehicle_data['vehicle_image'])}}" height="70px" width="70px">
          @else
            <img src="{{ asset("assets/images/vehicle.jpeg")}}" height="70px" width="70px">
          @endif --}}
          <strong>{{date('d-M-Y',strtotime($row->vehicle_data['year']))}}</strong><br>
          <a href="{{ url("admin/vehicles/".$row->vehicle_id."/edit")}}"  target="_blank">
          {{-- <span class="badge badge-success"></span> --}}
          {{$row->vehicle_data['make']}}-{{$row->vehicle_data['model']}}
          </a>
          <br>
          @if($row->vehicle_data['vehicle_image'] != null)
            <a href="{{asset('uploads/'.$row->vehicle_data['vehicle_image'])}}" target="_blank" class="badge badge-danger">{{strtoupper($row->vehicle_data['license_plate'])}}</a>
          @else
            <a href="#" target="_blank" class="badge badge-danger">{{strtoupper($row->vehicle_data['license_plate'])}}</a>
          @endif
          <br>
        </td>
        <td>
          <strong>{{!empty(Helper::getTransaction($row->id,20)) ? Helper::getTransaction($row->id,20)->transaction_id : ''}}</strong>
          <br>
          {{Helper::getCanonicalDate($row->date,'default')}}
          <br>
          {{$row->province}}
        </td>
        <td>
          @if($row->fuel_details!='')
            {{$row->fuel_details->fuel_name}}
          @else
            <small style="color:red">specify fuel type</small>
          @endif
        </td>
        <td> {{$row->qty}} @if(Hyvikk::get('fuel_unit') == "gallon") @lang('fleet.gal') @else Liter @endif </td>
        <td>
          @php ($total = $row->qty * $row->cost_per_unit)
          {{ Hyvikk::get('currency') }} {{$total}}
          <br>
          {{ Hyvikk::get('currency') }} {{$row->cost_per_unit}}/ {{ Hyvikk::get('fuel_unit') }}
        </td>
        <td>
          @if(!empty($row->cgst))
          {{$row->cgst}} %
          <br>
          {{ Hyvikk::get('currency') }} {{$row->cgst_amt}}
          @endif
        </td>
        <td>
          @if(!empty($row->sgst))
          {{$row->sgst}} %
          <br>
          {{ Hyvikk::get('currency') }} {{$row->sgst_amt}}
          @endif
        </td>
        <td>
          @if(!empty($row->grand_total))
          {{ Hyvikk::get('currency') }} {{$row->grand_total}}
          @endif
        </td>
        {{-- <td>
          @lang('fleet.start'): {{$row->start_meter}} {{Hyvikk::get('dis_format')}}
          <br>
          @lang('fleet.end'): {{$row->end_meter}} {{Hyvikk::get('dis_format')}}
          <br>
          @lang('fleet.distence'):

          @if($row->end_meter == 0)
          0.00 {{Hyvikk::get('dis_format')}}
          @else
          {{$row->end_meter - $row->start_meter}}  {{Hyvikk::get('dis_format')}}
          @endif
        </td> --}}
        {{-- <td>
          {{ $row->consumption }}
          @if(Hyvikk::get('dis_format') == "km")
          @if(Hyvikk::get('fuel_unit') == "gallon")KMPG @else KMPL @endif
          @else
          @if(Hyvikk::get('fuel_unit') == "gallon")MPG @else MPL @endif
          @endif
        </td> --}}
        <td>
        <div class="btn-group">
          <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
            <span class="fa fa-gear"></span>
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu custom" role="menu">
            <a class="dropdown-item vview" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal2"> <span aria-hidden="true" class="fa fa-eye" style="color: #398439;"></span> @lang('fleet.view')</a>
            @if(Helper::isEligible($row->id,20))
            <a class="dropdown-item" href="{{ url("admin/fuel/".$row->id."/edit")}}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
            <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
            @endif
          </div>
        </div>
        {!! Form::open(['url' => 'admin/fuel/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}
        {!! Form::hidden("id",$row->id) !!}
        {!! Form::close() !!}
        </td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
          <th>SL#</th>
          <th>Vehicle</th>
          <th>@lang('fleet.date')</th>
          <th>@lang('fleet.fuelType')</th>
          <th>@lang('fleet.qty')</th>
          <th>@lang('fleet.cost')</th>
          <th>CGST</th>
          <th>SGST</th>
          <th>Total</th>
          <th>@lang('fleet.action')</th>
      </tr>
    </tfoot>
  </table>