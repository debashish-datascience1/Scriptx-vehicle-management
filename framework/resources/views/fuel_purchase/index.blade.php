@extends('layouts.app')
@section('breadcrumb')
<li class="breadcrumb-item active">@lang('fleet.fuel_purchase')</li>
@endsection

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card-info {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }
        #data_table, #data_table * {
            visibility: visible !important;
        }
        .btn, .card-header .btn, .pagination, #data_table th:last-child, #data_table td:last-child {
            display: none !important;
        }
        @page {
            size: auto;
            margin: 10mm;
        }
    }
</style>

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        @lang('fleet.fuel_purchase')
        &nbsp;
        <a href="{{ route('fuel_purchase.create') }}" class="btn btn-success">@lang('fleet.add_fuel_purchase')</a>
        <button onclick="window.print()" class="btn btn-secondary ml-2">
            <i class="fa fa-print"></i> @lang('fleet.print')
        </button>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th>@lang('fleet.date')</th>
              <th>@lang('fleet.vendor')</th>
              <th>@lang('fleet.quantity')</th>
              <th>@lang('fleet.amount')</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </thead>
          <tbody>
          @foreach($fuel_purchases as $purchase)
            <tr>
              <td>{{ $purchase->date }}</td>
              <td>{{ $purchase->vendor->name ?? 'N/A' }}</td>
              <td>{{ number_format($purchase->quantity, 2) }}</td>
              <td>{{ number_format($purchase->amount, 2) }}</td>
              <td>
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item" href="{{ route('fuel_purchase.edit', $purchase->id) }}">
                      <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')
                    </a>
                    {!! Form::open(['url' => 'admin/fuel_purchase/'.$purchase->id, 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'form_'.$purchase->id]) !!}
                    <a class="dropdown-item" data-id="{{$purchase->id}}" data-toggle="modal" data-target="#myModal">
                      <span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')
                    </a>
                    {!! Form::close() !!}
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
        {{ $fuel_purchases->links() }}
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.delete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>@lang('fleet.confirm_delete')</p>
      </div>
      <div class="modal-footer">
        <button id="del_btn" class="btn btn-danger" type="button" data-submit="">@lang('fleet.delete')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#form_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });
</script>
@endsection