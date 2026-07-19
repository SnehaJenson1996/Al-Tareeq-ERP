<div class="x_panel">

    <div class="x_title">
        <h2>Add Resource Planning</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        <form method="post" action="<?= base_url('index.php/Project/add_resource_planning_data'); ?>">

            <!-- Resource Code -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Resource Code
                </label>

                <div class="col-md-3">
                    <input type="text"
                           name="resource_code"
                           class="form-control"
                           value="<?= $auto_code; ?>"
                           readonly>
                </div>
            </div>

            <!-- Project -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Project <span class="text-danger">*</span>
                </label>

                <div class="col-md-5">
                    <select name="project_id" class="form-control" readonly>
                        <option value="">-- Select Project --</option>
                            <option value="<?= $projects['project_id']; ?>" selected>
                                <?= $projects['project_code'].' - '.$projects['project_name']; ?>
                            </option>
                   </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Machine & Operator <span class="text-danger">*</span>
                </label>

                <div class="col-md-5">
                    <select name="machine_id" class="form-control" required>
                       <option value="">-- Select Machine & Operator --</option>
                        <?php foreach($machine_operator as $row){ ?>
                            <option value="<?= $row['mapping_id']; ?>">
                                <?= $row['machine_name']; ?> -
                                <?= $row['employee_name']; ?>
                                (<?= $row['skill_level']; ?>)
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <!-- Machine -->
            <!--<div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Machine <span class="text-danger">*</span>
                </label>

                <div class="col-md-5">
                    <select name="machine_id" class="form-control" required>

                        <option value="">-- Select Machine --</option>

                        <?php foreach($machines as $machine){ ?>

                        <option value="<?= $machine['machine_id']; ?>">
                            <?= $machine['machine_name']; ?>
                        </option>

                        <?php } ?>

                    </select>
                </div>
            </div>-->

            <!-- Operator -->
            <!--<div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Operator <span class="text-danger">*</span>
                </label>

                <div class="col-md-5">
                    <select name="employee_id" class="form-control" required>

                        <option value="">-- Select Operator --</option>

                        <?php foreach($employees as $emp){ ?>

                        <option value="<?= $emp['employee_id']; ?>">
                            <?= $emp['employee_name']; ?>
                        </option>

                        <?php } ?>

                    </select>
                </div>
            </div>-->

            <!-- Operation -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Operation Name
                </label>

                <div class="col-md-5">
                    <input type="text"
                           name="operation_name"
                           class="form-control"
                           placeholder="Eg: Cutting, Edge Banding" required>
                </div>
            </div>

            <!-- Hours -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Hours Needed
                </label>

                <div class="col-md-2">
                    <input type="number"
                           name="hours_needed"
                           step="0.5"
                           min="0"
                           class="form-control" required>
                </div>
            </div>

            <!-- Start Date -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Start Date
                </label>

                <div class="col-md-3">
                    <input type="date"
                           name="start_date"
                           id="start_date"
                           class="form-control" required>
                </div>
            </div>

            <!-- End Date -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    End Date
                </label>

                <div class="col-md-3">
                    <input type="date"
                           name="end_date"
                           id="end_date"
                           class="form-control" required>
                </div>
            </div>

            <!-- Duration -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Duration (Days)
                </label>

                <div class="col-md-2">
                    <input type="text"
                           name="duration"
                           id="duration"
                           class="form-control"
                           readonly>
                </div>
            </div>

            <!-- Status -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Status
                </label>

                <div class="col-md-3">
                    <select name="status" class="form-control">

                        <option value="Planned">Planned</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>

                    </select>
                </div>
            </div>

            <!-- Remarks -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Remarks
                </label>

                <div class="col-md-6">
                    <textarea name="description"
                              rows="4"
                              class="form-control"
                              placeholder="Enter Remarks"></textarea>
                </div>
            </div>

            <!-- Active -->
            <input type="hidden" name="bit_active" value="1">

            <!-- Buttons -->
            <div class="form-group row">
                <div class="col-md-8 offset-md-3">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="<?= base_url('index.php/Project/list_resource_planning/'.$project_id) ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </div>

        </form>

    </div>

</div>

<script>
function calculateDuration() {
    var start = document.getElementById('start_date').value;
    var end = document.getElementById('end_date').value;
    var duration = document.getElementById('duration');

    if (start && end) {
        var startDate = new Date(start);
        var endDate = new Date(end);
        var diffTime = endDate - startDate;
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        duration.value = diffDays >= 0 ? diffDays : 0;
    } else {
        duration.value = '';
    }
}

document.getElementById('start_date').addEventListener('change', calculateDuration);
document.getElementById('end_date').addEventListener('change', calculateDuration);
calculateDuration();
</script>