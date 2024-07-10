<table class="table table-striped">
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        
        <th></th>
        <th></th>
        <th></th>
         @if($remaining->remaining!=0)
        <th></th>
        <th></th>
        <th></th>
        @endif
        <th></th>
        <th></th>
        <th>Total : {{Hyvikk::get('currency')}} {{number_format($transaction->total,2,'.','')}}</th>
        <th></th>
        <th>
            Remaining : <span id="span-remain">{{Hyvikk::get('currency')}} {{number_format($remaining->remaining,2,'.','')}}</span>
            @if($remaining->remaining==0)
            &nbsp;&nbsp;&nbsp;<span class="badge badge-success"><i class="fa fa-check"></i> Adjusted</span>
            @endif
        </th>
        <th><input type="hidden" name="remainingAmt" id="remainingAmt" value="{{$remaining->remaining}}"></th>
        
        <th>
            <input type="hidden" name="trans_id" value="{{$transaction->id}}">
        </th>
    </tr>
</table>

<table class="table table-striped adjustTable">
    <thead>
        <th style="width: 18%">Date</th>
        <th style="width: 18%">Method</th>
        <th style="width: 19%">Amount</th>
        <th>Remarks</th>
        <th></th>
    </thead>
    <tbody>
        @if(count($incomes)>0)
            @foreach($incomes as $income)
            <tr class="parent-row">
                <td>
                    <input type="text" name="adjDatePrev[]" class="form-control adjDatePrev" readonly value="{{date("Y-m-d",strtotime($income->date))}}">
                </td>
                <td>
                {{-- <select name="adjTypePrev[]" id="" class="form-control adjTypePrev">
                    @foreach ($method as $item)
                        <option value="{{$item['id']}}" {{$item->id==$income->payment_method ? 'selected style=pointer-events:none' : ''}}>{{$item->label}}</option>
                    @endforeach
                </select> --}}
                    {!! Form::select('adjTypePrev[]',$method,$income->payment_method,['class'=>'form-control adjTypePrev']) !!}
                </td>
                <td>
                    <input type="text"  name="adjAmountPrev[]" class="form-control adjAmountPrev"  value="{{$income->amount}}" {{count($incomes)==1 && $income->amount==0 ? '' : 'readonly'}} required>
                </td>
                <td>
                    {{$income->remarks}}
                </td>
                <td><input type="hidden" name="income_id[]" class="income_id" value="{{$income->id}}"></td>
            </tr>
            @endforeach
        @endif
        @if(($remaining->remaining>0 && count($incomes)!=1) || ($remaining->remaining>0 && $incomes[0]->amount!=0))
        <tr class="parent-row">    
            <td>
                <input type="text" name="adjDate[]" class="form-control adjDate" readonly placeholder="Choose Date.." required>
            </td>
            <td>
                {!! Form::select('adjType[]',$method,null,['class'=>'form-control adjType','required']) !!}
            </td>
            <td>
                <input type="text"  name="adjAmount[]" class="form-control adjAmount" placeholder="Enter Amount.." required>
            </td>
            <td>
                <input type="text"  name="adjRemarks[]" class="form-control adjRemarks" placeholder="Enter Remarks..">
            </td>
            <td>
                <button class="btn btn-primary addmore" id="addmore"><span class="fa fa-plus"></span> Add More</button>
            </td>
        </tr>
        @endif
    </tbody>
</table>
