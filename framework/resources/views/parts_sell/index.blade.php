@extends('layouts.app')
@section("breadcrumb")
<li class="breadcrumb-item active">@lang('menu.partSell')</li>
@endsection
@section('extra_css')
<style type="text/css">
  .checkbox, #chk_all{
    width: 20px;
    height: 20px;
  }
  @media print {
    .btn, .card-header, .breadcrumb, .main-header, .main-sidebar, .main-footer, .pagination {
      display: none !important;
    }
    .card {
      border: none !important;
    }
    .card-body {
      padding: 0 !important;
    }
    table {
      width: 100% !important;
    }
    .dropdown-toggle, .dropdown-menu, .btn-group {
      display: none !important;
    }
  }
</style>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">@lang('menu.partSell')
          <a href="{{ route('parts-sell.create')}}" class="btn btn-success">Sell Parts</a>
          <!-- <button onclick="printTable()" class="btn btn-primary">Print</button> -->
          <button onclick="printAll()" class="btn btn-primary">Print All</button>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th>@lang('fleet.sellTo')</th>
              <th>@lang('fleet.date')</th>
              <th>@lang('fleet.details')</th>
              <th>@lang('fleet.total')</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $transaction_id => $event)
              <tr>
                <td>{{ $event->first()->sell_to }}</td>
                <td>{{ $event->first()->date_of_sell }}</td>
                <td>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>@lang('fleet.item')</th>
                        <th>@lang('fleet.quantity')</th>
                        <th>@lang('fleet.amount')</th>
                        <th>@lang('fleet.total')</th>
                        <th>@lang('fleet.selltyreNumbers')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($event as $row)
                        <tr>
                          <td>{{ $items[$row->item] ?? $row->item }}</td>
                          <td>{{ $row->quantity }}</td>
                          <td>{{ Hyvikk::get('currency') . " " . $row->amount }}</td>
                          <td>{{ Hyvikk::get('currency') . " " . $row->total }}</td>
                          <td>
                            @php
                              $tyres = $row->tyre_numbers;
                              if (!empty($tyres)) {
                                  $numbers_array = explode(',', $tyres);
                                  $formatted_numbers = [];

                                  foreach (array_chunk($numbers_array, 4) as $chunk) {
                                      $formatted_numbers[] = implode(', ', $chunk);
                                  }

                                  $output = nl2br(implode("\n", $formatted_numbers));
                              } else {
                                  $output = 'N/A';
                              }

                              echo $output;
                            @endphp
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </td>
                <td>{{ Hyvikk::get('currency') . " " . $event->sum('total') }}</td>
                <td>
                  <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                      <span class="fa fa-gear"></span>
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu custom" role="menu">
                      <a class="dropdown-item" href="{{ url("admin/parts-sell/".$event->first()->id."/edit")}}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                      <a class="dropdown-item delete-sale" href="#" data-id="{{$event->first()->id}}"> <span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
                    </div>
                  </div>
                  {!! Form::open(['url' => 'admin/parts-sell/'.$event->first()->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$event->first()->id]) !!}
                  {!! Form::hidden("id",$event->first()->id) !!}
                  {!! Form::close() !!}
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>@lang('fleet.sellTo')</th>
              <th>@lang('fleet.date')</th>
              <th>@lang('fleet.details')</th>
              <th>@lang('fleet.total')</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </tfoot>
        </table>
        {{ $data->links() }}
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.delete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>@lang('fleet.confirm_delete')</p>
      </div>
      <div class="modal-footer">
        <button id="del_btn" class="btn btn-danger" type="button" data-submit="">@lang('fleet.delete')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

@endsection

