@extends('layouts.app')
@section("breadcrumb")
<li class="breadcrumb-item">@lang('menu.settings')</li>
<li class="breadcrumb-item active">@lang('menu.general_settings')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('menu.general_settings')
        </h3>
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

        {!! Form::open(['route' => 'settings.store','files'=>true,'method'=>'post']) !!}
        <div class="row">
          <div class="form-group col-md-4">
            {!! Form::label('app_name',__('fleet.app_name'),['class'=>"form-label"]) !!}
            {!! Form::text('name[app_name]',
            Hyvikk::get('app_name'),['class'=>"form-control",'required']) !!}
          </div>

          <div class="form-group col-md-4">
            {!! Form::label('email',__('fleet.email'),['class'=>"form-label"]) !!}
            {!! Form::text('name[email]',
            Hyvikk::get('email'),['class'=>"form-control",'required']) !!}
          </div>

          <div class="form-group col-md-4">
            {!! Form::label('badd1',__('fleet.badd1'),['class'=>"form-label"]) !!}
            {!! Form::text('name[badd1]',
            Hyvikk::get('badd1'),['class'=>"form-control",'required']) !!}
          </div>

          <div class="form-group col-md-4">
            {!! Form::label('badd2',__('fleet.badd2'),['class'=>"form-label"]) !!}
            {!! Form::text('name[badd2]',
            Hyvikk::get('badd2'),['class'=>"form-control",'required']) !!}
          </div>

          <div class="form-group col-md-4">
            {!! Form::label('city',__('fleet.city'),['class'=>"form-label"]) !!}
            {!! Form::text('name[city]',
            Hyvikk::get('city'),['class'=>"form-control",'required']) !!}
          </div>

          <div class="form-group col-md-4">
            {!! Form::label('state',__('fleet.state'),['class'=>"form-label"]) !!}
            {!! Form::text('name[state]',
            Hyvikk::get('state'),['class'=>"form-control",'required']) !!}
          </div>

          <div class="form-group col-md-3">
            {!! Form::label('country',__('fleet.country'),['class'=>"form-label"]) !!}
            {!! Form::text('name[country]',
            Hyvikk::get('country'),['class'=>"form-control",'required']) !!}
          </div>

          <div class="form-group col-md-3">
            {!! Form::label('dis_format',__('fleet.dis_format'),['class'=>"form-label"]) !!}
            {!! Form::select('name[dis_format]', ['km' => 'km', 'miles' => 'miles'], Hyvikk::get("dis_format"),['class'=>"form-control",'required']) !!}
          </div>

          <div class="form-group col-md-3">
            {!! Form::label('fuel_unit',__('fleet.fuel_unit'),['class'=>"form-label"]) !!}
            {!! Form::select('name[fuel_unit]', ['gallon' => 'gallon', 'liter' => 'liter'], Hyvikk::get("fuel_unit"),['class'=>"form-control",'required']) !!}
          </div>

          <div class="form-group col-md-3">
            {!! Form::label('language',__('fleet.language'),['class'=>"form-label"]) !!}
            <select id='name[language]' name='name[language]' class="form-control" required>
              <option value="">-</option>
              @if(Auth::user()->getMeta('language')!= null)
              @php ($language = Auth::user()->getMeta('language'))
              @else
              @php($language = Hyvikk::get("language"))
              @endif
              @foreach($languages as $lang)
              @php($l = explode('-',$lang))
              @if($language == $lang)

              <option value="{{$lang}}" selected> {{$l[0]}}</option>
              @else
              <option value="{{$lang}}" > {{$l[0]}} </option>
              @endif
              @endforeach
            </select>
          </div>

          <div class="form-group col-md-2">
            {!! Form::label('number_days',__('fleet.noOfDays'),['class'=>"form-label"]) !!}

              <div class="input-group mb-3">
                {!! Form::number('name[number_days]',Hyvikk::get('number_days'),['class'=>"form-control",'required','min'=>1]) !!}
                <div class="input-group-append">
                  <span class="input-group-text">day(s)</span>
                </div>
              </div>
          </div>
          <div class="form-group col-md-2">
            {!! Form::label('km_travel',__('fleet.kmt'),['class'=>"form-label"]) !!}

              <div class="input-group mb-3">
                {!! Form::number('name[km_travel]',Hyvikk::get('km_travel'),['class'=>"form-control",'required','min'=>1]) !!}
                <div class="input-group-append">
                  <span class="input-group-text">km(s)</span>
                </div>
              </div>
          </div>

          <div class="form-group col-md-4">
            <label for="icon_img"> @lang('fleet.icon_img')</label>
            @if(Hyvikk::get('icon_img')!= null)
            <button type="button" class="btn btn-success view1 btn-xs" data-toggle="modal" data-target="#myModal3" id="view" title="@lang('fleet.image')" style="margin-bottom: 5px">
            @lang('fleet.view')
            </button>
            @endif
            <div class="input-group input-group-sm">
            {!! Form::file('icon_img') !!}
            </div>
          </div>
          <div class="form-group col-md-4">
            <label for="logo_img"> @lang('fleet.logo_img')</label>
            @if(Hyvikk::get('logo_img')!= null)
            <button type="button" class="btn btn-success view2 btn-xs" data-toggle="modal" data-target="#myModal3" id="view" title="@lang('fleet.image')" style="margin-bottom: 5px">
            @lang('fleet.view')
            </button>
            @endif
            <div class="input-group input-group-sm">
              {!! Form::file('logo_img') !!}
            </div>
          </div>

          <div class="form-group col-md-3">
            {!! Form::label('currency',__('fleet.currency'),['class'=>"form-label"]) !!}
            {!! Form::text('name[currency]',
            Hyvikk::get('currency'),['class'=>"form-control",'required']) !!}
          </div>
          <div class="form-group col-md-3">
            {!! Form::label('date_format',__('fleet.date_format'),['class'=>"form-label"]) !!}
            {!! Form::select('name[date_format]', ['d-m-Y' => 'dd-mm-yyyy ('.date('d-m-Y').')', 'Y-m-d' => 'yyyy-mm-dd ('.date('Y-m-d').')','m-d-Y'=>'mm-dd-yyyy ('.date('m-d-Y').')'], Hyvikk::get("date_format"),['class'=>"form-control",'required']) !!}
          </div>
          <div class="form-group col-md-3">
            {!! Form::label('tax_no',__('fleet.tax_no'),['class'=>"form-label"]) !!}
            {!! Form::text('name[tax_no]',
            Hyvikk::get('tax_no'),['class'=>"form-control",'required']) !!}
          </div>

          <div class="form-group col-md-3">
            {!! Form::label('tax_charge',__('fleet.tax_charge')." (%)",['class'=>"form-label"]) !!}
            <div class="row">
              <div class="col-md-8">
                {!! Form::text('udf1', null,['class' => 'form-control','id'=>'udf1','placeholder'=>'Enter Tax Name']) !!}
              </div>
              <div class="col-md-4">
                <button type="button" class="btn btn-info add_udf"> @lang('fleet.addNew')</button>
              </div>
            </div>
          </div>
          @php($udfs = json_decode(Hyvikk::get('tax_charge')))

          @if($udfs != null)
          <div class="col-md-4"><hr></div>
          <div class="col-md-4"><h4 class="text-center">@lang('fleet.tax_charge')</h4></div>
          <div class="col-md-4"><hr></div>
          @foreach($udfs as $key => $value)
          <div class="row col-md-6">
          <div class="col-md-8">  <div class="form-group"> <label class="form-label text-uppercase">{{$key}}</label> <div class="input-group mb-3"><input type="number" name="udf[{{$key}}]" class="form-control" required value="{{$value}}" min=0 step="0.01"> <div class="input-group-append"> <span class="input-group-text fa fa-percent"></span> </div> </div></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div>
          </div>
          @endforeach
          @endif
          <div class="blank col-md-12"></div>
          <div class="form-group col-md-12">
            {!! Form::label('invoice_text',__('fleet.invoice_text'),['class'=>"form-label"]) !!}
            {!! Form::textarea('name[invoice_text]',
            Hyvikk::get('invoice_text'),['class'=>"form-control",'size'=>'30x3']) !!}
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <input type="submit"  class="form-control btn btn-success"  value="@lang('fleet.save')" />
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <button type="button" data-toggle="modal" data-target="#myModal"  class="form-control btn btn-danger">@lang('fleet.clear_database')</button>  
            </div>
          </div>
        </div>
      </div>
      {!! Form::close()!!}
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.delete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>@lang('fleet.confirm_clear_database')</p>
        <p class="text-danger"><strong>@lang('fleet.note'): @lang('fleet.clear_database_note')</strong></p>
      </div>
      <div class="modal-footer">
        {!! Form::open(['url' => 'admin/clear-database','method'=>'post']) !!}
        <button class="btn btn-danger" type="submit">@lang('fleet.clear_database')</button>
        {!! Form::close() !!}
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

