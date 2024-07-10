<form action="{{url('admin/bookings/modal_save')}}" method="POST">
<input type="hidden" name="id" value="{{$data->id}}">
{{ csrf_field() }}
<table class="table table-striped">
    <tbody>
    @if($data->getMeta('advance_pay')!="")
        <tr>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td colspan="2" align="center"><strong>Driver Details</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Name :</strong></td>
                        <td>{{$data->driver->name}}</td>
                    </tr>
                    <tr>
                        <td><strong>Vehicle :</strong></td>
                        <td>{{$data->vehicle->make}} - {{$data->vehicle->model}} - {{$data->vehicle->license_plate}}</td>
                    </tr>
                    <tr>
                        <td><strong>Journey Date :</strong></td>
                        <td>{{Helper::getCanonicalDateTime($data->pickup)}}</td>
                    </tr>
                </tbody>
            </table>
        </tr>
        <tr>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td><strong>Total Advance :</strong></td>
                        <td>
                            <input type="number" class="form-control" name="total_adv" id="total_adv" value="{{$data->getMeta('advance_pay')}}" readonly>
                        </td>
                        <td><strong>Toll Tax :</strong></td>
                        <td>
                            <input type="number" class="form-control from-input" name="toll_tax" id="toll_tax" value="">
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Food Expenses :</strong></td>
                        <td>
                            <input type="number" class="form-control from-input" name="food" id="food" value="">
                        </td>
                        <td><strong>Labour Expenses :</strong></td>
                        <td>
                            <input type="number" class="form-control from-input" name="labour" id="labour" value="">
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Tyre Expenses :</strong></td>
                        <td>
                            <input type="number" class="form-control from-input" name="tyre" id="tyre" value="">
                        </td>
                        <td><strong>Donations :</strong></td>
                        <td>
                            <input type="number" class="form-control from-input" name="donations" id="donations" value="">
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Documents :</strong></td>
                        <td>
                            <input type="number" class="form-control from-input" name="documents" id="documents" value="">
                        </td>
                        <td><strong>Fuel :</strong></td>
                        <td>
                            <input type="number" class="form-control from-input" name="fuel" id="fuel" value="">
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Maintenance :</strong></td>
                        <td>
                            <input type="number" class="form-control from-input" name="maintenance" id="maintenance" value="">
                        </td>
                        <td><strong>Electrical :</strong></td>
                        <td>
                            <input type="number" class="form-control from-input" name="electrical" id="electrical" value="">
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Advance to Driver :</strong></td>
                        <td>
                            <input type="number" class="form-control from-input" name="advance" id="advance" value="">
                        </td>
                        <td><strong>Refund :</strong></td>
                        <td>
                            <input type="number" class="form-control from-input" name="refund" id="refund" value="">
                        </td>
                    </tr>
                    
                    <tr>
                        <td><strong>Others :</strong></td>
                        <td colspan="3">
                            <input type="number" class="form-control" name="others" id="others" value="{{$data->getMeta('advance_pay')}}" readonly style="margin-bottom: 10px;">
                            
                            <textarea name="remarks[others]" id="remarks" class="form-control" style="resize: none;height:100px;" placeholder="Remark..." required></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <input type="submit" class="btn btn-success" id="sub" style="margin-left:22%">
                        </td>
                    </tr>
                </tbody>
            </table>
        </tr>
    @else
        <tr>
            <td style="color: red; text-align:center" colspan="2">No advance was given to <i>{{$data->driver->name}}</i> for this booking..</td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" class="btn btn-success" id="sub" style="float: right" value="Complete Anyway..">
            </td>
        </tr>
    @endif
    </tbody>
</table>
</form>    