@extends('layouts.app')
@section('extra_css')
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style type="text/css">
  /* .select2-selection{
    height: 38px !important;
  } */
  .remarks{height: 100px;resize: none;}
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("bulk_leave.index")}}">@lang('fleet.bulk_leave')</a></li>
<li class="breadcrumb-item active">@lang('fleet.abulk_leave')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">@lang('fleet.abulk_leave')</h3>
      </div>

      <div class="card-body">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        {!! Form::open(['route' => 'bulk_leave.store','files'=>true,'method'=>'post']) !!}
        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('drivers', 'Select Drivers', ['class' => 'form-label required']) !!}
                {!! Form::select('driver_id',$data,null,['class' => 'form-control drivers','required','id'=>'driver_id','placeholder'=>'Select Driver']) !!}
              </div>
          </div>

          <div class="col-md-4">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    {!! Form::label('years', 'Select Year', ['class' => 'form-label required']) !!}
                    {!! Form::select('years',$years,null,['class' => 'form-control years','required','id'=>'years','placeholder'=>'Select Year']) !!}
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    {!! Form::label('months', 'Select Month', ['class' => 'form-label required']) !!}
                    {!! Form::select('months',$months,null,['class' => 'form-control months','required','id'=>'months','placeholder'=>'Select Month']) !!}
                  </div>
                </div>
              </div>
          </div>
          <div class="col-md-4 help-div" style="display: none;">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    {!! Form::label('select_date', 'Select Date Help?', ['class' => 'form-label required']) !!}
                    {!! Form::select('',$select_array,null,['class' => 'form-control select_date','id'=>'select_date','placeholder'=>'Choose Help Type']) !!}
                  </div>
                </div>
              </div>
          </div>
          
          <div class="col-md-12">
          {!! Form::submit(__('fleet.search'), ['class' => 'btn btn-success','id'=>'searchbtn']) !!}
          </div>
          
        </div>
        <div class="month_data mt-3">
          {{-- month data here --}}
        </div>
        {!! Form::close() !!}
    </div>
  </div>
</div>

@endsection

@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>

<script type="text/javascript">

function getRandom(){
  var myArray = [1,2,3,4]; 
  return myArray[(Math.random() * myArray.length) | 0]
  
}

$(document).ready(function() {
    // $("#driver_id").attr('disabled',true);
    // var date = new Date();
    // date.setDate(date.getDate()-1);
    // $('#date').datepicker({
    //     autoclose: true,
    //     format: 'yyyy-mm-dd',
    //     // startDate: date,
    //     endDate: '+1d'
    // });

    $("body").on("change","#select_date",function(){
      let select = $(this);
      if(select.val()=="all-present"){
        $(".attendance option[value=1]").prop('selected','selected');
      }else if(select.val()=="all-absent"){
        $(".attendance option[value=2]").prop('selected','selected');
      }else if(select.val()=="randomize" || select.val()=="randomize-more"){
        // $(".attendance option[value=2]").prop('selected','selected');
        $(".attendance").each(function(){
          $(this).val(getRandom());
        });
      }else{

      }
    });

    $(".drivers,#years,#months").select2();
    
    $("#searchbtn").on("click",function(e){
      e.preventDefault();
      var blankTest = /\S/;
      var driver = $("#driver_id").val();
      var year = $("#years").val();
      var month = $("#months").val();
      if(!blankTest.test(driver)){
        alert('Please select a driver to proceed.')
        return false;
      }

      if(!blankTest.test(year)){
        alert('Please select year to proceed.')
        return false;
      }

      if(!blankTest.test(month)){
        alert('Please select month to proceed.')
        return false;
      }

      // var data ={driver:driver,year:year,month:month}
      // $('.month_data').load('{{ url("admin/bulk_leave/getDatesPage")}}/'+data,function(result){
      // // console.log(result);
      // });
      var data = {_token:"{{csrf_token()}}",driver:driver,year:year,month:month};
      var posting = $.post("{{route('bulk_leave.getDatesPage')}}",data)
      posting.done(function(data){
        // console.log(data)
        $('.month_data').html(data);
        if(!$('.month_data').is(':empty')){
          $(".help-div").show();
        }else{
          $(".help-div").show();
        }
      })
    })

    // $("#present_type").change(function(){
    //     if($(this).val()==1 || $(this).val()=='')
    //         $("#driver_id").attr('disabled',true);
    //     else
    //         $("#driver_id").attr('disabled',false);
    // })

    // $("#driver_id").on("change",function(){
    //     var darr = $(this).val();
    //     console.log(darr)
    //     $('.remarks').load('{{url("admin/leave/get_remarks")}}/'+darr,function(result){
    //         // console.log(result);
    //     })
    // })

    

    // $("#savebtn").click(function(){
    //     if($("#date").val()==""){
    //         alert('Date field can\'t be empty.');
    //         return false;
    //     }
            
    // })
});
</script>
@endsection