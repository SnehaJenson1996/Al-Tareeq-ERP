<form action='<?php echo base_url().'index.php/'; ?>Stock/update_stock_allocation_data' method='post' >
        
            <div class="x_content">
                <ul class="stats-overview">
                    <li>
                        <span class="name"> Allocation Code </span>
                        <span class="value text-success"> <?php echo $stock_allocation['allocation_code']; ?> </span>
                    </li>
                    <li>
                        <span class="name"> Allocation date </span>
                        <span class="value text-success"> <?php echo date('d-m-Y',strtotime($stock_allocation['allocation_date'])); ?>   </span>
                    </li>
                    <li>
                        <span class="name"> Sales Order </span>
                        <span class="value text-success"> <?php echo $stock_allocation['pi_code']; ?>  </span>
                    </li>
                </ul>
                <input type='hidden' name='allocation_id' value='<?php echo $stock_allocation['allocation_id']; ?>' />
            </div>

            <div class="clearfix"></div>
            
            <div class="x_content">
                    <table class="table-striped table-bordered" style="width:100%;font-size:10px">
                                <thead>
                                    <tr style='height:50px'>
                                        <td style='width:5%'>Sl No</td>
                                        <td style='width:15%'>Brand</td>
                                        <td style='width:15%'>Model</td>
                                        <td style='width:30%'>Description</td>
                                        <td style='width:10%'>Qty</td>
                                        <td style='width:10%'></td>
                                        <td style='width:10%'></td>
                                    </tr>
                                </thead>
                                <tbody id='mytbody'>
                                <?php $i=0;
                                foreach($allocation_details as $detail){ ?>
                                    
                                    <tr id='<?php echo 'addr'.$i; ?>'>
                                        <td class='serial-number' style='text-align:center'><?php echo $i+1; ?><input type='hidden' name='allocation_detail_id[]' id='<?php echo 'allocation_detail_id'.$i; ?>' value='<?php echo $detail->allocation_detail_id ; ?>' /><input type='hidden' name='item_id[]' id='<?php echo 'item_id'.$i; ?>' value='<?php echo $detail->item_id; ?>' /></td>
                                        <td class='serial-number' style='text-align:center'><?php echo $detail->brand_name; ?> </td>
                                        <td class='serial-number' style='text-align:center'><?php echo $detail->item_model; ?> </td>
                                        <td class='serial-number' style='text-align:center'><?php echo $detail->item_description; ?> </td>
                                        <td><input type='number' max='<?php echo $detail->pi_quantity; ?>' class='form-control quantity' name='quantity[]' id='<?php echo 'quantity'.$i; ?>' value='<?php echo $detail->allocated_quantity; ?>'  /></td>
                                        <td><a href='javascript:delete_row(<?php echo $i; ?>)' title='delete row' class='btn'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
                                        <td><a href='javascript:show_serial_numbers(<?php echo $i; ?>)' class="btn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></td>
                                    </tr>
                                <?php $i++; }?>     
                                               
                                </tbody>
                    </table>   

                    <input type='hidden' name='row_count' id='row_count' value=<?php echo $i; ?> />
                    <input type='hidden' name='deleted_detail_ids' id='deleted_detail_ids' value='' />
                    <div class="clearfix"></div>
                    

                  <div class="modal fade" id="target_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Allocated Units</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>

      <form id="my_modal" method="post">
        <div class="modal-body">
          <input type="hidden" name="allocation_detail_id" id="allocation_detail_id" />
          <table class="table table-striped table-bordered w-100" id="modal_table">
            <thead>
              <tr>
                <th width="20%">Sl no</th>
                <th width="60%">Serial No</th>
                <th width="20%">Action</th>
              </tr>
            </thead>
            <tbody style="text-align:center"></tbody>
          </table>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="update_allocations(this)">Save changes</button>
        </div>
      </form>

    </div>
  </div>
</div>
            </div>
            
            <div class="x_content">
                <div class="clearfix"></div>
                        <div class="item form-group">  
                            <div class="col-md-6"> 
                            
                            </div>         
                            <div class="col-md-6">
                            <button type="submit" class="btn btn-success btn-primary">Save Changes</button>
                            </div>
                        </div>
            </div>

            <div class="clearfix"></div>
            </div>
        </form>

<script>
     const base_url = "<?= base_url(); ?>";
    function delete_row(row){
        var dlt = confirm("Are you sure to delete this record?");
		if(dlt){
			var pi_detail_id = $('#pi_detail_id_'+row).val();
			var deleted = $('#deleted_detail_ids').val();
			if(deleted == ''){
				deleted = pi_detail_id;
			}
			else{
				deleted = deleted +','+pi_detail_id;
			}
			$('#deleted_detail_ids').val(deleted);
			
            var row_count = $('#row_count').val();
            row_count--;
            $('#row_count').val(row_count);
			$('#addr'+row).remove();
            calculate_total_quantity();
            updateSerialNumbers();
		}
        
    }

    function updateSerialNumbers() {
        const rows = document.querySelectorAll("#mytbody tr");
        rows.forEach((row, index) => {
            
            if(row.querySelector(".serial-number")){
                row.querySelector(".serial-number").textContent = index + 1;
            }
        });
    }

    function show_serial_numbers(row){
        
        var allocation_detail_id = $('#allocation_detail_id'+row).val();
        $('#allocation_detail_id').val(allocation_detail_id);
         $.ajax({
                url: base_url +"index.php/Stock/get_allocated_stock_details_by_id", 
                type: 'POST',
                data: { allocation_detail_id: allocation_detail_id },
                dataType:"json",
                success: function(response) {
                    const modal_table = document.querySelector('#modal_table tbody') ;
                    modal_table.innerHTML = '';
                    let i =1;
                    response.forEach(serial_no => {
                      const readonly = serial_no.project > 0 ? 'readonly' : '';
                      const rowHTML = 
                                `<tr>
                                <td>${i}</td>
                                <td><input type="text" name='scanned_serial[]' id='scanned_serial${i}' class='form-control scannedSerial' placeholder="Scan barcode" value='${serial_no.serial_number}' ${readonly}   /></td>
                                <td></td>
                                </tr>`;
                                modal_table.insertAdjacentHTML('beforeend', rowHTML); // Append each row
                      i++;
				            });
                    
                }
            });
        const myModal = new bootstrap.Modal(document.getElementById('target_modal'));
        myModal.show();
    }


  function update_allocations(btn){
        const myForm = btn.closest('form');         
        if (!myForm) {
            alert('Form not found!');
            return;
        }

        const formData = new FormData(myForm);
        $.ajax({
          url: '<?= base_url('index.php/Stock/update_allocation_details_data'); ?>',
          method: 'POST',
          data: formData,     
          contentType: false,    
          processData: false,     
          dataType: 'json'
        })
        .done(function (resp) {
           $('#target_modal').modal('hide');
          location.reload();
        })
        .fail(function (xhr) {
           let msg = 'Server error';
           alert('Error: ' + msg);
        });
    }
          
    
</script>