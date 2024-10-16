@extends('layouts.app')
@section('breadcrumb')
<li class="breadcrumb-item active">@lang('fleet.loan_take')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        @lang('fleet.loan_take')
        &nbsp;
        <a href="{{ route('loan-take.create')}}" class="btn btn-success">@lang('fleet.create_loan_take')</a>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th>@lang('fleet.date')</th>
              <th>@lang('fleet.from')</th>
              <th>@lang('fleet.amount')</th>
              <th>@lang('fleet.remaining_amount')</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($loanTakes as $loanTake)
            <tr>
              <td>{{ $loanTake->date }}</td>
              <td>{{ $loanTake->from }}</td>
              <td>{{ number_format($loanTake->amount, 2) }}</td>
              <td>{{ number_format($loanTake->remaining_amount, 2) }}</td>
              <td>
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item" href="{{ route('loan-take.show', $loanTake->id) }}">
                      <span aria-hidden="true" class="fa fa-eye" style="color: #398439;"></span> @lang('fleet.details')
                    </a>
                    <a class="dropdown-item" href="{{ route('loan-take.edit', $loanTake->id) }}">
                      <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')
                    </a>
                    <a class="dropdown-item" href="{{ route('loan-take.return', $loanTake->id) }}">
                      <span aria-hidden="true" class="fa fa-undo" style="color: #3c8dbc;"></span> @lang('fleet.return')
                    </a>
                    {!! Form::open(['url' => 'admin/loan-take/'.$loanTake->id, 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'form_'.$loanTake->id]) !!}
                    <a class="dropdown-item" data-id="{{$loanTake->id}}" data-toggle="modal" data-target="#myModal">
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
        {{ $loanTakes->links() }}
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