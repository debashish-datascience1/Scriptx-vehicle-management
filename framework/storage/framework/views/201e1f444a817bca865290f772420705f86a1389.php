<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('bank-account.index')); ?>"><?php echo app('translator')->getFromJson('fleet.bankAccount'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.bulkPay'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style>
    .form-label{display:block !important;}
    .inputlen {width: 95% !important}
    .checkbox, #chk_all{
        width: 20px;
        height: 20px;
    }
    .date,.method{margin-bottom: 7px;}
    .where_from,.advance_for{cursor: pointer;}
    .where_from{color:#fff!important; }
    .border-refund{border:2px solid #02bcd1; }
    .badge-driver-adv{background: royalblue;color:#fff;}
    .badge-parts{background: darkslategrey;color:#fff;}
    .badge-refund{background: darkviolet;color:#fff;}
    .badge-fuel{background: #8bc34a;color:#fff;}
    .badge-starting-amt{background: #c34a4a;color:#fff;}
    .form-control{position: relative !important;}
    .calculate{cursor: pointer;}
    .remarks{height: 70px;resize: none;margin-top: 10px;display: none}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Bulk Pay
        </h3>
      </div>

      <div class="card-body">
        <?php echo Form::open(['route' => 'bank-account.bulk_pay','method'=>'post','class'=>'form-inline']); ?>

        <div class="row">
          <div class="col-md-4 mb-2">
            <div class="form-group">
              <?php echo Form::label('vendor', 'Vendor', ['class' => 'form-label']); ?>

              <?php echo Form::select('vendor',$vendors,$vendor,['class'=>'form-control inputlen','placeholder'=>'Select Vendor','required',$vendorSelect]); ?>

            </div>
          </div>
          <div class="col-md-4 mb-2">
            <div class="form-group">
              <?php echo Form::label('from_date', 'From Date', ['class' => 'form-label']); ?>

              <?php echo Form::text('from_date',$from_date,['class'=>'form-control inputlen','readonly']); ?>

            </div>
          </div>
          <div class="col-md-4 mb-2">
            <div class="form-group">
              <?php echo Form::label('to_date', 'To Date', ['class' => 'form-label']); ?>

              <?php echo Form::text('to_date',$to_date,['class'=>'form-control inputlen','readonly']); ?>

            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <button type="submit" class="btn btn-success" id="sub">Submit</button>
            </div>
          </div>
          
          <?php echo Form::close(); ?>

        </div>
      </div>
    </div>
  </div>
</div>

<?php if(isset($result)): ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
          <?php echo app('translator')->getFromJson('fleet.bulkPay'); ?>
        </h3>
      </div>

      <div class="card-body table-responsive">
      <?php echo Form::open(['route' => 'bulk_pay.store','method'=>'post','id'=>'formSub']); ?>

        <table class="table table-bordered table-striped table-hover"  id="">
          <thead>
            <?php if(!empty($custvend)): ?>
            <tr>
                <td colspan="6" align="center" style="font-size:23px;"><strong><?php echo e($custvend); ?></strong></td>
            </tr>
            <?php endif; ?>
            <tr>
              <th>
                <?php if(count($transactions)>0): ?>
                <input type="checkbox" id="chk_all">
                <?php endif; ?>
              </th>
              <th>Transaction ID</th>
              <th style="width: 20%">Date</th>
              <th>Freight Amount</th>
              <th style="width: 25%">Balance Remaining</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if(count($transactions)>0): ?>
                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                <td>
                    <input type="checkbox" name="ids[<?php echo e($row->id); ?>]" value="<?php echo e($row->id); ?>" class="checkbox" id="chk<?php echo e($row->id); ?>" onclick='checkcheckbox();'>
                </td>
                <td>
                  <?php echo e($row->transaction_id); ?><br>
                  <?php if(!empty($row->license_plate)): ?>
                    <strong style="font-size: 15px;"><?php echo e($row->license_plate); ?></strong><br>
                  <?php endif; ?>
                  <?php if($row->param_id==18): ?>
                      <a class="badge badge-success where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo app('translator')->getFromJson('fleet.view'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                      <br>
                      <?php if($row->advance_for==21): ?>
                        <a class="badge badge-warning advance_for" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#advanceForModal" title="<?php echo app('translator')->getFromJson('fleet.view'); ?>"><?php echo e($row->advancefor->label); ?></a>
                      <?php endif; ?>
                      <?php elseif($row->param_id==19): ?>
                        <a class="badge badge-info where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                      <?php elseif($row->param_id==20): ?>
                        <a class="badge badge-fuel where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                      <?php elseif($row->param_id==25): ?>
                        <a class="badge badge-driver-adv where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                      <?php elseif($row->param_id==26): ?>
                        <a class="badge badge-parts where_from" data-id="<?php echo e($row->id); ?>" data-partsw=<?php echo e($row->id); ?> data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                      <?php elseif($row->param_id==27): ?>
                        <a class="badge badge-refund where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                      <?php elseif($row->param_id==28): ?>
                        <a class="badge badge-info where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                      <?php elseif($row->param_id==29): ?>
                        <a class="badge badge-starting-amt where_from" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#whereModal" title="<?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?>"><?php echo e(!empty($row->params) ? $row->params->label : 'N/A'); ?></a>
                      <?php else: ?><?php echo e(dd($row->param_id)); ?>

                  <?php endif; ?>
                </td>
                <td>
                  <?php echo e(date("H:i:s",strtotime($row->date))!="00:00:00" ? Helper::getCanonicalDateTime($row->date,'default') : Helper::getCanonicalDate($row->date,'default')); ?>

                </td>
                <td><?php echo e(Helper::properDecimals($row->total)); ?></td>
                <td class="remnants">
                  <strong><?php echo e(Helper::properDecimals($row->incExp->remaining)); ?></strong>
                  <input type="hidden" name="hid[<?php echo e($row->id); ?>]" class="hid" value="<?php echo e(Helper::properDecimals($row->incExp->remaining)); ?>">
                  <div class='input-group date'>
                    <div class="input-group-prepend" title="Full Amount">
                      <span class="input-group-text"><span class="fa fa-clipboard"></span></span>
                    </div>
                    <?php echo Form::text("price[$row->id]",null,['class'=>'form-control price','disabled','placeholder'=>'Price...','step'=>'any','onkeypress'=>'return isNumber(event,this)']); ?><br>
                  </div>
                  
                </td>
                <td>
                <?php if($row->param_id==18): ?>
                  <?php echo Form::label('driver',$row->booking->driver->name, ['class' => 'form-label']); ?>

                <?php endif; ?>
                  <?php echo Form::select("faulty[$row->id]",$faulty,null,['class'=>'form-control faulty','placeholder'=>'Select','disabled']); ?>

                  <?php echo Form::textarea("remarks[$row->id]",null,['class'=>'form-control remarks','placeholder'=>'Remarks...','disabled']); ?>

                </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
            <tr>
                <td align="center" colspan="6" style="color:red">No Records Found...</td>
            </tr>
            <?php endif; ?>
          </tbody>
          <?php if(count($transactions)>0): ?>
          <tfoot>
            <tr>
                <th colspan="3"></th>
                <th> Grand Total </th>
                <th> Total Balance</th>
                <th> Entered Amount <span class="badge badge-primary calculate">Calculate</span></th>
            </tr>
            <tr> 
                <th colspan="3"></th>
                <th>
                <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($totalAmount)); ?>

                
                <?php echo Form::hidden('total',base64_encode(Helper::properDecimals($remm)),['id'=>'total']); ?>

                <?php echo Form::hidden('amount',null,['id'=>'amount']); ?>

                </th>
                <th><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(Helper::properDecimals($remm)); ?></th>
                <th><?php echo e(Hyvikk::get('currency')); ?> <span id="showedAmt">0.00</span></th>
            </tr>
            <tr>
              <th colspan="2"></th>
              <th>
                <?php echo Form::label('method','Payment Method', ['class' => 'form-label']); ?>

                <?php echo Form::select("method",$method,null,['class'=>'form-control method','placeholder'=>'Select Method','required']); ?>

              </th>
              <th>
                <?php echo Form::label('reference','Reference No.', ['class' => 'form-label']); ?>

                <?php echo Form::text("reference",null,['class'=>'form-control reference','placeholder'=>'Reference No..','required']); ?>

              </th>
              <th>
                <?php echo Form::label('bank_account','Bank Account', ['class' => 'form-label']); ?>

                <?php echo Form::select('bank_account',$bankAccounts,null,['class'=>'form-control','placeholder'=>'Select','required']); ?>

              </th>
              <th colspan="2">
                <?php echo Form::label('date','Date', ['class' => 'form-label']); ?>

                <?php echo Form::text('date',null,['class'=>'form-control date','readonly','id'=>'date']); ?>

              </th>
            </tr>
            <tr>
                <th colspan="4"></th>
                <th colspan="2">
                    <?php echo Form::submit('Confirm',['class'=>'btn btn-success','id'=>'finalSub','name'=>'finalSub','style'=>'width:20%;padding:0px;','disabled']); ?>

                </th>
            </tr>
          </tfoot>
          <?php endif; ?>
        </table>
      </div>
      <?php echo Form::close(); ?>

    </div>
  </div>
    <!-- Modal -->
    <div id="whereModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                Loading....
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#user_id").select2();
	});
</script>

<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/jszip.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/pdfmake.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/vfs_fonts.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datetimepicker.js')); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {

  $('#from_date,#to_date,#date').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
  });

  $(".faulty").change(function(){
    if($(this).val()!="")
      $(this).closest("tr").find(".remarks").prop("disabled",false).show();
    else
      $(this).closest("tr").find(".remarks").prop("disabled",true).hide();
  })

  $(".price").on("keyup",function(){
    var act = $(this).closest("tr").find(".hid").val();
    var ent = $(this).val();
    var self = $(this);
    // console.log(act+' - '+ent);
    // console.log(ent>act)
    var sendData={_token:"<?php echo e(csrf_token()); ?>",act:act,ent:ent};
    $.post('<?php echo e(route("bulk_pay.compareValues")); ?>',sendData).done(function(result){
        // console.log(result);
        // return false;
      if(result.status) self.val('').focus();
    })
  })

  $("#finalSub").click(function(){
    var blankTest = /\S/;
    var date = $("#date").val();
    // console.log(date);
    // console.log(date.length);
    // console.log(typeof date);
    // console.log(!blankTest.test(date));
    // return false;
    if(!blankTest.test(date)){
      alert("Date cannot be empty");
      $("#date").focus();
      return false;
    }
  })

  $(".calculate").click(function(){
    var sendData={_token:"<?php echo e(csrf_token()); ?>",sum:[]};
    $(".price").each(function(){
      if($(this).prop("readonly")==false){
        sendData.sum.push($(this).val());
      }
    })
    // console.log(sendData);
    $.post('<?php echo e(route("bulk_pay.getAmount")); ?>',sendData).done(function(result){
        console.log(result);
        // return false;
        $("#showedAmt").html(result.total);
    })
  })

  $(".input-group-prepend").hover(function(){
    var ischk = $(this).closest("tr").find(".checkbox").prop('checked');
    if(ischk){
      $(this).attr('title','Copy Full Amount').css('cursor','pointer');
    }else{
      $(this).attr('title','').css('cursor','');
    }
  })
  $(".input-group-prepend").click(function(){
    var ischk = $(this).closest("tr").find(".checkbox").prop('checked');
    if(ischk){
      var hidval = $(this).closest("tr").find('.hid').val();
      $(this).closest("tr").find('.price').val(hidval)
    }
  })

  $(".faulty").each(function(){
    $(this).css("pointer-events","none");
  })

	$('#myTable tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="'+title+'" />' );
  });
    var myTable = $('#myTable').DataTable({
      dom: 'Bfrtip',
      buttons: [{
           extend: 'collection',
              text: 'Export',
              buttons: [
                  'copy',
                  'excel',
                  'csv',
                  'pdf',
              ]}
      ],
      "language": {
               "url": '<?php echo e(__("fleet.datatable_lang")); ?>',
            },
      "initComplete": function() {
              myTable.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                    that.search(this.value).draw();
                });
              });
            }

        // 'initComplete': function (settings, json){
        //     this.api().columns('.sum').every(function(){
        //         var column = this;

        //         var sum = column
        //             .data()
        //             .reduce(function (a, b) { 
        //             a = parseInt(a, 10);
        //             if(isNaN(a)){ a = 0; }                   

        //             b = parseInt(b, 10);
        //             if(isNaN(b)){ b = 0; }

        //             return a + b;
        //             });

        //         $(column.footer()).html('Sum: ' + sum);
        //     });
        // }
    });
    
    $(function(){
      // $("#customer").change(function(){
      //     $(this).val()=="" ? $("#vendor").prop('disabled',false) : $("#vendor").prop('disabled',true);
      // })
      // $("#vendor").change(function(){
      //     $(this).val()=="" ? $("#customer").prop('disabled',false) : $("#customer").prop('disabled',true);
      // })

      // $('#date').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss',sideBySide: true,icons: {
      //       previous: 'fa fa-arrow-left',
      //       next: 'fa fa-arrow-right',
      //       up: "fa fa-arrow-up",
      //       down: "fa fa-arrow-down"
      //   }});
      
      // $("#sub").click(function(){
      //     var blankTest = /\S/;
      //     var vendor = $("#vendor").val();
      //     var customer = $("#customer").val();
      //     if(!blankTest.test(vendor) && !blankTest.test(customer)){
      //         alert("Please choose Vendor or Customer");
      //         return false;
      //     }
      // })
    })
    

    $(".where_from").on("click",function(){
        var id = $(this).data("id");
        var partsw = $(this).data("partsw");
        console.log(id);
        console.log(partsw);
        $("#whereModal .modal-content").load('<?php echo e(url("admin/accounting/where_from")); ?>/'+id,function(res){
        typeof partsw!="undefined" ? $(this).css('width','160%') : $(this).css('width','');
        $("#whereModal").modal({show:true})
        })
    })

  

    $('#chk_all').on('click',function(){
        if(this.checked){
        $('.checkbox').each(function(){
            $('.checkbox').prop("checked",true);
        });
        }else{
        $('.checkbox').each(function(){
            $('.checkbox').prop("checked",false);
        });
        }
    });
    
    $("body").on("click",".checkbox,#chk_all",function(){
        var self = $(this);
        if(self.attr("id")=="chk_all"){ //all
          if($(this).prop("checked")==true){ //all checked
            $(".checkbox").each(function(){
              $(this).prop("checked",true);
              $(this).closest("tr").find(".price").prop({'disabled':false,'required':true});
              // $(this).closest("tr").find(".reference").prop({'disabled':false,'required':true});
              $(this).closest("tr").find(".faulty").attr('disabled',false).prop('required',true).css("pointer-events","");
              // $(this).closest("tr").find(".method").attr('disabled',false).prop('required',true).css("pointer-events","");
            })
          }else{ //all unchecked
          $(".checkbox").each(function(){
              $(this).prop("checked",false);
              $(this).closest("tr").find(".price").prop({'disabled':true,'required':false}).val('');
              // $(this).closest("tr").find(".reference").prop({'disabled':true,'required':false}).val('');
              $(this).closest("tr").find(".faulty").attr('disabled',true).prop('required',false).css("pointer-events","none").val($(".faulty option:eq(0)").val()).change();
              // $(this).closest("tr").find(".method").attr('disabled',true).prop('required',false).css("pointer-events","none").val($(".method option:eq(0)").val()).change();
            })
          }
        }else{ //single
          if($(this).prop("checked")==true){ //single checked
            $(this).closest("tr").find(".price").prop({'disabled':false,'required':true});
            // $(this).closest("tr").find(".reference").prop({'disabled':false,'required':true});
            $(this).closest("tr").find(".faulty").attr('disabled',false).prop('required',true).css("pointer-events","");
            // $(this).closest("tr").find(".method").attr('disabled',false).prop('required',true).css("pointer-events","");
          }else{ //single unchecked
            $(this).closest("tr").find(".price").prop({'disabled':true,'required':false}).val('');
            // $(this).closest("tr").find(".reference").prop({'disabled':true,'required':false}).val('');
            $(this).closest("tr").find(".faulty").attr('disabled',true).prop('required',false).css("pointer-events","none").val("").change();
            // $(this).closest("tr").find(".method").attr('disabled',true).prop('required',false).css("pointer-events","none").val("");
          }
        }

        $(".checkbox:checked").length>0 ? $("#finalSub").prop("disabled",false) : $("#finalSub").prop("disabled",true);
    })

    // $("#paid").keyup(function(){
    //     var amount = $("#amount").val(btoa($(this).val()));

    // })
    
    

    // $("#formSub").on('submit',function(e){
    //     // alert()
    //     var float= /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;
    //     var kamount = $("#paid").val();
    //     var rl = window.atob($('#total').val());
    //     if(!float.test(kamount)){
    //         $("#paid").val('');
    //         alert("Invalid Amount..");
    //         $("#paid").focus();
    //         return false;
    //     }else{
    //       var remTotal=0;
    //       var tRem = $(".checkbox:checked").length;
    //       // console.log(tRem);
    //       $(".checkbox:checked").each(function(k,v){
    //         var amountRemnant = parseFloat($(this).data("rem"));
    //         if((tRem>1 && tRem-1!=k))
    //           remTotal+=amountRemnant;
    //         else if(tRem==1 && k==0)
    //           remTotal=0;
    //       })
    //         console.log(remTotal);
    //         var aamount = window.atob($('#amount').val());
    //         console.log(aamount)

    //         if(aamount==null || aamount==0 || aamount==0.00){
    //             alert('Please enter payment amount..');
    //             $("#paid").val('').css('border','1px solid red').fadeIn('2000').focus();
    //             return false;
    //         }else{
    //             $("#paid").css('border','');
    //         }
    //         console.log(Math.round(remTotal * 100));
    //         console.log(Math.round(kamount * 100));
    //         console.log(1);

    //         if(Math.round(remTotal * 100)>=Math.round(kamount * 100)){
    //           $("#paid").val('');
    //             alert('Pay more than '+remTotal+' or unselect some transactions');
    //             return false;
    //         }else if(Math.round(kamount * 100)>Math.round(aamount * 100)){
    //             $("#paid").val('');
    //             // alert('Can\'t ')
    //             return false;
    //         }
    //         // e.preventDefault();return false;
    //     }
        
    //     // console.log(kamount);
    //     // console.log(aamount);
    //     // e.preventDefault();
    //     // return false;
    // })

});

// Check Number and Decimal
function isNumber(evt, element) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (            
        (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57))
        return false;
        return true;
}


// Checkbox checked
    function checkcheckbox(){
        // Total checkboxes
        var length = $('.checkbox').length;
        // Total checked checkboxes
        var totalchecked = 0;
        $('.checkbox').each(function(){
            if($(this).is(':checked')){
                totalchecked+=1;
            }
        });
        // console.log(length+" "+totalchecked);
        // Checked unchecked checkbox
        if(totalchecked == length){
            $("#chk_all").prop('checked', true);
        }else{
            $('#chk_all').prop('checked', false);
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/bank_account/bulk_pay.blade.php ENDPATH**/ ?>