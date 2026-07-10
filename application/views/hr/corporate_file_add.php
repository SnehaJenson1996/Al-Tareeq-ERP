<style>
    .select2Width {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 240px !important;
        min-width: 240px !important;
    }
</style>

<div class="x_panel">
    <div class="x_title">
        <h2>Add Corporate File</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Hr/add_corporate_file_data" autocomplete="off" enctype="multipart/form-data">

            <div class="form-group row">
                <label class="col-md-3 col-form-label">Document Name <span class="text-danger">*</span></label>
                <div class="col-md-4">
                    <input type="text" name="doc_name" id="doc_name" class="form-control form-control-sm" placeholder="Enter document name" tabindex="1" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">Licence/Card No <span class="text-danger">*</span></label>
                <div class="col-md-4">
                    <input type="text" name="card_no" id="card_no" class="form-control form-control-sm" placeholder="Enter card number" tabindex="2" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">Expiry Date <span class="text-danger">*</span></label>
                <div class="col-md-4">
                    <div class="input-group date datepicker1">
                        <input type="text" class="form-control form-control-sm datepicker1" id="exp_date" name="exp_date" value="<?php echo date('d-m-Y') ?>" tabindex="3" required>
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">Upload Files <br><small>(jpeg, jpg, png, doc, pdf)</small></label>
                <div class="col-md-6">
                    <table class="table table-bordered table-hover" id="tab_logic">
                        <tbody>
                            <tr id="addr0">
                                <td style="width: 40px;">1</td>
                                <td>
                                    <input type="file" name="documents[]" id="documents" class="form-control form-control-sm">
                                </td>
                                <td style="width: 100px;">
                                    <a id="add_row" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></a>
                                    <a id="delete_row" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <tr id="addr1"></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">Remarks</label>
                <div class="col-md-4">
                    <textarea id="remark" name="remark" rows="2" class="form-control form-control-sm" placeholder="Enter remark" tabindex="5"></textarea>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-md-4">
                    <button type="submit" id="add" tabindex="6" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        var i = 1;
        $("#add_row").click(function() {
            $('#addr' + i).html(
                "<td>" + (i + 1) + "</td>" +
                "<td><input type='file' name='documents[]' class='form-control form-control-sm'></td>" +
                "<td><a class='btn btn-sm btn-danger remove_row'><i class='fa fa-trash'></i></a></td>"
            );
            $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
            i++;
        });

        $(document).on('click', '.remove_row', function() {
            $(this).closest('tr').remove();
        });

        $("#delete_row").click(function() {
            if (i > 1) {
                $("#addr" + (i - 1)).remove();
                i--;
            }
        });
    });
</script>



<!-- <script>
    $(document).ready(function() {
        var i = 1;
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

    $("#tab_logic").on('click', '.remove', function() {
        $(this).closest('tr').remove();
    });
</script> -->