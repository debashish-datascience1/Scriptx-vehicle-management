
<table class="table table-striped adjustTable">
    <thead>
        <th>{{$adv->driver->name}}</th>
        <th>{{Helper::getCanonicalDate($adv->date,'default')}}</th>
        <th>Total : {{Hyvikk::get('currency')}} {{Helper::properDecimals($adv->amount)}}</th>
        <th colspan="2">
            Remaining : <span id="span-remain">{{Hyvikk::get('currency')}} {{Helper::properDecimals($remaining)}}</span>
            @if($remaining==0)
            &nbsp;&nbsp;&nbsp;<span class="badge badge-success"><i class="fa fa-check"></i> Adjusted</span>
            @endif
        </th>
        <th><input type="hidden" name="remainingAmt" id="remainingAmt" value="{{$remaining}}"></th>
        <th></th>
        <th><input type="hidden" name="otherAdvance" id="otherAdvance" value="{{$adv->id}}"></th>
    </thead>
    <tbody>
            <tr>
                <th style="width: 18%">Head & Date</th>
                <th style="width: 18%">Method & Reference No.</th>
                <th style="width: 19%">Amount & Payment Type</th>
                <th colspan="4">Remarks</th>
                <th></th>
            </tr>
        @if(count($others)>0)
            @foreach($others as $oth)
            <tr class="parent-row">
                <td>
                    {!!Form::text("adjHeadPrev[]",$oth->head,['class'=>'form-control','placeholder'=>'e.g. Food','readonly'])!!}
                    <input type="text" name="adjDatePrev[]" class="form-control adjDatePrev" readonly placeholder="Date.." value="{{$oth->date}}">
                </td>
                <td>
                    {!! Form::select('adjMethodPrev[]',$method,$oth->method,['class'=>'form-control adjMethodPrev','readonly','placeholder'=>'Select']) !!}
                    {!!Form::text("adjRefPrev[]",$oth->ref_no,['class'=>'form-control adjRefPrev','placeholder'=>'Reference No.'])!!}
                </td>
                <td>
                    <input type="text"  name="adjAmountPrev[]" class="form-control adjAmount" placeholder="Enter Amount.." readonly  onkeypress='return isNumber(event,this)' value="{{$oth->amount}}">
                    {!! Form::select('adjTypePrev[]',$type,$oth->type,['class'=>'form-control adjTypePrev','readonly','placeholder'=>'Select']) !!}
                    @if($oth->bank_id!='')
                    <strong>{{$oth->bank_details->bank}}<br> ({{$oth->bank_details->account_no}})</strong>
                    @endif
                </td>
                <td colspan="4">
                    <textarea class="form-control adjRemarksPrev" name="adjRemarksPrev[]" id="adjRemarks[]" style="resize:none;height:85px;" placeholder="Remarks if any">{{$oth->remarks}}</textarea>
                </td>
                <td>
                </td>
            </tr>
            @endforeach
        @endif
        {{-- @if((is_null($remaining) && $others->isEmpty()) || ($remaining>0 && !$others->isEmpty())) --}}
        @if($remaining!=0)
        <tr class="parent-row">
            <td>
                {!!Form::text("adjHead[]",null,['class'=>'form-control','placeholder'=>'e.g. Food','required'])!!}
                <input type="text" name="adjDate[]" class="form-control adjDate" readonly placeholder="Date.." required>
            </td>    
            <td>
                {!! Form::select('adjMethod[]',$method,null,['class'=>'form-control adjMethod','required','placeholder'=>'Select']) !!}
                {!!Form::text("adjRef[]",null,['class'=>'form-control adjRef','placeholder'=>'Reference No.'])!!}
            </td>
            <td>
                <input type="text"  name="adjAmount[]" class="form-control adjAmount" placeholder="Enter Amount.." required  onkeypress='return isNumber(event,this)'>
                {!! Form::select('adjType[]',$type,null,['class'=>'form-control adjType','required','placeholder'=>'Select']) !!}
                {!! Form::select('adjBank[]',$bank,null,['class'=>'form-control adjBank','required','placeholder'=>'Select']) !!}
            </td>
            <td colspan="4">
                {{-- <input type="text"  name="adjRemarks[]" class="form-control adjRemarks" placeholder="Enter Remarks.."> --}}
                <textarea class="form-control adjRemarks" name="adjRemarks[]" id="adjRemarks[]" style="resize:none;height:85px;" placeholder="Remarks if any"></textarea>
            </td>
            <td>
                <button type="button" class="btn btn-primary addmore" id="addmore"><span class="fa fa-plus"></span> Add More</button>
            </td>
        </tr>
        <tr>
            <td colspan="8">
                {!!Form::submit("Submit",['class'=>'btn btn-success',"id"=>'subAdjust','name'=>'subAdjust'])!!}
            </td>
        </tr>
        @endif
    </tbody>
</table>
