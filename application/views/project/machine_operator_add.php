<div class="x_panel">
    

    <div class="x_content">



<form method="post" action="<?php echo base_url().'index.php/Project/add_machineop_map_data'; ?>">

            <div class="form-group row">

             <label class="col-md-3 col-form-label">
                Machine operator Mapping Code
            </label>

            <div class="col-md-3">
                <input type="text" name="map_code" class="form-control" 
                    value="<?= isset($auto_code) ? $auto_code : '' ?>" readonly>
            </div></div>
             <div class="form-group row">
                <label class="col-md-3 col-form-label">
                    Machine <span class="text-danger">*</span>
                </label>
                <div class="col-md-3">
                    <select name="machine_id" class="form-control" required>
                        <option value="">-- Select machine --</option>
                        <?php $machine_op['machine_id'] =  ''; ?>
                        <?php foreach($machines as $mac): ?>
                            <option value="<?= $mac['machine_id'] ?>"
                                <?= !empty($machine_op) && $machine_op['machine_id'] == $mac['machine_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($mac['machine_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
  <div class="form-group row" style="clear:both;">
                <label class="col-md-3  col-form-label">
                    Employee <span class="text-danger">*</span>
                </label>
                <div class="col-md-3">
                    <select name="employee_id" class="form-control" required>
                        <option value="">-- Select Employee --</option>
                        <?php $machine_op['employee_id'] =  ''; ?>
                        <?php foreach($employees as $emp): ?>
                            <option value="<?= $emp['employee_id'] ?>"
                                <?= !empty($machine_op) && $machine_op['employee_id'] == $emp['employee_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($emp['employee_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                 </div>

                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">
                        Skill Level <span class="text-danger">*</span>
                    </label>

                    <div class="col-md-3">
                        <select name="skill_level" class="form-control" required>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate" selected>Intermediate</option>
                            <option value="Expert">Expert</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">
                        Active <span class="text-danger">*</span>
                    </label>

                    <div class="col-md-3">
                        <select name="bit_active" class="form-control" required>
                            <option value="1">Active</option>
                            <option value="0">In active</option>
                        </select>
                    </div>
                </div>
                 <div class="form-group row">

                <label class="col-md-3 col-form-label">
                    Remarks
                </label>

                <div class="col-md-6">
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

            </div>
                
             </div>
            </div>

           

            <div class="form-group row">
                <div class="col-md-8 offset-md-2">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>

        </form>

    </div>
</div>