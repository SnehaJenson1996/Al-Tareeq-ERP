<link href="<?php echo base_url()."public/build/css/popup.css"; ?>" rel="stylesheet">
<form id="mr_form" action="<?= base_url('index.php/Project/update_material_request') ?>" method="post">

<input type="hidden" name="mr_id" value="<?= $mr['mr_id'] ?>">

<!-- Select Approved Project mb-3-->
<div class="col-md-6">
    <label for="project_id" class="form-label">Select Approved Project</label>
    <select name="project_id" id="project_id" class="form-control" required>
        <option value="">-- Select Project --</option>
        <?php foreach ($approved_projects as $proj): ?>
            <option value="<?= $proj['project_id'] ?>"
                <?= ($proj['project_id'] == $mr['project_id']) ? 'selected' : '' ?>>
                <?= $proj['project_name'] ?> (<?= $proj['project_code'] ?>)
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Initiated By -->
<div class="col-md-6">
    <label for="initiated_by" class="form-label">Initiated By</label>
    <select name="initiated_by" id="initiated_by" class="form-control" required>
        <option value="">-- Select User --</option>
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['user_id'] ?>"
                <?= ($user['user_id'] == $mr['initiated_by']) ? 'selected' : '' ?>>
                <?= $user['user_name'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="col-md-12 clear topp">
<!-- Auto-filled Project Info -->
<table class="table table-bordered">
<tr>
    <th>Project</th>
    <td id="project_name"><?= $mr['project_name'] ?></td>
</tr>
<tr>
    <th>Customer</th>
    <td id="customer_name"><?= $mr['customer_name'] ?></td>
</tr>
<tr>
    <th>Branch</th>
    <td id="branch_name"><?= $mr['branch_name'] ?></td>
</tr>
<tr>
    <th>Requested Date</th>
    <td>
        <input type="date" name="requested_date" class="form-control"
               value="<?= $mr['requested_date'] ?>" required>
    </td>
</tr>
<tr>
    <th>Required Date</th>
    <td>
        <input type="date" name="required_date" class="form-control"
               value="<?= $mr['required_date'] ?>" required>
    </td>
</tr>
</table>
</div>

<div class="col-md-12 mt-2">
    <label><strong>Items</strong></label>
</div>

<div class="col-md-12 mt-2">

<table class="table table-bordered table-hover" id="tab_logic">

    <thead>
        <tr>
            <th>Item Name</th>
            <th width="120">Quantity</th>
            <th >Description</th>
            <th>Remarks</th>
            <th width="50">
                <a id="add_row" class="btn btn-xs bg-orange" title="Add">
                    <span class="fa fa-plus"></span>
                </a>
            </th>
        </tr>
    </thead>

    <tbody id="mytbbody">

    <?php

    if(!empty($mitems))
    {
        $i=0;
        foreach($mitems as $r)
        {
    ?>

        <tr id="addr<?= $i; ?>">

            <td>

                <input type="hidden"
                       name="m_id[]"
                       value="<?= $r['pjt_material_id'] ?>">

                <select name="product[]" class="form-control">

                    <option value="">-- Select Product --</option>

                    <?php foreach($pitems as $itm){ ?>

                        <option value="<?= $itm['product_id']; ?>"
                        <?= ($itm['product_id']==$r['fk_item_id'])?'selected':''; ?>>

                            <?= htmlspecialchars($itm['product_name']); ?>

                        </option>

                    <?php } ?>

                </select>

            </td>

            <td>

                <input
                    type="number"
                    name="pdt_qty[]"
                    class="form-control"
                    value="<?= $r['item_qty']?>">

            </td>
            <td>

                <textarea
                    name="desc[]"
                    id="desc<?= $i; ?>"
                    rows="4"
                    class="form-control"><?= $r['item_desc']; ?></textarea>

            </td>
            <td>

                <textarea
                    name="item_remark[]"
                    id="item_remark<?= $i; ?>"
                    class="form-control" rows="4"><?= $r['item_remarks'] ?></textarea>

            </td>

            <td>

                <a href="javascript:void(0)"
                   onclick="remove_row(<?= $i; ?>)"
                   class="btn btn-xs bg-orange">

                    <span class="fa fa-trash"></span>

                </a>

            </td>

        </tr>

    <?php
        $i++;
        }
    ?>

        <tr id="addr<?= $i; ?>"></tr>

    <?php
    }
    else
    {
    ?>

        <tr id="addr0">

            <td>

                <select name="product[]" class="form-control">

                    <option value="">-- Select Product --</option>

                    <?php foreach($pitems as $itm){ ?>

                        <option value="<?= $itm['product_id']; ?>">

                            <?= htmlspecialchars($itm['product_name']); ?>

                        </option>

                    <?php } ?>

                </select>

            </td>

            

            <td>

                <input
                    type="number"
                    name="pdt_qty[]"
                    class="form-control">

            </td>
            <td>

                <textarea
                    name="desc[]"
                    id="desc0"
                    rows="4"
                    class="form-control"></textarea>

            </td>

            <td>

                <textarea
                    name="item_remark[]"
                    id="item_remark0"
                    class="form-control" rows="4"></textarea>

            </td>

            <td>

                <a href="javascript:void(0)"
                   onclick="remove_row(0)"
                   class="btn btn-xs bg-orange">

                    <span class="fa fa-trash"></span>

                </a>

            </td>

        </tr>

        <tr id="addr1"></tr>

    <?php } ?>

    </tbody>

</table>

</div>

<!--<h5>Items</h5>
<table class="table table-bordered" id="items_table">
<thead>
<tr>
    <th>#</th>
    <th>Item</th>
    <th>Unit</th>
    <th>Required Quantity</th>
</tr>
</thead>
<tbody>
<?php if(!empty($mr_items)): ?>
    <?php foreach($mr_items as $i => $item): ?>
    <tr>
        <td><?= $i+1 ?></td>
        <td><?= $item['product_name'] ?></td>
        <td>
            <select name="unit_id[]" class="form-control" required>
                <option value="">-- Select Unit --</option>
                <?php foreach($units as $u): ?>
                    <option value="<?= $u['unit_id'] ?>" 
                        <?= ($u['unit_id'] == $item['unit']) ? 'selected' : '' ?>>
                        <?= $u['unit_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="hidden" name="product_id[]" value="<?= $item['product_id'] ?>">
            <input type="number" name="quantity[]" value="<?= $item['quantity'] ?>" class="form-control">
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4">Select a project to load items</td>
    </tr>
<?php endif; ?>
</tbody>
</table>-->


<input type="hidden" name="project_code" id="project_code" value="<?= $mr['project_code'] ?>">
<input type="hidden" name="customer_name" id="hidden_customer_name" value="<?= $mr['customer_name'] ?>">
<input type="hidden" name="branch_name" id="hidden_branch_name" value="<?= $mr['branch_name'] ?>">

<div class="col-md-12 text-end mt-3">
    <button type="submit" class="btn btn-success">Update MR</button>
    <a href="<?= base_url('index.php/Project/list_material_request') ?>" class="btn btn-secondary">Cancel</a>
</div>

</form>
<?php
$productOptions="";
$productOptions .= '<option value="">-- Select Product --</option>';

foreach ($pitems as $itm) {
    $id = $itm['product_id'];
    $name = $itm['product_name'];
    $productOptions .= '<option value="' . $id . '">' .
                        htmlspecialchars($name) .
                       '</option>';
}
?>
<script>
var productOptions = `<?= $productOptions ?>`;
var j=1;
function remove_row(append_id){    	 
    $('#addr'+append_id).attr("id","addr"+append_id+"x");
    $('#addr'+append_id+"x").remove();
}  

</script>
<script>
$(document).ready(function(){
    var i = 1;
    $("#add_row").click(function () {

        var html = "";

        html += "<td>";
        html += "<select name='product[]' class='form-control' tabindex='2'>";
        html += productOptions;
        html += "</select>";
        html += "</td>";

        html += "<td>";
        html += "<input type='number' name='pdt_qty[]' class='form-control form-control-sm' tabindex='14'>";
        html += "</td>";

        html += "<td>";
        html += "<textarea rows='4' cols='20' name='desc[]' id='desc" + i + "' ";
        html += "class='form-control form-control-sm' ";
        html += "style='font-size:11px;font-weight:bold;' ";
        html += "placeholder='Description'></textarea>";
        html += "</td>";

        html += "<td>";
        html += "<textarea name='item_remark[]' rows='4' id='item_remark" + i + "' ";
        html += "class='form-control form-control-sm' ";
        html += "placeholder='Remark'></textarea>";
        html += "</td>";

        html += "<td width='30px'>";
        html += "<a onclick='remove_row(" + i + ");' class='btn btn-xs bg-orange remove1'>";
        html += "<span class='fa fa-trash'></span>";
        html += "</a>";
        html += "</td>";

        $("#addr" + i).html(html);

        $("#mytbbody tr:last").after('<tr id="addr' + (i + 1) + '"></tr>');

        i++;
    });

    

    $('#project_id').change(function(){
        var project_id = $(this).val();
        if(!project_id){
            $('#project_name').text('-');
            $('#customer_name').text('-');
            $('#branch_name').text('-');
            $('#items_table tbody').html('<tr><td colspan="3">Select a project to load items</td></tr>');
            return;
        }

        $.ajax({
            url: '<?= base_url("index.php/Project/get_project_details_ajax") ?>',
            type: 'POST',
            data: { project_id: project_id },
            dataType: 'json',
            success: function(res){
                $('#project_name').text(res.project.project_name);
                $('#customer_name').text(res.project.customer_name);
                $('#branch_name').text(res.project.branch_name);

                $('#project_code').val(res.project.project_code);
                $('#hidden_customer_name').val(res.project.customer_name);
                $('#hidden_branch_name').val(res.project.branch_name);

                var rows = '';
                res.items.forEach(function(item, i){
                    rows += `<tr>
                        <td>${i+1}</td>
                        <td>${item.product_name}</td>
                        <td>
                            <input type="hidden" name="product_id[]" value="${item.product_id}">
                            <input type="number" name="quantity[]" value="${item.quantity}" class="form-control">
                        </td>
                    </tr>`;
                });

                $('#items_table tbody').html(rows);
            }
        });
    });
});
</script>
