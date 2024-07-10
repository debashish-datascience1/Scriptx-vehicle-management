{!!Form::open(['route'=>'work_order.itemno_store','method'=>'POST','files'=>true])!!}
    <table class="table table-striped">
        <tr>
            <th class="text-center">
                {{ Helper::getFullPartName($used->part->id) }}
                <input type="hidden" value="{{$used->id}}" name="used_id">
                {{-- {{dd($used->parts_number)}} --}}
            </th>
        </tr>
        @if ($used->parts_number->count())
            @foreach ($used->parts_number as $k=>$item)
                <tr>
                    <td>
                        {!! Form::text('itemno[]',$item->slno,['class'=>'form-control','placeholder'=>'Enter Item No.','autocomplete'=>'off',$k==0 ? 'required' : '']) !!}
                    </td>
                </tr>
            @endforeach
        @else
            @for ($i = 1; $i < $used->qty; $i++)
                <tr>
                    <td>
                        {!! Form::text('itemno[]',null,['class'=>'form-control','placeholder'=>'Enter Item No.','autocomplete'=>'off',$i==1 ? 'required' : '']) !!}
                    </td>
                </tr>
            @endfor
        @endif
        <tr>
            <td>
                {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-success']) !!}
            </td>
        </tr>
    </table>
{!! Form::close() !!}