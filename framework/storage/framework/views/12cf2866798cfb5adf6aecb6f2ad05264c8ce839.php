<div class="parts-list row" >
        <div class="col-md-6">
           
            <div class="form-group">
              <?php echo Form::label('title', __('fleet.title'), ['class' => 'form-label']); ?>

              <?php echo Form::text('title[]', null,['class' => 'form-control title']); ?>

            </div>

            <div class="form-group">
              <?php echo Form::label('number', __('fleet.number'), ['class' => 'form-label']); ?>

              <?php echo Form::text('number[]', null,['class' => 'form-control number']); ?>

            </div>

           
            <div class="form-group">
              <?php echo Form::label('status',__('fleet.status'), ['class' => 'form-label']); ?>

              <?php echo Form::select('status[]',["Active"=>"Active","Pending"=>"Pending", "Processing"=>"Processing", "Completed"=>"Completed","Hold"=>"Hold"],null,['class' => 'form-control']); ?>

            </div>

            <div class="form-group">
              <?php echo Form::label('availability',__('fleet.availability'), ['class' => 'form-label']); ?>

              <select  name="availability[]" class="form-control" >
               <option value="1">Available</option>
                <option value="0">Not Available</option>
               
                </select>
          </div>

            
           
            <hr>
            <div class="form-group">
              <?php echo Form::label('dateofpurchase',__('fleet.dateofpurchase'), ['class' => 'form-label']); ?>

              <div class='input-group mb-3 date'>
                <div class="input-group-prepend">
                  <span class="input-group-text"> <span class="fa fa-calendar"></span></span>
                </div>
                <?php echo Form::text('dateofpurchase[]',date("d-m-Y"),['class'=>'form-control dateofpurchase']); ?>

              </div>
            </div>  
           
              <!--debi start-->
              
            
            <!--debi end-->
            
           
           
            <div class="blank"></div>
        </div>
        <div class="col-md-6">
            
            <div class="form-group">
              <?php echo Form::label('category_id',__('fleet.parts_category'), ['class' => 'form-label ']); ?>

              <select  name="category_id[]" class="form-control category_id" >
                <option value="">-</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>

            <div class="form-group">
              <?php echo Form::label('manufacturer', __('fleet.manufacturer'), ['class' => 'form-label']); ?>

              <?php echo Form::text('manufacturer[]', null,['class' => 'form-control manufacturer']); ?>

            </div>

            
            <div class="cal_div">
            <div class="form-group">
              <?php echo Form::label('unit_cost', __('fleet.unit_cost'), ['class' => 'form-label']); ?>

              <div class="input-group date">
                <div class="input-group-prepend">
                <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span> </div>

                <?php echo Form::number('unit_cost[]', null,['class' => 'form-control unit_cost']); ?>

              </div>
            </div>
            <div class="form-group">
              <?php echo Form::label('stock', __('fleet.quantity'), ['class' => 'form-label']); ?>

              <?php echo Form::number('stock[]', null,['class' => 'form-control stock']); ?>

            </div>
            <div class="form-group">
              <?php echo Form::label('total', __('fleet.total'), ['class' => 'form-label']); ?>

              <?php echo Form::number('total[]', null,['class' => 'form-control total','readonly']); ?>

            </div>
          </div>
          
        </div>
    </div>
    <div class="more hide "> 
        
            <div class="col-md-6">            
                <div class="form-group">
                    <?php echo Form::label('title', __('fleet.title'), ['class' => 'form-label']); ?>

                    <?php echo Form::text('title[]', null,['class' => 'form-control title']); ?>

                </div>

                <div class="form-group">
                    <?php echo Form::label('number', __('fleet.number'), ['class' => 'form-label']); ?>

                    <?php echo Form::text('number[]', null,['class' => 'form-control number']); ?>

                </div>

                
                <div class="form-group">
                    <?php echo Form::label('status',__('fleet.status'), ['class' => 'form-label']); ?>

                    <?php echo Form::select('status[]',["Active"=>"Active","Pending"=>"Pending", "Processing"=>"Processing", "Completed"=>"Completed","Hold"=>"Hold"],null,['class' => 'form-control']); ?>

                </div>

                <div class="form-group">
                  <?php echo Form::label('availability',__('fleet.availability'), ['class' => 'form-label']); ?>

                  <select  name="availability[]" id="availability" class="form-control" >
                   <option value="1">Available</option>
                    <option value="0">Not Available</option>
                   
                    </select>
              </div>
                
                <hr>
                <div class="form-group">
                  <?php echo Form::label('dateofpurchase',__('fleet.dateofpurchase'), ['class' => 'form-label']); ?>

                  <div class='input-group mb-3 date'>
                  <div class="input-group-prepend">
                      <span class="input-group-text"> <span class="fa fa-calendar"></span></span>
                  </div>
                  <?php echo Form::text('dateofpurchase[]',date("d-m-Y"),['class'=>'form-control dateofpurchase']); ?>

                  </div>
              </div> 
                

                
               
                
                <div class="blank"></div>
            </div>

            <div class="col-md-6">                
                <div class="form-group">
                    <?php echo Form::label('category_id',__('fleet.parts_category'), ['class' => 'form-label']); ?>

                    <select  name="category_id[]" class="form-control category_id" >
                    <option value="">-</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <?php echo Form::label('manufacturer', __('fleet.manufacturer'), ['class' => 'form-label']); ?>

                    <?php echo Form::text('manufacturer[]', null,['class' => 'form-control manufacturer']); ?>

                </div>

                
                <div class="cal_div">  
                <div class="form-group">
                  <?php echo Form::label('unit_cost', __('fleet.unit_cost'), ['class' => 'form-label']); ?>

                  <div class="input-group date">
                  <div class="input-group-prepend">
                  <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span> </div>

                  <?php echo Form::number('unit_cost[]', null,['class' => 'form-control unit_cost']); ?>

                  </div>
              </div>
                <div class="form-group">
                    <?php echo Form::label('stock', __('fleet.qty_on_hand'), ['class' => 'form-label']); ?>

                    <?php echo Form::number('stock[]', null,['class' => 'form-control stock']); ?>

                </div>
                <div class="form-group">
                <?php echo Form::label('total', __('fleet.total'), ['class' => 'form-label']); ?>

                <?php echo Form::number('total[]', null,['class' => 'form-control total','readonly']); ?>

                </div>
               
          </div>
            
          
            </div>
        

    </div> 
    <div class="col-md-6">
    <div class="form-group">
      <?php echo Form::label('subtotal', __('fleet.subtotal'), ['class' => 'form-label']); ?>

      <?php echo Form::number('subtotal', null,['class' => 'form-control subtotal','readonly']); ?>

      </div>
    
      <div class="form-group">
        <?php echo Form::label('cash_payment', __('fleet.cash_payment'), ['class' => 'form-label']); ?>

        <div class="input-group date">
        <div class="input-group-prepend">
         </div>

        <?php echo Form::number('cash_payment', null,['class' => 'form-control cash_payment']); ?>

        </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="form-group">
      <?php echo Form::label('cheque_draft', __('fleet.cheque_draft'), ['class' => 'form-label']); ?>

      <div class="input-group date">
      <div class="input-group-prepend">
       </div>

      <?php echo Form::text('cheque_draft', null,['class' => 'form-control cheque_draft']); ?>

      </div>
      
  </div>


  <div class="form-group">
    <?php echo Form::label('cheque_draft_amount', __('fleet.cheque_draft_amount'), ['class' => 'form-label']); ?>

    <div class="input-group date">
    <div class="input-group-prepend">
     </div>

    <?php echo Form::number('cheque_draft_amount', null,['class' => 'form-control cheque_draft_amount']); ?>

    </div>
    
