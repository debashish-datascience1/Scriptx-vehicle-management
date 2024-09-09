@extends('layouts.app')

@section('extra_css')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">
    <style>
        .remove-section {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .fastag-entry {
            position: relative;
            padding-bottom: 30px;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .remove-button-container {
            position: absolute;
            bottom: 0;
            right: 0;
        }
        #grand-total-container {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
@endsection

@section("breadcrumb")
    <li class="breadcrumb-item"><a href="{{ route("fastag.index") }}">Fastag</a></li>
    <li class="breadcrumb-item active">Edit Fastag Entry</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-warning">
            <div class="card-header with-border">
                <h3 class="card-title">Edit Fastag Entry</h3>
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

                {!! Form::open(['route' => ['fastag.update', $fastag->id], 'method' => 'put', 'id' => 'fastagForm']) !!}
                {!! Form::hidden('grand_total', $fastag->total_amount, ['id' => 'grand_total_input']) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('vehicle_id',__('fleet.selectVehicle'), ['class' => 'form-label']) !!}
                            <select name="vehicle_id" class="form-control vehicle-select" required>
                                <option value="">-</option>
                                @foreach($vehicles as $vehicle)
                                <option value="{{$vehicle->id}}" {{ $fastag->registration_number == "{$vehicle->make} - {$vehicle->model} - {$vehicle->license_plate}" ? 'selected' : '' }}>
                                    {{$vehicle->make}} - {{$vehicle->model}} - {{$vehicle->license_plate}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div id="fastag-entries">
                    @foreach($fastagEntries as $index => $entry)
                    <div class="fastag-entry">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('fastag[]', 'Fastag Number', ['class' => 'form-label required']) !!}
                                    <select name="fastag[]" class="form-control fastag-select" required>
                                        <option value="">Select Fastag</option>
                                        @foreach($bank_accounts as $account)
                                            <option value="{{ $account->id }}" {{ $entry->fastag == "{$account->bank} - {$account->account_no}" ? 'selected' : '' }}>
                                                {{ $account->bank }} - {{ $account->account_no }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('date[]', 'Date', ['class' => 'form-label required']) !!}
                                    {!! Form::text('date[]', $entry->date, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('toll_gate_name[]', 'Toll Gate Name', ['class' => 'form-label required']) !!}
                                    {!! Form::text('toll_gate_name[]', $entry->toll_gate_name, ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('amount[]', 'Amount', ['class' => 'form-label required']) !!}
                                    {!! Form::number('amount[]', $entry->amount, ['class' => 'form-control amount-input', 'required', 'step' => '0.01']) !!}
                                </div>
                            </div>
                        </div>
                        @if($index > 0)
                        <div class="remove-button-container">
                            <span class="remove-section">Remove</span>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                <div id="grand-total-container">
                    Grand Total: <span id="grand-total">{{ $fastag->total_amount }}</span>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="button" id="add-more" class="btn btn-info">Add More</button>
                        {!! Form::submit('Update', ['class' => 'btn btn-warning']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {
    let entryCount = {{ count($fastagEntries) }};

    initializeSelect2();
    initializeDatepicker();
    initializeAmountListeners();

    $('#add-more').click(function() {
        entryCount++;
        const newEntry = createNewEntry(entryCount);
        $('#fastag-entries').append(newEntry);
        initializeSelect2($('#fastag-entries .fastag-entry:last'));
        initializeDatepicker($('#fastag-entries .fastag-entry:last'));
        initializeAmountListeners($('#fastag-entries .fastag-entry:last'));
    });

    $(document).on('click', '.remove-section', function() {
        $(this).closest('.fastag-entry').remove();
        updateGrandTotal();
    });

    function createNewEntry(index) {
        return `
            <div class="fastag-entry">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fastag_${index}" class="form-label required">Fastag Number</label>
                            <select name="fastag[]" id="fastag_${index}" class="form-control fastag-select" required>
                                <option value="">Select Fastag</option>
                                @foreach($bank_accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->bank }} - {{ $account->account_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_${index}" class="form-label required">Date</label>
                            <input type="text" name="date[]" id="date_${index}" class="form-control datepicker" required autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="toll_gate_name_${index}" class="form-label required">Toll Gate Name</label>
                            <input type="text" name="toll_gate_name[]" id="toll_gate_name_${index}" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount_${index}" class="form-label required">Amount</label>
                            <input type="number" name="amount[]" id="amount_${index}" class="form-control amount-input" required step="0.01">
                        </div>
                    </div>
                </div>
                <div class="remove-button-container">
                    <span class="remove-section">Remove</span>
                </div>
            </div>
        `;
    }

    function initializeSelect2(context = $('body')) {
        context.find('.vehicle-select').select2({placeholder: "@lang('fleet.selectVehicle')"});
        context.find('.fastag-select').select2({placeholder: "Select Fastag"});
    }

    function initializeDatepicker(context = $('body')) {
        context.find('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    }

    function initializeAmountListeners(context = $('body')) {
        context.find('.amount-input').on('input', function() {
            updateGrandTotal();
        });
    }

    function updateGrandTotal() {
        let total = 0;
        $('.amount-input').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#grand-total').text(total.toFixed(2));
        $('#grand_total_input').val(total.toFixed(2));
    }
});
</script>
@endsection