<div class="clearfix"></div>

<div class="row">
<div class="col-md-12 col-sm-12">
<div class="x_panel">
<div class="x_content">
<br />

<form action="<?= base_url('index.php/Setup/'.(isset($agent) ? 'update_agent/'.$agent['agent_id'] : 'add_agent_data')); ?>"
      method="post" autocomplete="off" id="agent_form">

<div class="row">

    <!-- Agent Code -->
    <div class="col-md-6">
        <label>Agent Code <span class="required">*</span></label>
        <input type="text"
               name="agent_code"
               class="form-control"
               readonly
               value="<?= isset($agent) ? $agent['agent_code'] : $agent_code; ?>">
    </div>

    <!-- Agent Name -->
    <div class="col-md-6">
        <label>Agent Name <span class="required">*</span></label>
        <input type="text"
               name="agent_name"
               class="form-control"
               required
               value="<?= isset($agent) ? htmlspecialchars($agent['agent_name']) : '' ?>">
    </div>

    <!-- Phone -->
    <div class="col-md-6 mt-2">
        <label>Phone</label>
        <input type="text"
               name="phone"
               class="form-control"
               value="<?= isset($agent) ? htmlspecialchars($agent['phone']) : '' ?>">
    </div>

    <!-- Email -->
    <div class="col-md-6 mt-2">
        <label>Email</label>
        <input type="email"
               name="email"
               class="form-control"
               value="<?= isset($agent) ? htmlspecialchars($agent['email']) : '' ?>">
    </div>

    <!-- Address -->
    <div class="col-md-12 mt-2">
        <label>Address</label>
        <textarea name="address" rows="3" class="form-control"><?= isset($agent) ? htmlspecialchars($agent['address']) : '' ?></textarea>
    </div>



</div>

<div class="row mt-3">
    <div class="col-md-12 text-center">
        <button type="submit" id="saveBtn" class="btn btn-success">
            <?= isset($agent) ? 'Update' : 'Save' ?>
        </button>
    </div>
</div>

</form>

</div>
</div>
</div>
</div>

<script>
document.getElementById("agent_form").addEventListener("submit", function(e){

    var btn = document.getElementById("saveBtn");

    if(btn.disabled){
        e.preventDefault();
        return false;
    }

    btn.disabled = true;
    btn.innerHTML = "Processing...";
});
</script>