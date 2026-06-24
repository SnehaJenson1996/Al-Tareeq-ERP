<style>
    /* --- Clean modern Gentellela-style form --- */
    .x_panel {
        border-radius: 8px;
        background: #fff;
        border: 1px solid #e5e5e5;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 25px;
    }

    .x_title h2 {
        font-size: 16px;
        font-weight: 600;
        color: #007bff;
    }

    label.col-form-label {
        font-weight: 600;
        color: #333;
        font-size: 13px;
    }

    .form-control-sm, .select2-container--default .select2-selection--single {
        font-size: 13px !important;
        border-radius: 6px !important;
        height: 32px !important;
    }

    textarea.form-control, textarea {
        font-size: 13px !important;
        border-radius: 6px;
    }

    .table-bordered th {
        background: #f8f9fa;
        font-size: 13px;
        text-align: center;
        font-weight: 600;
    }

    .table-bordered td {
        font-size: 13px;
        vertical-align: middle !important;
    }

    .btn-sm.bg-blue {
        background-color: #007bff;
        color: #fff;
        border-radius: 4px;
    }

    .btn-sm.bg-blue:hover {
        background-color: #0056b3;
    }

    hr.salary-line {
        border: 1px solid #007bff;
        margin-top: 25px;
        margin-bottom: 25px;
        opacity: 0.3;
    }

    #add {
        border-radius: 6px;
        font-size: 13px;
        padding: 6px 15px;
        background-color: #007bff;
        border: none;
    }

    #add:hover {
        background-color: #0056b3;
    }

    .text-danger {
        color: red !important;
    }

    .select2Width {
        max-width: 240px !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>


<div class="x_panel">
    <div class="x_title">
        <h2>Employee Salary Structure</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Hr/add_salary_structure_data" autocomplete="off" enctype="multipart/form-data">

            <!-- Employee & Date -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Employee Name<span class="text-danger">*</span></label>
                <div class="col-sm-3">
                    <select tabindex="1" class="form-control form-control-sm select2" id="employee_id" name="employee_id" required>
                        <option value="">Select</option>
                       <?php foreach ($records as $s) { ?>
                            <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <label class="col-sm-2 col-form-label">Effective Date :</label>
                <div class="col-sm-3">
                      <div class="input-group date">
                    <input type="date" class="form-control form-control-sm" id="effctive_date" name="effctive_date" value="<?php echo date('Y-m-d'); ?>" tabindex="2">
                    <div class="input-group-addon"></div>
                </div>
                  
                </div>
            </div>

            <!-- Basic Salary -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Basic Salary<span class="text-danger">*</span></label>
                <div class="col-sm-3">
                    <input tabindex="3" type="number" step="0.01" name="bsalary" id="bsalary"
                           placeholder="Enter basic salary"
                           class="form-control form-control-sm"
                           oninput="calculateGrossSalary()" min="0" required>
                </div>
            </div>

            <hr class="salary-line">

            <!-- Allowance Section -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Allowance:</label>
                <div class="col-sm-8">
                    <table class="table table-bordered table-hover" id="allowance">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Allowance Type</th>
                                <th>Amount</th>
                                <th>Action <a class="add_row_allowance btn btn-sm bg-blue" title="Add"><i class="fa fa-plus"></i></a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="allowance_row">
                                <td>1</td>
                                <td>
                                    <select tabindex="4" class="form-control form-control-sm allowance_type" name="allowance_type[]">
                                        <option value="">Select</option>
                                        <?php foreach ($record1 as $a) {
                                            if ($a->allowance_type == 'A') { ?>
                                                <option value="<?php echo $a->sno ?>"><?php echo $a->allowance_name; ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </td>
                                <td><input type="number" step="0.01" name="a_amount[]" class="form-control form-control-sm" oninput="calculate_a()" min="0"></td>
                                <td class="text-center"><a class="delete_row btn btn-sm bg-blue" title="Delete"><i class="fa fa-trash"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-right">
                        <label>Total Allowance:</label>
                        <input type="number" step="0.01" id="t_allowance" name="t_allowance" readonly class="form-control form-control-sm" style="width:150px;display:inline-block;">
                    </div>
                </div>
            </div>

            <hr class="salary-line">

            <!-- Deduction Section -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Deduction:</label>
                <div class="col-sm-8">
                    <table class="table table-bordered table-hover" id="deduction">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Deduction Type</th>
                                <th>Amount</th>
                                <th>Action <a class="add_row_deduction btn btn-sm bg-blue" title="Add"><i class="fa fa-plus"></i></a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="deduction_row">
                                <td>1</td>
                                <td>
                                    <select class="form-control form-control-sm deduction_type" name="deduction_type[]" tabindex="6">
                                        <option value="">Select</option>
                                        <?php foreach ($record1 as $d) {
                                            if ($d->allowance_type == 'D') { ?>
                                                <option value="<?php echo $d->sno ?>"><?php echo $d->allowance_name; ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </td>
                                <td><input type="number" step="0.01" name="d_amount[]" class="form-control form-control-sm" oninput="calculate_d()" min="0"></td>
                                <td class="text-center"><a class="delete_row btn btn-sm bg-blue" title="Delete"><i class="fa fa-trash"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-right">
                        <label>Total Deduction:</label>
                        <input type="number" step="0.01" id="t_deduction" name="t_deduction" readonly class="form-control form-control-sm" style="width:150px;display:inline-block;">
                    </div>
                </div>
            </div>

            <hr class="salary-line">

            <!-- Gross Salary -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Gross Salary :</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control form-control-sm" id="gross_salary" name="gross_salary" readonly>
                </div>
            </div>

            <!-- Remarks -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Remarks :</label>
                <div class="col-sm-6">
                    <textarea id="remark" name="remark" rows="2" class="form-control form-control-sm" placeholder="Enter remarks..."></textarea>
                </div>
            </div>

            <!-- Submit -->
            <div class="form-group row">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" id="add" class="btn btn-primary">
                        <i class="fa fa-save"></i> Submit
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>



<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
    $(document).ready(function() {
        var allowance_i = 1;
        var deduction_i = 1;

        $(".add_row_allowance").click(function() {
            var newRow = "<tr><td>" + (allowance_i + 1) + "</td><td><div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'><select tabindex='4' class='form-control form-control-sm allowance_type' name='allowance_type[]' required><option value=''>Select</option>";
            <?php foreach ($record1 as $a) {
                if ($a->allowance_type == 'A') {
            ?>
                    newRow += "<option value='<?php echo $a->sno ?>'><?php echo $a->allowance_name; ?></option>";
            <?php }
            } ?>
            newRow += "</select></div></td><td><input type='number' step='0.01' id='a_amount_" + allowance_i + "' name='a_amount[]' class='form-control form-control-sm' oninput='calculate_a()'></td>";
            newRow += "<td><a class='delete_row btn btn-sm bg-blue' title='Delete'><span class='fa fa-trash'></span></a></td>";
            newRow += "</tr>";
            $('#allowance tbody').append(newRow);
            allowance_i++;
            // Recalculate Total allowances
            updateTotalAllowance();
            calculateGrossSalary(); // Call the function to recalculate gross salary
        });

        $(".add_row_deduction").click(function() {
            var newRow = "<tr><td>" + (deduction_i + 1) + "</td><td><div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'><select tabindex='6' class='form-control form-control-sm deduction_type' name='deduction_type[]' required><option value=''>Select</option>";
            <?php foreach ($record1 as $d) {
                if ($d->allowance_type == 'D') {
            ?>
                    newRow += "<option value='<?php echo $d->sno ?>'><?php echo $d->allowance_name; ?></option>";
            <?php }
            } ?>
            newRow += "</select></div></td><td><input type='number' step='0.01' id='d_amount_" + deduction_i + "' name='d_amount[]' oninput='calculate_d()' class='form-control form-control-sm'></td>";
            newRow += "<td><a class='delete_row btn btn-sm bg-blue' title='Delete'><span class='fa fa-trash'></span></a></td>";
            newRow += "</tr>";
            $('#deduction tbody').append(newRow);
            deduction_i++;

            // Recalculate total deduction
            updateTotalDeduction();
            calculateGrossSalary(); // Call the function to recalculate gross salary
        });

        $(document).on('click', '.delete_row', function() {
            var table_id = $(this).closest('table').attr('id');
            var rowCount = $('#' + table_id + ' tbody tr').length;
            if (rowCount > 1) {
                $(this).closest('tr').remove();
                if (table_id == 'allowance') {
                    allowance_i--;
                    updateTotalAllowance();
                } else if (table_id == 'deduction') {
                    deduction_i--;
                    updateTotalDeduction();
                }
            } else {
                alert("At least one row is required.");
            }
            calculateGrossSalary(); // Call the function to recalculate gross salary
        });

        function updateTotalAllowance() {
            var totalAllowance = 0;
            $('#allowance tbody tr').each(function() {
                var amount = parseFloat($(this).find('td:eq(2) input').val()) || 0;
                totalAllowance += amount;
            });
            $('#t_allowance').val(totalAllowance.toFixed(2));
            calculateGrossSalary(); // Call the function to recalculate gross salary
        }

        function updateTotalDeduction() {
            var totalDeduction = 0;
            $('#deduction tbody tr').each(function() {
                var amount = parseFloat($(this).find('td:eq(2) input').val()) || 0;
                totalDeduction += amount;
            });
            $('#t_deduction').val(totalDeduction.toFixed(2));
            calculateGrossSalary(); // Call the function to recalculate gross salary
        }

    });

    function calculate_d() {
        var totalDeduction = 0;
        $("input[name='d_amount[]']").each(function() {
            totalDeduction += parseFloat($(this).val()) || 0;
        });
        $('#t_deduction').val(totalDeduction.toFixed(2));
        calculateGrossSalary(); // Call the function to recalculate gross salary
    }

    function calculate_a() {
        var totalAllowance = 0;
        $("input[name='a_amount[]']").each(function() {
            totalAllowance += parseFloat($(this).val()) || 0;
        });
        $('#t_allowance').val(totalAllowance.toFixed(2));
        calculateGrossSalary(); // Call the function to recalculate gross salary
    }

    function calculateGrossSalary() {
        var basicSalary = parseFloat(document.getElementById("bsalary").value);
        var totalAllowance = parseFloat($('#t_allowance').val()) || 0;
        var totalDeduction = parseFloat($('#t_deduction').val()) || 0;

        var grossSalary = basicSalary + totalAllowance - totalDeduction;

        document.getElementById("gross_salary").value = grossSalary.toFixed(2);
    }

$(document).ready(function () {
    $('.select2').select2({
        placeholder: "Select Employee",
        allowClear: true,
        width: '100%'
    });
});
</script>
