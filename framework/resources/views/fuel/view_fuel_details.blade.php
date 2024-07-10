
<table class="table table-striped" >
    
    <thead class="thead-inverse">
        <tr>
            <td colspan="6">
            {!! Form::open(['method'=>'post','class'=>'form-inline']) !!}
                <input type="hidden" name="vendor_id" value="{{$vendor_id}}">
                <input type="hidden" name="fuel_type" value="{{$fuel_type}}">
                <input type="hidden" name="from_date" value="{{$from_date}}">
                <input type="hidden" name="to_date" value="{{$to_date}}">
                <input type="hidden" name="vendor_name" value="{{$vendor_name}}">
                <input type="hidden" name="fuel_name" value="{{$fuel_name}}">
                <div class="row" style="width: 100%">
                  <div class="col-md-10">
                    <div class="row">
                      <div class="col-md-12">Vendor Name : <strong>{{$vendor_name}}</strong></div>
                      <div class="col-md-12">Fuel Type : <strong>{{$fuel_name}}</strong></div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <button type="submit" formaction="{{url('admin/print-fuel-modal-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
                  </div>
                </div>
                {{-- <h6>  Fuel Type : <strong>{{$fuel_name}}</strong></h6> --}}
                {!! Form::close() !!} 
            </td>
                
        </tr>
      <tr>
        <th>SL#</th>
        <th>Date</th>
        <th>Vehicle</th>
        <th>Quantity(ltr)</th> 
        <th>Cost per unit</th> 
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
       
      @foreach($fuel as $k=>$data) 
      <tr>
        <td>{{$k+1}}</td>  
        <td>{{Helper::getCanonicalDate($data->date,'default')}}</td>  
        <td><strong>{{strtoupper($data->vehicle_data->license_plate)}}</strong></td>
         <td>{{$data->qty}}</td>  
         <td>{{Hyvikk::get('currency')}} {{number_format($data->cost_per_unit,2,'.','')}}</td>  
        <td>{{Hyvikk::get('currency')}} {{number_format($data->qty * $data->cost_per_unit,2,'.','')}}</td> 
        
      </tr>

      
      @endforeach
     <tr>
         <th colspan="4"></th>
         <th>Grand Total</th>
         <th>{{Hyvikk::get('currency')}} {{number_format($fuelSum,2,'.','')}}</th>
     </tr>
    </tbody>
   
  </table>