<!--model 2 -->
<div id="myModal3" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <img src="" class="myimg">
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          @lang('fleet.close')
        </button>
      </div>
    </div>
  </div>
</div>
<!--model 2 -->
@endsection

@section('script')

<script type="text/javascript">
  @if(Session::get('msg'))
    new PNotify({
        title: 'Success!',
        text: '{{ Session::get('msg') }}',
        type: 'success'
      });
  @endif
  
  $('.view1').click(function(){
    $('#myModal3 .modal-body .myimg').attr( "src","{{ asset('assets/images/'. Hyvikk::get('icon_img') ) }}");
    $('#myModal3 .modal-body .myimg').removeAttr( "height");
    $('#myModal3 .modal-body .myimg').removeAttr( "width");
  });

  $('.view2').click(function(){
    $('#myModal3 .modal-body .myimg').attr( "src","{{ asset('assets/images/'. Hyvikk::get('logo_img') ) }}");
    $('#myModal3 .modal-body .myimg').attr( "height","140px");
    $('#myModal3 .modal-body .myimg').attr( "width","300px");
  });

  $(".add_udf").click(function () {
    // alert($('#udf').val());
    var field = $('#udf1').val();
    if(field == "" || field == null){
      alert('Enter Tax name');
    }
    else{
      $(".blank").append('<div class="row col-md-12"><div class="col-md-4">  <div class="form-group"> <label class="form-label">'+ field.toUpperCase() +'</label> <div class="input-group mb-3"><input type="number" name="udf['+ field +']" class="form-control" placeholder="Enter '+ field +'" required min=0 step="0.01"> <div class="input-group-append"> <span class="input-group-text fa fa-percent"></span> </div> </div></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>');
      $('#udf1').val("");
    }
  });
</script>
@endsection