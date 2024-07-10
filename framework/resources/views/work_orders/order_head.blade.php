{!! Form::model($workOrder,['route'=>['work_order.store-order-head',$workOrder->id],'method'=>'PATCH']) !!}
<table class="table table-striped">
    <tr>
        <th>
            <div class="row">
                <div class="col-12 text-center">
                    Tranaction ID : {{Helper::getTransaction($workOrder->id,28)->transaction_id}}<br>
                    <small>Bill No : {{$workOrder->bill_no}}</small><br>
                    <small>Date : {{Helper::getCanonicalDate($workOrder->required_by,'default')}}</small>
                </div>
            </div>
        </th>
    </tr>
    <tr>
        <th>
            {!! Form::label('category_id', __('fleet.order_head'), ['class' => 'col-xs-5 control-label']) !!}
            {!! Form::select('category_id',$workOrderCategory,null,['class' => 'form-control','required','placeholder'=>'Select Order Head']) !!}
        </th>
    </tr>
    <tr>
        <th>
            {!! Form::submit('Submit',['class'=>'btn btn-success']) !!}
        </th>
    </tr>
</table>
{!! Form::close() !!}