<tr class="parent-row">
    <td>
        <?php echo Form::text("adjHead[]",null,['class'=>'form-control','placeholder'=>'e.g. Food','required']); ?>

        <input type="text" name="adjDate[]" class="form-control adjDate" readonly placeholder="Date.." required>
    </td>    
    <td>
        <?php echo Form::select('adjMethod[]',$method,null,['class'=>'form-control adjMethod','required','placeholder'=>'Select']); ?>

        <?php echo Form::text("adjRef[]",null,['class'=>'form-control adjRef','placeholder'=>'Reference No.']); ?>

    </td>
    <td>
        <input type="text"  name="adjAmount[]" class="form-control adjAmount" placeholder="Enter Amount.." required onkeypress='return isNumber(event,this)'>
        <?php echo Form::select('adjType[]',$type,null,['class'=>'form-control adjType','required','placeholder'=>'Select']); ?>

        <?php echo Form::select('adjBank[]',$bank,null,['class'=>'form-control adjBank','required','placeholder'=>'Select','style'=>'display:none;']); ?>

    </td>
    <td colspan="4">
        
        <textarea class="form-control adjRemarks" name="adjRemarks[]" id="adjRemarks[]" style="resize:none;height:85px;" placeholder="Remarks if any"></textarea>
    </td>
    <td>
        <button type="button" class="btn btn-danger remove" id="remove"><span class="fa fa-minus"></span> Remove</button>
    </td>
</tr><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/other_advance/addmore.blade.php ENDPATH**/ ?>