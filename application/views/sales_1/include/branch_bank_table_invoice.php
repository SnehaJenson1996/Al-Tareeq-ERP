<h5><strong>Bank Account Details</strong></h5>
<table class="table table-bordered table-hover" id="tab_logic">
    <thead>
        <tr>
            <th>Select</th>
            <th>Bank Name</th>
            <th>Account No</th>
            <th>Branch</th>
            <th>IBAN</th>
            <th>SWIFT</th>
        </tr>
    </thead>
    <tbody id="mytbbody">
        <?php if (!empty($branch_bank)) {
            $i = 0;
            foreach ($branch_bank as $bank) { ?>
                <tr id="addr<?= $i ?>">
                    <td>
                        <input type="radio" name="selected_bank" 
                               value="<?= $bank->bid ?>" 
                               class="form-check-input">
                    </td>
                    <td><input type="text" name="bname[]" class="form-control" value="<?= htmlspecialchars($bank->bank_name) ?>"></td>
                    <td><input type="text" name="bacc[]" class="form-control" value="<?= htmlspecialchars($bank->bank_account) ?>"></td>
                    <td><input type="text" name="bbranch[]" class="form-control" value="<?= htmlspecialchars($bank->bank_branch) ?>"></td>
                    <td><input type="text" name="biban[]" class="form-control" value="<?= htmlspecialchars($bank->bank_iban) ?>"></td>
                    <td><input type="text" name="bswift[]" class="form-control" value="<?= htmlspecialchars($bank->bank_swift) ?>"></td>
                </tr>
        <?php $i++; }
        } else { ?>
            <!-- Default empty row when no bank data -->
            <tr id="addr0">
                <td>
                    <input type="radio" name="selected_bank" value="0" class="form-check-input">
                </td>
                <td><input type="text" name="bname[]" class="form-control"></td>
                <td><input type="text" name="bacc[]" class="form-control"></td>
                <td><input type="text" name="bbranch[]" class="form-control"></td>
                <td><input type="text" name="biban[]" class="form-control"></td>
                <td><input type="text" name="bswift[]" class="form-control"></td>
                <td><a class="btn btn-xs bg-orange" onclick="remove_row(0)"><span class="fa fa-trash"></span></a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
