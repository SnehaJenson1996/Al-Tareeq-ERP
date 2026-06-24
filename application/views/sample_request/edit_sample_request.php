<form action='<?php echo base_url().'index.php/'; ?>SampleRequest/update_sample_request_data' method='post' >
        
            <div class="x_content">
                <ul class="stats-overview">
                    <li>
                        <span class="name"> Request Code </span>
                        <span class="value text-success"> <?php echo $request['request_code']; ?> </span>
                    </li>
                    <li>
                        <span class="name"> Request date </span>
                        <span class="value text-success"> <?php echo date('d-m-Y',strtotime($request['request_date'])); ?>   </span>
                    </li>
                    <li>
                        <span class="name"> Customer </span>
                        <span class="value text-success"> <?php echo $request['customer_name']; ?>  </span>
                    </li>
                    
                </ul>
                
            </div>

            <div class="clearfix"></div>
            <div class='table-responsive-custom'>
                <div class="x_content">
                    <input type='hidden' name='request_id' id='request_id' value='<?php echo $request['request_id']; ?>' />
                    <table class="table-striped table-bordered" style="width:100%;font-size:10px">
                                <thead>
                                    <tr>
                                        <td style='width:5%' style='text-align:center'>Sl No</td>
                                        <td style='width:20%'>Item</td>
                                        <td style='width:30%'>Description</td>
                                        <td style='width:10%;text-align:center'>Requested Quantity</td>
                                        <td style='width:10%;text-align:center'>Issued Quantity</td>
                                        <td style='width:10%;text-align:center'>Returned Quantity</td>
                                        <td style='width:10%;text-align:center'>Unit</td>
                                        <td style='width:5%'></td>
                                        
                                    </tr>
                                </thead>
                                <tbody id='mytbody'>
                                <?php $i=0;
                                foreach($request_details as $detail){ ?>
                                    
                                    <tr id='<?php echo 'addr'.$i; ?>'>
                                        <td class='serial-number' style='text-align:center'><?php echo $i+1; ?><input type='hidden' name='request_detail_id[]' value='<?php echo $detail->request_detail_id; ?>' /></td>
                                        <td class='serial-number' style='text-align:center'>
                                            <select class="form-control item-select2" name="item[]" id='<?php echo 'item'.$i; ?>' onchange='get_item_by_id(<?php echo $i; ?>)' disabled>
                                        <option value=''>Select</option>
                                        <option value='<?php echo $detail->item_id ?>'  selected><?php echo $detail->item_model; ?></option>
                                        </select>
                                        </td>
                                        <td><?php echo $detail->item_description ?></td>
                                        <td><input type='text' class='form-control quantity' name='quantity[]' id='<?php echo 'quantity'.$i; ?>' value='<?php echo $detail->quantity; ?>' /></td>
                                        <td><?php echo $detail->issued_qty; ?></td>
                                        <td><?php echo $detail->returned_qty; ?></td>
                                        <td style='text-align:center' ><?php echo $detail->unit_name; ?></td>
                                       
                                        
                                    </tr>
                                <?php $i++; }?>     
                                               
                                </tbody>
                    </table>     
                    <input type='hidden' name='row_count' id='row_count' value=<?php echo $i; ?> />
                    <input type='hidden' name='deleted_detail_ids' id='deleted_detail_ids' value='' />
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Return Date</label>
                        <div class="col-md-2">
                        <input type='date' class='form-control' name='return_date' id='return_date' value='<?php echo $request['return_date']; ?>'/>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>

                    <div class="item form-group">
                                <label class="col-form-label col-md-1 col-sm-1 label-align" >Comments</label>
                                <div class="col-md-4 col-sm-4 ">
                                    <textarea name='comments' rows=2 cols=25 class='form-control'></textarea>
                                </div>
                               
                    </div>   
                   
                    <div class="item form-group" >  
                        <div class="col-md-4"></div>
                        <div class="col-md-3">
                                    <button type="submit" class="btn btn-success btn-primary">Save Changes</button>
                        </div>
                        <div class="col-md-3">
                                    <?php if($request['request_status']==0){?><button type="button" onclick='approve_request()' class="btn btn-success btn-primary">Approve Request</button><?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            </div>
        </form>

<script>
    const base_url = "<?= base_url(); ?>";
    function approve_request(){
        var request_id = $('#request_id').val();
        $.ajax({
                url: '<?= base_url("index.php/SampleRequest/approve_sample_request") ?>', 
                type: 'POST',
                data: { request_id: request_id },
                dataType:"json",
                success: function(response) {
                   window.location.href = base_url + "index.php/SampleRequest/list_sample_requests" ;
                    
                }
            });
        
    }
</script>