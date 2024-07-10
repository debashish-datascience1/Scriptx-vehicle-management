<div role="tabpanel" style="margin-bottom: 10px;">
    <ul class="nav nav-pills">
        <li class="nav-item"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active" style="margin-bottom: 10px;"><i class="fa fa-"></i> General Information </a>
        </li>

        <li class="nav-item"><a href="#history-tab" data-toggle="tab" class="nav-link custom_padding"> <i class="fa fa-history"></i> History</a>
        </li>
    </ul>

    <div class="tab-content">
    <!-- General Information Tab-->
        <div class="tab-pane active" id="info-tab">
            <table class="table table-striped">
                <tr>
                    <th style="width: 145px;">Bank Name</th>
                    <td>{{$bankAccount->bank}}</td>
                </tr>
                <tr>
                    <th>Account No.</th>
                    <td>
                        {{$bankAccount->account_no}}
                    </td>
                </tr>
                <tr>
                    <th>IFSC Code</th>
                    <td>
                        {{$bankAccount->ifsc_code}}
                    </td>
                </tr>
                <tr>
                    <th>Branch</th>
                    <td>
                        {{$bankAccount->branch}}
                    </td>
                </tr>
                <tr>
                    <th>Account Holder</th>
                    <td>
                        {{$bankAccount->account_holder}}
                    </td>
                </tr>
                <tr>
                    <th>Starting Amount</th>
                    <td>
                        {{$bankAccount->starting_amount}}
                    </td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>
                        {{$bankAccount->address}}
                    </td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>
                        {{$bankAccount->mobile}}
                    </td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        {{$bankAccount->email}}
                    </td>
                </tr>
                {{-- <tr>
                    <th>Status</th>
                    <td>
                        @if($bankAccount->status==1)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                </tr> --}}
            </table>
        </div>
        <div class="tab-pane" id="history-tab">
            <table class="table table-striped">
                <tr>
                    <th>Bank</th>
                    <th>Refer Bank</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Remarks</th>
                </tr>
                @foreach($history as $h)
                <tr>
                    <td>{{$h->bank->bank}}</td>
                    <td>{{!empty($h->refer_bank) ? $h->referBank->bank : '-'}}</td>
                    <td>{{Hyvikk::get('currency')}}{{Helper::properDecimals($h->amount)}}</td>
                    <td>{{Helper::getCanonicalDate($h->date,'default')}}</td>
                    <td>{{!empty($h->remarks) ? Helper::limitText($h->remarks,40) : '-'}}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
