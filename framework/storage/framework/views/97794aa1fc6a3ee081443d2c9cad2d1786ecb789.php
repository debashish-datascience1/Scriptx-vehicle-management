<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('menu.partSell'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<style type="text/css">
  .checkbox, #chk_all{
    width: 20px;
    height: 20px;
  }
  @media  print {
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('menu.partSell'); ?>
          <a href="<?php echo e(route('parts-sell.create')); ?>" class="btn btn-success">Sell Parts</a>
          <!-- <button onclick="printTable()" class="btn btn-primary">Print</button> -->
          <button onclick="printAll()" class="btn btn-primary">Print All</button>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th><?php echo app('translator')->getFromJson('fleet.sellTo'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.details'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.total'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction_id => $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($event->first()->sell_to); ?></td>
                <td><?php echo e($event->first()->date_of_sell); ?></td>
                <td>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th><?php echo app('translator')->getFromJson('fleet.item'); ?></th>
                        <th><?php echo app('translator')->getFromJson('fleet.quantity'); ?></th>
                        <th><?php echo app('translator')->getFromJson('fleet.amount'); ?></th>
                        <th><?php echo app('translator')->getFromJson('fleet.total'); ?></th>
                        <th><?php echo app('translator')->getFromJson('fleet.selltyreNumbers'); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $__currentLoopData = $event; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <td><?php echo e($items[$row->item] ?? $row->item); ?></td>
                          <td><?php echo e($row->quantity); ?></td>
                          <td><?php echo e(Hyvikk::get('currency') . " " . $row->amount); ?></td>
                          <td><?php echo e(Hyvikk::get('currency') . " " . $row->total); ?></td>
                          <td>
                            <?php
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
                            ?>
                          </td>
                        </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                  </table>
                </td>
                <td><?php echo e(Hyvikk::get('currency') . " " . $event->sum('total')); ?></td>
                <td>
                  <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                      <span class="fa fa-gear"></span>
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu custom" role="menu">
                      <a class="dropdown-item" href="<?php echo e(url("admin/parts-sell/".$event->first()->id."/edit")); ?>"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?></a>
                      <a class="dropdown-item delete-sale" href="#" data-id="<?php echo e($event->first()->id); ?>"> <span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> <?php echo app('translator')->getFromJson('fleet.delete'); ?></a>
                    </div>
                  </div>
                  <?php echo Form::open(['url' => 'admin/parts-sell/'.$event->first()->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$event->first()->id]); ?>

                  <?php echo Form::hidden("id",$event->first()->id); ?>

                  <?php echo Form::close(); ?>

                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
          <tfoot>
            <tr>
              <th><?php echo app('translator')->getFromJson('fleet.sellTo'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.details'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.total'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </tfoot>
        </table>
        <?php echo e($data->links()); ?>

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
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.delete'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><?php echo app('translator')->getFromJson('fleet.confirm_delete'); ?></p>
      </div>
      <div class="modal-footer">
        <button id="del_btn" class="btn btn-danger" type="button" data-submit=""><?php echo app('translator')->getFromJson('fleet.delete'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
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
      url: '<?php echo e(route("parts-sell.get-all-data")); ?>',
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
    printContent += '<th><?php echo app('translator')->getFromJson("fleet.sellTo"); ?></th>';
    printContent += '<th><?php echo app('translator')->getFromJson("fleet.date"); ?></th>';
    printContent += '<th><?php echo app('translator')->getFromJson("fleet.details"); ?></th>';
    printContent += '<th><?php echo app('translator')->getFromJson("fleet.total"); ?></th>';
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
        printContent += '<th><?php echo app('translator')->getFromJson("fleet.item"); ?></th>';
        printContent += '<th><?php echo app('translator')->getFromJson("fleet.quantity"); ?></th>';
        printContent += '<th><?php echo app('translator')->getFromJson("fleet.amount"); ?></th>';
        printContent += '<th><?php echo app('translator')->getFromJson("fleet.total"); ?></th>';
        printContent += '<th><?php echo app('translator')->getFromJson("fleet.selltyreNumbers"); ?></th>';
        printContent += '</tr></thead><tbody>';
        
        $.each(event, function(index, row) {
          printContent += '<tr>';
          printContent += '<td>' + (items[row.item] || row.item) + '</td>';
          printContent += '<td>' + row.quantity + '</td>';
          printContent += '<td><?php echo e(Hyvikk::get("currency")); ?> ' + row.amount + '</td>';
          printContent += '<td><?php echo e(Hyvikk::get("currency")); ?> ' + row.total + '</td>';
          printContent += '<td>' + formatTyreNumbers(row.tyre_numbers) + '</td>';
          printContent += '</tr>';
          total += parseFloat(row.total);
        });
        
        printContent += '</tbody></table></td>';
        printContent += '<td><?php echo e(Hyvikk::get("currency")); ?> ' + total.toFixed(2) + '</td>';
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/parts_sell/index.blade.php ENDPATH**/ ?>