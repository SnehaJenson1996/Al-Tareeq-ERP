    <style>
        .form-control{
            padding:0 px;
        }
        .select2{
            font-size:0.8 rem;
        }
        .table-bordered{
            font-size:0.7 rem;
        }
        .table-responsive-custom {
        width: 100%;
        
    }

    .table-responsive-custom table {
      
    }
    .right-align{
        text-align:right;
    }
    body {
        font-family: "Franklin Gothic Book", "Franklin Gothic Medium", Arial, sans-serif;
        font-size:13px;
    }

    </style>
    <div class="clearfix"></div>
        
        <form action='<?php echo base_url().'index.php/'; ?>SampleRequest/add_sample_request' method='post' >
        <div class="x_content">
            <div class="item form-group">
                <label class="col-form-label col-md-2 col-sm-1 label-align" >Enquiry <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 ">
                    <select class="form-control" name="enquiry_id" id="enquiry_id" onchange='this.form.submit()'>
                        <option value="">Select Enquiry</option>
                        <?php foreach($all_enquiries as $enq) { ?>
                            <option value="<?php echo $enq->enquiry_id;?>" <?php if(isset($enquiry) && $enq->enquiry_id == $enquiry['enquiry_id']) echo 'selected'; ?>><?php echo $enq->enquiry_code;?></option>
                        <?php } ?>
                    </select>
                </div>
                
            </div>
        </div>
        </form>
        <div class="clearfix"></div>
        <?php if(isset($enquiry)){ ?>
        <form action='<?php echo base_url().'index.php/'; ?>SampleRequest/add_sample_request_data' method='post' >
            <div class="x_content">
                <input type='hidden' name='enquiry_id' value='<?php echo $enquiry['enquiry_id']; ?>' />
                <ul class="stats-overview">
                    <li>
                        <span class="name"> Request code </span>
                        <span class="value text-success"> <?php echo $return_code; ?>  </span>
                    </li>
                    <li>
                        <span class="name"> Request date </span>
                        <span class="value text-success"> <?php echo date('d-M-Y'); ?>   </span>
                    </li>
                    <li>
                        <span class="name"> Customer </span>
                        <span class="value text-success"> <?php echo $enquiry['customer_name']; ?>  </span>
                    </li>
                    
                </ul>
            </div>

            <div class="clearfix"></div>
            <div class='table-responsive-custom'>
                <div class="x_content">

                    <table class="table-striped table-bordered" style="width:100%;font-size:10px">
                                <thead>
                                    <tr>
                                        <td style='width:5%' style='text-align:center'>Sl No</td>
                                        <td style='width:20%'>Item</td>
                                        <td style='width:40%'>Description</td>
                                        <td style='width:10%;text-align:center'>Quantity</td>
                                        <td style='width:20%;text-align:center'>Unit</td>
                                        <td style='width:5%'></td>
                                    </tr>
                                </thead>
                                <tbody id='mytbody'>
                                    <tr id='addr0'>
                                    <td style='text-align:center'><span class='serial-number'>1</span></td>
                                    <td>
                                        <select class="form-control item-select2" name="item[]" id='item0' onchange='get_item_by_id(0)'>
                                        <option value=''>Select</option>
                                        
                                        </select>
                                    </td>
                                    <td id='description0'></td>
                                    <td><input type='text' class='form-control' name='quantity[]' id='quantity0' /></td>
                                    <td>
                                        <select class="form-control" name="unit[]" id='unit0'>
                                        <option value=''>Select</option>
                                        <?php foreach($active_units as $unit){ ?>
                                            <option value='<?php echo $unit->unit_id ?>'><?php echo $unit->unit_name; ?></option>
                                        <?php } ?>
                                        </select>
                                    </td>
                                    <td style='text-align:center'><a href='javascript:remove_row(0)' title='Remove row' class='btn'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a></td>
                                   
                                </tr>
                                  
                                                
                                </tbody>
                    </table>     
                    
                    <input type='hidden' name='row_count' id='row_count' value=1 />
                    <div class="clearfix"></div>
                    <br>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Return Date</label>
                        <div class="col-md-2">
                        <input type='date' class='form-control' name='return_date' id='return_date' required />
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="x_content">
                        
                        <div class="item form-group">  
                            <div class="col-md-4"> 
                            </div>         
                            <div class="col-md-8">
                            <button type="submit" class="btn btn-success btn-primary">Add Sample Request</button>
                            </div>
                        </div>
                
            </div>

            <div class="clearfix"></div>
            </div>
        </form>
        <?php } ?>
                                     
    

    

<script>
    var unitOptions = `<option value="">Select</option>`;
  <?php foreach ($active_units as $unit): ?>
    unitOptions += `<option value="<?= $unit->unit_id ?>"><?= $unit->unit_name ?></option>`;
  <?php endforeach; ?>
  
     function add_row(){

    var row_count = $('#row_count').val();
    var i = parseInt($('#next_id').val());
    // Build the row HTML
    let rowContent = `
        <tr id='addr${i}'>
            <td style='text-align:center'><span class='serial-number'></span></td>
            <td>
                <select class='form-control item-select2' name='item[]' id='item${i}' onchange='get_item_by_id(${i})'>
                </select>
            </td>
            <td id='description${i}'></td>
            <td><input type='text' class='form-control' name='quantity[]' id='quantity${i}' oninput='calculate_row_values(${i})' /></td>
            <td>
                <select class='form-control' name='unit[]' id='unit${i}'>
                    ${unitOptions}
                </select>
            </td>
            <td><a href='javascript:remove_row(${i})' title='Remove row' class='btn'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a></td>
           
        </tr>
    `;

    $('#mytbody').append(rowContent);
    
    const $newSelect = $('#item' + i);

    setTimeout(() => {
    if ($newSelect.length && !$newSelect.hasClass('select2-hidden-accessible')) {
        initializeSelect2($newSelect);
    }
    }, 0);
     row_count++;
    $('#row_count').val(row_count);
    i++;
    $('#next_id').val(i);
    updateSerialNumbers();
  }

   function updateSerialNumbers() {
    const rows = document.querySelectorAll("#mytbody tr");
    rows.forEach((row, index) => {
		
        if(row.querySelector(".serial-number")){
			row.querySelector(".serial-number").textContent = index + 1;
		}
    });
    
    }

  function get_item_by_id(row_no){
        var item_id = $('#item'+row_no).val();
        
        if(item_id != ''){
            $.ajax({
                url: '<?= base_url("index.php/Item/get_item_by_id") ?>', 
                type: 'POST',
                data: { item_id: item_id },
                dataType:"json",
                success: function(response) {
                   
                    //$('#brand_name'+row_no).text(response.brand_name);
                    $('#description'+row_no).text(response.item_description);
                    $('#unit'+row_no).val(response.item_unit).change();
                    $('#quantity'+row_no).prop('required',true);
                    var nextRow = document.getElementById('addr'+row_no).nextElementSibling;
                    if(!nextRow ) 
                        add_row();
                    
                }
            });
        }
        else{
            $('#brand_name'+row_no).text('');
            $('#description'+row_no).text('');
            $('#unit'+row_no).val('').change();
            $('#unit'+row_no).prop('required',false);
            $('#quantity'+row_no).prop('required',false);
        }
    }  
</script>   
      
            