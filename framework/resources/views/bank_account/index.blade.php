@extends("layouts.app")
@section("breadcrumb")
<li class="breadcrumb-item active">@lang('fleet.bankAccount')</li>
@endsection
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
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
    @if (count($errors) > 0)
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        @lang('fleet.bankAccount')
        &nbsp;
        @if(Auth::user()->user_type == "S")
            <a href="{{ route('bank-account.create')}}" class="btn btn-success">Add Bank Account</a>
        @endif
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th>
              @if($bankAccount->count() > 0)
                <input type="checkbox" id="chk_all">
              @endif
              </th>
              <th>Bank</th>
              <th>Account</th>
              <th>Holder</th>
              <th>Starting Amount</th>
              {{-- <th>Current Balance</th> --}}
              <th>Contact</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </thead>
          <tbody>
          @foreach($bankAccount as $row)
            <tr>
              <td>
                <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();'>
              </td>
              <td>{{$row->bank}}</td>
              <td>{{$row->account_no}}</td>
              <td>{{$row->account_holder}}</td>
              <td style="font-weight: 600">{{Hyvikk::get('currency')}} {{bcdiv($row->starting_amount,1,2)}}</td>
              {{-- <td style="font-weight: 600">{{Hyvikk::get('currency')}} {{Helper::accountBalance($row->id)}}</td> --}}
              <td>{{$row->mobile}}</td>
              <td>
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-gear"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item mybtn vevent" data-id="{{$row->id}}" data-toggle="modal" data-target="#viewModal" title="@lang('fleet.view')"><i class="fa fa-eye" aria-hidden="true" style="color:#269abc;"></i> @lang('fleet.view')</a>
                  @if($row->id!=1)
                  <a class="dropdown-item" href="{{url("admin/bank-account/".$row->id."/edit") }}"><span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                  {!! Form::hidden("id",$row->id) !!}
                  {{-- <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a> --}}
                  @endif
                  @if($row->id!=1)
                  <a class="dropdown-item vaddamt" data-id="{{$row->id}}" data-toggle="modal" data-target="#addAmount"><span aria-hidden="true" class="fa fa-plus" style="color: #1872da"></span> Add Amount</a>
                  @endif
                </div>
              </div>
                {!! Form::open(['url' => 'admin/bank-account/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}
                {!! Form::hidden("id",$row->id) !!}
                {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>
              {{-- @if($bankAccount->count() > 0)
                <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled>@lang('fleet.delete')</button>
              @endif --}}
              </th>
              <th>Bank</th>
              <th>Account</th>
              <th>Holder</th>
              <th>Starting Amount</th>
              {{-- <th>Current Balance</th> --}}
              <th>Contact</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->

<!-- Modal -->

<!-- Modal -->
{{-- <div id="bulkModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.delete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url'=>'admin/bank-account','method'=>'POST','id'=>'form_delete']) !!}
        <div id="bulk_hidden"></div>
        <p>@lang('fleet.confirm_bulk_delete')</p>
      </div>
      <div class="modal-footer">
        <button id="bulk_action" class="btn btn-danger" type="submit" data-submit="">@lang('fleet.delete')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
        {!! Form::close() !!}
    </div>
  </div>
</div> --}}
<!-- Modal -->

<!-- Modal -->
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
<!-- Modal -->
<div id="addAmount" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width: 150%">
      <div class="modal-header">
        <h4 class="modal-title">Add Amount</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        {!! Form::open(['route' => 'addamount.store','files'=>true,'method'=>'post']) !!}
          <div class="container">
              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          {!!Form::label('bank','Bank',['class' => 'form-label'])!!}
                          {!!Form::select('bank',$banks,null,['class'=>'form-control','id'=>'bank','placeholder'=>'Select Bank','required'])!!}
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          {!!Form::label('is_self','Is Self ?',['class' => 'form-label'])!!}
                          {!!Form::select('is_self',$is_self,null,['class'=>'form-control','id'=>'is_self','placeholder'=>'Is Self'])!!}
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          {!!Form::label('amount','Amount',['class' => 'form-label'])!!}
                          {!!Form::text('amount',null,['class'=>'form-control','id'=>'amount','placeholder'=>'Enter Amount','required'])!!}
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          {!!Form::label('date','Date',['class' => 'form-label'])!!}
                          {!!Form::text('date',null,['class'=>'form-control','id'=>'date','required','readonly'])!!}
                      </div>
                  </div>
                  <div class="col-md-8">
                      <div class="form-group">
                          {!!Form::label('remarks','Remarks',['class' => 'form-label'])!!}
                          {!!Form::textarea('remarks',null,['class'=>'form-control','id'=>'remarks','style'=>'height:75px;resize:none;'])!!}
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          {!! Form::submit('Save', ['class' => 'btn btn-success','id'=>'sub']) !!}
                      </div>
                  </div>
              </div>
          </div>
          {!! Form::close() !!}
      </div>
      <div class="modal-footer">
        {{-- <button id="del_btn" class="btn btn-success" type="button" data-submit="">@lang('fleet.delete')</button> --}}
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="viewModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.view')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
 $(".vevent").click(function(){
    var id = $(this).data("id");
    // console.log(id)
      $("#viewModal .modal-body").load('{{url("admin/bank-account/view_event")}}/'+id,function(res){
        // console.log(res)
        $(".modal-content").css('width','100%');
        $("#viewModal").modal({show:true});
      })
  })

  $("body").on("click",".custom_padding",function(){
    var href = $(this).attr("href");
    // console.log(href);
    if(href=="#history-tab")
      $(".modal-content").css('width','165%');
    else
      $(".modal-content").css('width','100%');
  })

  $(".vaddamt").click(function(){
    var id = $(this).data("id");
    $("#addAmount .modal-body").load('{{url("admin/bank-account/add_amount")}}/'+id,function(res){
        // console.log(res)
        $("#addAmount .modal-content").css('width','150%');
        $("#addAmount").modal({show:true});
      })
  })

  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#form_"+id).submit();
  });
  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });

  $('input[type="checkbox"]').on('click',function(){
    $('#bulk_delete').removeAttr('disabled');
  });

  $('#bulk_delete').on('click',function(){
    // console.log($( "input[name='ids[]']:checked" ).length);
    if($( "input[name='ids[]']:checked" ).length == 0){
      $('#bulk_delete').prop('type','button');
        new PNotify({
            title: 'Failed!',
            text: "@lang('fleet.delete_error')",
            type: 'error'
          });
        $('#bulk_delete').attr('disabled',true);
    }
    if($("input[name='ids[]']:checked").length > 0){
      // var favorite = [];
      $.each($("input[name='ids[]']:checked"), function(){
          // favorite.push($(this).val());
          $("#bulk_hidden").append('<input type=hidden name=ids[] value='+$(this).val()+'>');
      });
      // console.log(favorite);
    }
  });


  $('#chk_all').on('click',function(){
    if(this.checked){
      $('.checkbox').each(function(){
        $('.checkbox').prop("checked",true);
      });
    }else{
      $('.checkbox').each(function(){
        $('.checkbox').prop("checked",false);
      });
    }
  });

  // Checkbox checked
  function checkcheckbox(){
    // Total checkboxes
    var length = $('.checkbox').length;
    // Total checked checkboxes
    var totalchecked = 0;
    $('.checkbox').each(function(){
        if($(this).is(':checked')){
            totalchecked+=1;
        }
    });
    // console.log(length+" "+totalchecked);
    // Checked unchecked checkbox
    if(totalchecked == length){
        $("#chk_all").prop('checked', true);
    }else{
        $('#chk_all').prop('checked', false);
    }
  }
  $(function(){
      $(document).on('click','.advbook',function(){
          $("#viewModal .modal-content").css('width','150%');
      })
      $(document).on('click','.advgeneral',function(){
          $("#viewModal .modal-content").css('width','100%');
      })

      // $('body').on("focus","#date",function(){
      //   $(this).datepicker({
      //     'dateFormat':'dd-mm-YY'
      //   });
      // })
      $("body").on("focus","#date",function(){
        $(this).datepicker({
          autoclose: true,
          format: 'dd-mm-yyyy'
        });
      })
  })
</script>
@endsection