@extends("layouts.app")
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("work_order.index")}}">@lang('fleet.work_orders') </a></li>
<li class="breadcrumb-item active">@lang('fleet.partsUsed')</li>
@endsection
@section('extra_css')
<style>
  .item-put{cursor: pointer;}
</style>
@endsection
@section('script')
    <script>
      $(".data_table").DataTable();
      $(".item-put").click(function(){
        var id = $(this).data("id");
        // console.log(id);
        $("#myModal2 .modal-body").load('{{url("admin/work_order/itemno_get")}}/'+id,function(result){
          $('#myModal2').modal({show:true});
        })
      })
    </script>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        Own Parts Used
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th>@lang('fleet.vehicle')</th>
              <th>@lang('fleet.description')</th>
              <th>@lang('fleet.part')</th>
              <th>@lang('fleet.qty')</th>
              <th>@lang('fleet.unit_cost')</th>
              <th>@lang('fleet.part_price')</th>
              <th>@lang('fleet.total_cost')</th>
            </tr>
          </thead>
          <tbody>
          @foreach($order->part_fromown as $row)
            <tr>
              <td>{{ $row->workorder->vehicle->license_plate }}</td>
              <td>{!! $row->workorder->description !!}</td>
              <td>
                @if(!empty($row->part) && !empty($row->part->category) && $row->part->category->is_itemno==1)
                  <a class="item-put" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal2"><strong>{{ Helper::getFullPartName($row->part->id) }}</strong></a>
                @else
                  {{ Helper::getFullPartName($row->part->id) }}
                @endif
              </td>
              <td>{{ $row->qty }}</td>
              <td>{{ Hyvikk::get('currency')." ". $row->price }}</td>
              <td>{{Hyvikk::get('currency')." ". $row->total }}</td>
              <td>{{ Hyvikk::get('currency')." ". $row->grand_total }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        Vendor Parts Used
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table data_table">
          <thead class="thead-inverse">
            <tr>
              <th>@lang('fleet.vehicle')</th>
              <th>@lang('fleet.description')</th>
              <th>@lang('fleet.part')</th>
              <th>@lang('fleet.qty')</th>
              <th>@lang('fleet.unit_cost')</th>
              <th>@lang('fleet.part_price')</th>
              <th>@lang('fleet.cgst')</th>
              <th>@lang('fleet.cgst_amt')</th>
              <th>@lang('fleet.sgst')</th>
              <th>@lang('fleet.sgst_amt')</th>
              <th>@lang('fleet.total_cost')</th>
            </tr>
          </thead>
          <tbody>
          @foreach($order->parts_fromvendor as $row)
            <tr>
              <td>{{ $row->workorder->vehicle->license_plate }}</td>
              <td>{!! $row->workorder->description !!}</td>
              <td>
                @if(!empty($row->part) && !empty($row->part->category) && $row->part->category->is_itemno==1)
                  <a class="item-put" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal2"><strong>{{ Helper::getFullPartName($row->part->id) }}</strong></a>
                @else
                  {{ Helper::getFullPartName($row->part->id) }}
                @endif
              </td>
              <td>{{ $row->qty }}</td>
              <td>{{ Hyvikk::get('currency')." ". $row->price }}</td>
              <td>{{Hyvikk::get('currency')." ". $row->total }}</td>
              <td>{{$row->cgst}} %</td>
              <td>{{Hyvikk::get('currency')." ". $row->cgst_amt }}</td>
              <td>{{$row->sgst}} %</td>
              <td>{{Hyvikk::get('currency')." ". $row->sgst_amt }}</td>
              <td>{{ Hyvikk::get('currency')." ". $row->grand_total }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Item No.</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          Loading..
        </div>
        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            @lang('fleet.close')
          </button>
        </div> --}}
      </div>
    </div>
  </div>
</div>
@endsection