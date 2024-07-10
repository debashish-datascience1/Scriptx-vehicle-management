 @extends("layouts.app")
@section("breadcrumb")
<li class="breadcrumb-item active">@lang('fleet.manageBulkPay')</li>
@endsection
@section('extra_css')
<style type="text/css">
  .checkbox, #chk_all{
    width: 20px;
    height: 20px;
  }
    .where_from,.advance_for{cursor: pointer;}
    .where_from{color:#fff!important; }
    .border-refund{border:2px solid #02bcd1; }
    .badge-driver-adv{background: royalblue;color:#fff;}
    .badge-parts{background: darkslategrey;color:#fff;}
    .badge-refund{background: darkviolet;color:#fff;}
    .badge-fuel{background: #8bc34a;color:#fff;}
    .badge-starting-amt{background: #c34a4a;color:#fff;}
    .badge-deposit{background: #b000bb;color:#fff;}
    .badge-revised{background: #da107f;color:#fff;}
    .badge-liability{background: #004e5c;color:#fff;}
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
        @lang('fleet.manageBulkPay')
        &nbsp;
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th>
              {{-- @if($bulkpays->count() > 0)
                <input type="checkbox" id="chk_all">
              @endif --}}
              </th>
              <th>Date</th>
              <th>Bank</th>
              <th>Transaction ID</th>
              <th>Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          @foreach($bulkpays as $row)
            <tr>
              <td>
                <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();'>
              </td>
              <td>{{Helper::getCanonicalDate($row->date,'default')}}</td>
              <td>
                <span class="badge badge-info">{{$row->bank->bank}}</span><br>
              </td>
              <td>
                @foreach($row->trash as $tr)
                    @php $tr = $tr->transaction; @endphp
                  @if($tr->param_id==18)
                    <a class="badge badge-success where_from" data-id="{{$tr->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($tr->params) ? $tr->params->label : 'N/A'}}">{{!empty($tr->params) ? $tr->transaction_id : 'N/A'}}</a>
                    
                    @if($tr->advance_for==21)
                    <br>
                      <a class="badge badge-warning advance_for" data-id="{{$tr->id}}" data-toggle="modal" data-target="#advanceForModal" title="{{!empty($tr->params) ? $tr->params->label : 'N/A'}}">{{$tr->transaction_id}}</a>
                      @endif
                    @elseif($tr->param_id==19)
                    <a class="badge badge-info where_from" data-id="{{$tr->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($tr->params) ? $tr->params->label : 'N/A'}}">{{!empty($tr->params) ? $tr->transaction_id : 'N/A'}}</a>
                    @elseif($tr->param_id==20)
                    <a class="badge badge-fuel where_from" data-id="{{$tr->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($tr->params) ? $tr->params->label : 'N/A'}}">{{!empty($tr->params) ? $tr->transaction_id : 'N/A'}}</a>
                    @elseif($tr->param_id==25)
                    <a class="badge badge-driver-adv where_from" data-id="{{$tr->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($tr->params) ? $tr->params->label : 'N/A'}}">{{!empty($tr->params) ? $tr->transaction_id : 'N/A'}}</a>
                    @elseif($tr->param_id==26)
                    <a class="badge badge-parts where_from" data-id="{{$tr->id}}" data-partsw={{$tr->id}} data-toggle="modal" data-target="#whereModal" title="{{!empty($tr->params) ? $tr->params->label : 'N/A'}}">{{!empty($tr->params) ? $tr->transaction_id : 'N/A'}}</a>
                    @elseif($tr->param_id==27)
                    <a class="badge badge-refund where_from" data-id="{{$tr->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($tr->params) ? $tr->params->label : 'N/A'}}">{{!empty($tr->params) ? $tr->transaction_id : 'N/A'}}</a>
                    @elseif($tr->param_id==28)
                    <a class="badge badge-info where_from" data-id="{{$tr->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($tr->params) ? $tr->params->label : 'N/A'}}">{{!empty($tr->params) ? $tr->transaction_id : 'N/A'}}</a>
                    @elseif($tr->param_id==29)
                    <a class="badge badge-starting-amt where_from" data-id="{{$tr->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($tr->params) ? $tr->params->label : 'N/A'}}">{{!empty($tr->params) ? $tr->transaction_id : 'N/A'}}</a>
                    @elseif($row->param_id==30)
                    <a class="badge badge-deposit where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                    @elseif($row->param_id==31)
                    <a class="badge badge-revised where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                    @elseif($row->param_id==32)
                    <a class="badge badge-liability where_from" data-id="{{$row->id}}" data-toggle="modal" data-target="#whereModal" title="{{!empty($row->params) ? $row->params->label : 'N/A'}}">{{!empty($row->params) ? $row->params->label : 'N/A'}}</a>
                    @else{{dd($tr->param_id)}}
                    @endif
                @endforeach
              </td>
              <td>{{Hyvikk::get('currency')}} {{bcdiv($row->amount,1,2)}}</td>
              <td>
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-gear"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item mybtn vevent" data-id="{{$row->id}}" data-toggle="modal" data-target="#viewModal" title="@lang('fleet.view')"><i class="fa fa-eye" aria-hidden="true" style="color:#269abc;"></i> @lang('fleet.view')</a>
                 
                  {{-- <a class="dropdown-item" href="{{url("admin/bank-account/".$row->id."/edit") }}"><span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a> --}}
                  {!! Form::hidden("id",$row->id) !!}
                  {{-- <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a> --}}
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
              <th>Date</th>
              <th>Bank</th>
              <th>Transaction ID</th>
              <th>Amount</th>
              <th>Action</th>
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
<!-- Modal -->
<div id="viewModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width: 155%">
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
<!-- Modal -->
<div id="whereModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
      <div class="modal-content">
        Loading....
      </div>
  </div>
</div>

<!-- Modal -->
<div id="advanceForModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Advance Details</h4>
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
<script type="text/javascript">
 $(".vevent").click(function(){
    var id = $(this).data("id");
    // console.log(id)
      $("#viewModal .modal-body").load('{{url("admin/bank-account/bulk_pay/view_event")}}/'+id,function(res){
        // console.log(res)
        $("#viewModal").modal({show:true});
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

    $(".where_from").on("click",function(){
        var id = $(this).data("id");
        var partsw = $(this).data("partsw");
        console.log(id);
        console.log(partsw);
        $("#whereModal .modal-content").load('{{url("admin/accounting/where_from")}}/'+id,function(res){
          if(typeof partsw!="undefined"){
            $(this).css({'width': '201%','margin-left': '-126px'})     
          }else{
            $(this).css({'width': '','margin-left': ''})   
          }
          $("#whereModal").modal({show:true})
        })
    })

    $(".advance_for").on("click",function(){
        var id = $(this).data("id");
        $("#advanceForModal .modal-body").load('{{url("admin/accounting/advance_for")}}/'+id,function(res){
        $("#advanceForModal").modal({show:true})
        })
    })
  })
</script>
@endsection