</div>
<div class="form-group">
  <?php echo Form::label('cheque_draft_date',__('fleet.cheque_draft_date'), ['class' => 'form-label']); ?>

  <div class='input-group mb-3 date'>
  <div class="input-group-prepend">
      <span class="input-group-text"> <span class="fa fa-calendar"></span></span>
  </div>
  <?php echo Form::text('cheque_draft_date','',['class'=>'form-control cheque_draft_date']); ?>

  </div>
</div> 
</div>
    <div class="text-right">
        <button class="btn btn-primary" type="button" id="button_addform" name="button" onclick="appendNewParts()"><?php echo e(__('Add More')); ?></button>
    </div>
<script type="text/javascript">
    
    $(document).ready(function(){
      $("body").on("keyup","#cash_payment,#cheque_draft_amount",function(){
        var cash_payment=parseFloat($('#cash_payment').val()).toFixed(2);
        var cheque_draft_amount=parseFloat($('#cheque_draft_amount').val()).toFixed(2);
        var subtotal=parseFloat($('#subtotal').val());
        
        
        // $(':input[type="submit"]').prop('disabled', false);
          //console.log(cash_payment+">"+subtotal +"||"+cheque_draft_amount+">"+subtotal);
         // console.log(typeof cash_payment+">"+ typeof subtotal +"||"+typeof cheque_draft_amount+">"+typeof subtotal);
            if(cash_payment>subtotal || cheque_draft_amount>subtotal)
         {
           $(':input[type="submit"]').prop('disabled', true);
         }
         else
         {
          $(':input[type="submit"]').prop('disabled', false);
         }
         })


    $("body").on("keyup",".unit_cost,.stock",function(){
       var stock  = $(this).closest('.cal_div').find('.stock').val();
       // var stock  = $(this).parent().parent().find('.stock ').val();
       var unit_cost  = $(this).closest('.cal_div').find('.unit_cost ').val();
       var cash_payment=$('#cash_payment').val();
        //var unit_cost  = $sss(this).parent().parent().find('.unit_cost ').val();
        var total=parseFloat(stock)*parseFloat(unit_cost);
        var totalamnt  = $(this).closest('.cal_div').find('.total').val(parseFloat(total).toFixed(2));
        
        var sumtotal=0;
        if(stock!="" && unit_cost!="")
        {
          $(".total").each(function(i,e){
            var thisval = $(this).val();
            if((thisval=="" && typeof thisval=='string') || (thisval==0 && typeof thisval=='string')){
              thisval=0.00;
            }
             
            thisval = parseFloat(thisval);
            
            if(thisval!="")
              sumtotal= parseFloat(sumtotal)+thisval;
          
          })

           $(".subtotal").val(sumtotal.toFixed(2));
        }
    });
  });
    
    
    function appendNewParts(){
      
        $('.parts-list').append($('.more').html());
        $('.parts-list').find('.part-choose').last().find('.category_id').select2({placeholder:"<?php echo app('translator')->getFromJson('fleet.parts_category'); ?>"});
        $('.dateofpurchase').datetimepicker({format: 'DD-MM-YYYY',sideBySide: true,icons: {
              previous: 'fa fa-arrow-left',
              next: 'fa fa-arrow-right',
              up: "fa fa-arrow-up",
              down: "fa fa-arrow-down"
        }});
        
   
   
    }
    
    //$(".category_id").select2({placeholder:"<?php echo app('translator')->getFromJson('fleet.parts_category'); ?>"});
    $('.dateofpurchase').datetimepicker({format: 'DD-MM-YYYY',sideBySide: true,icons: {
              previous: 'fa fa-arrow-left',
              next: 'fa fa-arrow-right',
              up: "fa fa-arrow-up",
              down: "fa fa-arrow-down"
        }});
        $('#cheque_draft_date').datetimepicker({format: 'DD-MM-YYYY',sideBySide: true,icons: {
              previous: 'fa fa-arrow-left',
              next: 'fa fa-arrow-right',
              up: "fa fa-arrow-up",
              down: "fa fa-arrow-down"
        }});
  
    $(document).ready(function(){
        $('.demo-select2').select2();
        //get_subcategories_by_category($('.category_id'));
    });



</script><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/parts/parts_form.blade.php ENDPATH**/ ?>