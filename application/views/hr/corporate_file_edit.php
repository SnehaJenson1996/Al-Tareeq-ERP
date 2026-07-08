<style type="text/css">
    .select2Width {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 240px !important;
        min-width: 240px !important;
    }
</style>

<div class="card-body">
    <?php foreach ($records as $row) : ?>
        <form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Hr/update_corporate_file" id="addform" autocomplete="off" enctype="multipart/form-data">
            <div class="form-group row">
                <label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-form-label">Document Name:</label>
                <div class="col-xs-12 col-sm-9 col-md-4 col-lg-4">
                    <input tabindex="1" type="text" name="doc_name" id="doc_name" placeholder="enter document name" class="form-control form-control-sm " value="<?php echo $row->document_name; ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-form-label">Licence/Card No :</label>
                <div class="col-xs-12 col-sm-9 col-md-4 col-lg-4">
                    <input tabindex="2" type="text" name="card_no" id="card_no" placeholder="enter card number" class="form-control form-control-sm " value="<?php echo $row->card_no; ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-form-label">Expiry Date :</label>
                <div class="col-xs-12 col-sm-9 col-md-4 col-lg-4">
                    <div class="input-group date datepicker1">
                        <input type="text" class="form-control form-control-sm datepicker1" id="exp_date" name="exp_date" value="<?php echo date('d-m-Y', strtotime($row->expiry_date) ?? ''); ?>" tabindex=3>
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-form-label">Upload("jpeg","jpg","png","doc","pdf"):</label>
                <div class="col-sm-6">
                    <table class="table table-bordered table-hover" id="tab_logic" tabindex=11>
                        <tbody>

                            <tr id='addr0'>
                                <td>1</td>
                                <td>
                                    <div class="col-sm-6">
                                        <input class="form-select form-control-sm" id="documents" name="documents[]" type="file">
                                    </div>
                                </td>
                                <td>
                                    <a id="add_row" title="Add" class="btn btn-sm bg-blue"><span class="fa fa-plus"></span></a>
                                    <a id='delete_row' title="Delete" class="btn btn-sm bg-blue"><span class="fa fa-trash"></span></a>
                                </td>
                            </tr>
                            <?php if (!empty($file_records)) {
                                $x = 1;
                                $i = 1;

                                foreach ($file_records as $k) { ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>

                                        <td>
                                            <?php
                                                $file = FCPATH.'public/uploaded_documents/'.$k->document_path;

                                                if(file_exists($file))
                                                {
                                                ?>
                                                    <a href="<?=base_url('public/uploaded_documents/'.$k->document_path)?>" target="_blank">
                                                        <?= $k->document_path ?>
                                                    </a>
                                                <?php
                                                }
                                                else
                                                {
                                                    echo "<span style='color:red'>File not found : ".$k->document_path."</span>";
                                                }
                                            ?>
                                        </td>

                                        <td></td>
                                    </tr>
                            <?php }
                            } ?>
                            <tr id='addr1'></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-form-label">Remarks : </label>
                <div class="col-sm-4">
                    <textarea id="remark" name="remark" rows="2" placeholder="remark" style="width: 100%;" tabindex="5"><?php echo $row->remark; ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2"></label>
                <div class="col-sm-10">
                    <input type="hidden" name="id" value="<?php echo $row->cop_id; ?>">

                    <button type="submit" id='add' tabindex="6" class="btn btn-primary m-b-0">submit</button>
                </div>
            </div>
        </form>
</div>
<?php endforeach; ?>

</div>


<script>
    $(document).ready(function() {
        var i = <?php echo count($file_records) + 1; ?>; // Set initial value of i to the count of existing files plus 1

        $("#add_row").click(function() {
            $('#addr' + i).html("<td>" + (i + 1) + "</td><td><div class='col-sm-6'><input class='form-control' id='documents" + i + "' name='documents[]' type='file'></div></td><td></td>");
            $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
            i++;
        });

        $("#delete_row").click(function() {
            if (i > 1) {
                $("#addr" + (i - 1)).html('');
                i--;
            }
        });
    });
</script>