@section('script')
<script type="text/javascript">
  $(".delete-sale").on("click", function(e){
    e.preventDefault();
    var id = $(this).data("id");
    if(confirm("Are you sure you want to delete this entire sale and all its parts?")) {
      $("#form_"+id).submit();
    }
  });

  function printTable() {
    var printWindow = window.open('', '_blank');
    var printContent = generatePrintContent(null, null);
    
    prepareAndPrint(printWindow, printContent, 'Parts Sell');
  }

  function printAll() {
    $.ajax({
      url: '{{ route("parts-sell.get-all-data") }}',
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        var printContent = generatePrintContent(response.data, response.items);
        var printWindow = window.open('', '_blank');
        
        prepareAndPrint(printWindow, printContent, 'All Parts Sold');
      },
      error: function(xhr, status, error) {
        console.error('Error fetching data:', error);
      }
    });
  }

  function prepareAndPrint(printWindow, content, title) {
    printWindow.document.write('<html><head><title>' + title + '</title>');
    
    // Copy the styles
    var styles = document.getElementsByTagName('style');
    for (var i = 0; i < styles.length; i++) {
      printWindow.document.write(styles[i].outerHTML);
    }
    
    printWindow.document.write('<style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid black; padding: 8px; text-align: left; }</style>');
    
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h2>' + title + '</h2>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    
    printWindow.onload = function() {
      printWindow.focus();
      printWindow.print();
      printWindow.close();
    };
  }

  function generatePrintContent(data, items) {
    var printContent = '<table>';
    printContent += '<thead><tr>';
    printContent += '<th>@lang("fleet.sellTo")</th>';
    printContent += '<th>@lang("fleet.date")</th>';
    printContent += '<th>@lang("fleet.details")</th>';
    printContent += '<th>@lang("fleet.total")</th>';
    printContent += '</tr></thead><tbody>';
    
    if (data === null) {
      // If data is null, we're printing the current page
      $('#data_table tbody tr').each(function() {
        var row = $(this);
        printContent += '<tr>';
        printContent += '<td>' + row.find('td:eq(0)').text() + '</td>';
        printContent += '<td>' + row.find('td:eq(1)').text() + '</td>';
        printContent += '<td>' + row.find('td:eq(2)').html() + '</td>';
        printContent += '<td>' + row.find('td:eq(3)').text() + '</td>';
        printContent += '</tr>';
      });
    } else {
      // If data is provided, we're printing all data
      $.each(data, function(transaction_id, event) {
        var firstEvent = event[0];
        var total = 0;
        
        printContent += '<tr>';
        printContent += '<td>' + firstEvent.sell_to + '</td>';
        printContent += '<td>' + firstEvent.date_of_sell + '</td>';
        printContent += '<td><table>';
        printContent += '<thead><tr>';
        printContent += '<th>@lang("fleet.item")</th>';
        printContent += '<th>@lang("fleet.quantity")</th>';
        printContent += '<th>@lang("fleet.amount")</th>';
        printContent += '<th>@lang("fleet.total")</th>';
        printContent += '<th>@lang("fleet.selltyreNumbers")</th>';
        printContent += '</tr></thead><tbody>';
        
        $.each(event, function(index, row) {
          printContent += '<tr>';
          printContent += '<td>' + (items[row.item] || row.item) + '</td>';
          printContent += '<td>' + row.quantity + '</td>';
          printContent += '<td>{{ Hyvikk::get("currency") }} ' + row.amount + '</td>';
          printContent += '<td>{{ Hyvikk::get("currency") }} ' + row.total + '</td>';
          printContent += '<td>' + formatTyreNumbers(row.tyre_numbers) + '</td>';
          printContent += '</tr>';
          total += parseFloat(row.total);
        });
        
        printContent += '</tbody></table></td>';
        printContent += '<td>{{ Hyvikk::get("currency") }} ' + total.toFixed(2) + '</td>';
        printContent += '</tr>';
      });
    }
    
    printContent += '</tbody></table>';
    return printContent;
  }

  function formatTyreNumbers(tyres) {
    if (!tyres) return 'N/A';
    var numbers_array = tyres.split(',');
    var formatted_numbers = [];
    for (var i = 0; i < numbers_array.length; i += 4) {
      formatted_numbers.push(numbers_array.slice(i, i + 4).join(', '));
    }
    return formatted_numbers.join('<br>');
  }
</script>
@endsection