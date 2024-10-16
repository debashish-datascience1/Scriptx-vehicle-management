@extends('layouts.app')
@section('breadcrumb')
<li class="breadcrumb-item active">@lang('fleet.fastag')</li>
@endsection
@section('extra_css')
<style type="text/css">
  .checkbox, #chk_all{
    width: 20px;
    height: 20px;
  }
</style>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        @lang('fleet.fastag')
        &nbsp;
        <a href="{{ route('fastag.create')}}" class="btn btn-success">Add Fastag Entry</a>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th>Fastag</th>
              <th>Entries</th>
              <th>Grand Total</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </thead>
          <tbody>
          @foreach($paginatedData as $fastagGroup)
            <tr>
              <td>{{ $fastagGroup['fastag'] }}</td>
              <td>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Toll Gate Name</th>
                      <th>Amount</th>
                      <th>Vehicle</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($fastagGroup['entries'] as $entry)
                      <tr>
                        <td>{{ $entry->date }}</td>
                        <td>{{ $entry->toll_gate_name }}</td>
                        <td>{{ number_format($entry->amount, 2) }}</td>
                        <td>{{ $entry->registration_number }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </td>
              <td>{{ number_format($fastagGroup['total'], 2) }}</td>
              <td>
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item" href="{{ route('fastag.edit', $fastagGroup['entries']->first()->id) }}">
                      <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')
                    </a>
                    {!! Form::open(['url' => 'admin/fastag/'.$fastagGroup['entries']->first()->id, 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'form_'.$fastagGroup['entries']->first()->id]) !!}
                    <a class="dropdown-item" data-id="{{$fastagGroup['entries']->first()->id}}" data-toggle="modal" data-target="#myModal">
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
        {{ $paginatedData->links() }}
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
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