<div class="x_panel">

    <div class="x_title">
        <h2>Edit Resource Planning</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        <form method="post"
              action="<?= base_url('index.php/Project/update_resource_planning_data'); ?>">

            <input type="hidden"
                   name="resource_id"
                   value="<?= $resource['resource_id']; ?>">

            <!-- Resource Code -->
            <div class="form-group row">
                <label class="col-md-3 control-label">Resource Code</label>

                <div class="col-md-3">
                    <input type="text"
                           class="form-control"
                           name="resource_code"
                           value="<?= $resource['resource_code']; ?>"
                           readonly>
                </div>
            </div>

            <!-- Project -->
            <div class="form-group row">
                <label class="col-md-3 control-label">
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

            <!-- Machine + Operator -->
            <div class="form-group row">
                <label class="col-md-3 control-label">
                    Machine & Operator
                </label>

                <div class="col-md-5">

                    <select name="mapping_id"
                            id="mapping_id"
                            class="form-control"
                            required>

                        <option value="">-- Select Machine & Operator --</option>

                        <?php foreach($machine_operator as $row){ ?>

                        <option value="<?= $row['mapping_id'];?>"

                            data-machine="<?= $row['machine_name'];?>"

                            data-employee="<?= $row['employee_name'];?>"

                            data-skill="<?= $row['skill_level'];?>"

                            <?= ($resource['mapping_id']==$row['mapping_id'])?'selected':'';?>>

                            <?= $row['machine_name'];?>
                            -
                            <?= $row['employee_name'];?>
                            (<?= $row['skill_level'];?>)

                        </option>

                        <?php } ?>

                    </select>

                </div>
            </div>

            <!-- Machine -->
            <div class="form-group row">
                <label class="col-md-3 control-label">Machine</label>

                <div class="col-md-5">
                    <input type="text"
                           id="machine_name"
                           class="form-control"
                           readonly>
                </div>
            </div>

            <!-- Operator -->
            <div class="form-group row">
                <label class="col-md-3 control-label">Operator</label>

                <div class="col-md-5">
                    <input type="text"
                           id="employee_name"
                           class="form-control"
                           readonly>
                </div>
            </div>

            <!-- Skill -->
            <div class="form-group row">
                <label class="col-md-3 control-label">Skill Level</label>

                <div class="col-md-3">
                    <input type="text"
                           id="skill_level"
                           class="form-control"
                           readonly>
                </div>
            </div>

            <!-- Operation -->
            <div class="form-group row">
                <label class="col-md-3 control-label">Operation Name</label>

                <div class="col-md-5">
                    <input type="text"
                           name="operation_name"
                           class="form-control"
                           value="<?= $resource['operation_name']; ?>">
                </div>
            </div>

            <!-- Hours -->
            <div class="form-group row">
                <label class="col-md-3 control-label">Hours Needed</label>

                <div class="col-md-2">
                    <input type="number"
                           name="hours_needed"
                           class="form-control"
                           step="0.5"
                           value="<?= $resource['hours_needed']; ?>">
                </div>
            </div>

            <!-- Start Date -->
            <div class="form-group row">
                <label class="col-md-3 control-label">Start Date</label>

                <div class="col-md-3">
                    <input type="date"
                           name="start_date"
                           id="start_date"
                           class="form-control"
                           value="<?= $resource['start_date']; ?>">
                </div>
            </div>

            <!-- End Date -->
            <div class="form-group row">
                <label class="col-md-3 control-label">End Date</label>

                <div class="col-md-3">
                    <input type="date"
                           name="end_date"
                           id="end_date"
                           class="form-control"
                           value="<?= $resource['end_date']; ?>">
                </div>
            </div>

            <!-- Duration -->
            <div class="form-group row">
                <label class="col-md-3 control-label">Duration (Days)</label>

                <div class="col-md-2">
                    <input type="text"
                           name="duration"
                           id="duration"
                           class="form-control"
                           value="<?= $resource['duration'] ?? ''; ?>"
                           readonly>
                </div>
            </div>

            <!-- Status -->
            <div class="form-group row">
                <label class="col-md-3 control-label">Status</label>

                <div class="col-md-3">

                    <select name="status" class="form-control">

                        <option value="Planned" <?=($resource['status']=='Planned')?'selected':'';?>>Planned</option>

                        <option value="In Progress" <?=($resource['status']=='In Progress')?'selected':'';?>>In Progress</option>

                        <option value="Completed" <?=($resource['status']=='Completed')?'selected':'';?>>Completed</option>

                        <option value="Cancelled" <?=($resource['status']=='Cancelled')?'selected':'';?>>Cancelled</option>

                    </select>

                </div>
            </div>

            <!-- Remarks -->
            <div class="form-group row">

                <label class="col-md-3 control-label">Remarks</label>

                <div class="col-md-6">

                    <textarea name="description"
                              class="form-control"
                              rows="3"><?= $resource['remarks']; ?></textarea>

                </div>

            </div>

            <div class="form-group row">

                <div class="col-md-9 col-md-offset-3">

                    <button class="btn btn-success">
                        <i class="fa fa-save"></i> Update
                    </button>

                    <a href="<?= base_url('index.php/Project/list_resource_planning/'.$project_id);?>"
                       class="btn btn-default">
                        Cancel
                    </a>

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

<script>
function loadMappingDetails() {

    var option = $("#mapping_id option:selected");

    $("#machine_name").val(option.data("machine"));
    $("#employee_name").val(option.data("employee"));
    $("#skill_level").val(option.data("skill"));
}

$(document).ready(function () {

    loadMappingDetails();

    $("#mapping_id").change(function () {
        loadMappingDetails();
    });

});
</script>