<?php
$lastMilestone = $last_log['milestone'] ?? '';
$lastStatus    = $last_log['current_status'] ?? 'Not Started';
$lastProgress  = $last_log['progress_percentage'] ?? 0;
?>

<h4>Project Progress – <?= htmlspecialchars($project['project_name']) ?></h4>

<div class="mb-3">
    <strong>Start Date:</strong>
    <?= date('d-m-Y', strtotime($project['start_date'])) ?> |
    <strong>End Date:</strong>
    <?= date('d-m-Y', strtotime($project['end_date'])) ?>
</div>

<form method="post"
      action="<?= base_url('index.php/Project/save_project_progress') ?>"
      enctype="multipart/form-data"
      id="progressForm">

<input type="hidden" name="project_id" value="<?= $project['project_id'] ?>">

<div class="row">
    <div class="col-md-3">
        <label>Date <span class="text-danger">*</span></label>
        <input type="date"
               name="log_date"
               class="form-control"
               value="<?= date('Y-m-d') ?>"
               required>
    </div>

    <div class="col-md-3">
        <label>Start Time <span class="text-danger">*</span></label>
        <input type="time"
               name="start_time"
               class="form-control"
               required>
    </div>

    <div class="col-md-3">
        <label>End Time <span class="text-danger">*</span></label>
        <input type="time"
               name="end_time"
               class="form-control"
               required>
    </div>

    <div class="col-md-3">
        <label>Milestone <span class="text-danger">*</span></label>
        <input type="text"
               name="milestone"
               class="form-control"
               value="<?= htmlspecialchars($lastMilestone) ?>"
               required>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <label>Status <span class="text-danger">*</span></label>
        <select name="current_status" class="form-control" required>
            <option value="Not Started" <?= $lastStatus=='Not Started'?'selected':'' ?>>Not Started</option>
            <option value="In Progress" <?= $lastStatus=='In Progress'?'selected':'' ?>>In Progress</option>
            <option value="Completed" <?= $lastStatus=='Completed'?'selected':'' ?>>Completed</option>
        </select>
    </div>

    <div class="col-md-6">
        <label>
            Progress <span class="text-danger">*</span> :
            <span id="progressValue"><?= $lastProgress ?>%</span>
        </label>

        <input type="range"
               name="progress_percentage"
               id="progressRange"
               class="form-range"
               min="0"
               max="100"
               value="<?= $lastProgress ?>"
               required
               oninput="updateProgress(this.value)">

        <div class="progress mt-2" style="height:25px;">
            <div id="progressBar"
                 class="progress-bar progress-bar-striped progress-bar-animated"
                 style="width:<?= $lastProgress ?>%">
                <?= $lastProgress ?>%
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <label>Site Images / Files</label>
    <input type="file"
           id="siteFilesInput"
           multiple
           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">
    <div id="selectedFiles" class="mt-2"></div>
</div>

<div class="mt-3">
    <label>Remarks</label>
    <textarea name="remarks" class="form-control"></textarea>
</div>

<div class="mt-3 text-end">
    <button type="submit" class="btn btn-success">
        Save Progress
    </button>
</div>
</form>

<hr>

<h5>Progress History</h5>

<table class="table table-bordered table-sm">
<thead>
<tr>
    <th>Date</th>
    <th>Start</th>
    <th>End</th>
    <th>Milestone</th>
    <th>Status</th>
    <th>Progress</th>
    <th>Files</th>
    <th>Remarks</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach ($logs as $log): ?>
<tr>
    <td><?= date('d-m-Y', strtotime($log['log_date'])) ?></td>
    <td><?= $log['start_time'] ?></td>
    <td><?= $log['end_time'] ?></td>
    <td><?= htmlspecialchars($log['milestone']) ?></td>
    <td>
        <span class="badge
            <?= $log['current_status']=='Completed'?'bg-success':
                ($log['current_status']=='In Progress'?'bg-warning':'bg-secondary') ?>">
            <?= $log['current_status'] ?>
        </span>
    </td>
    <td><?= $log['progress_percentage'] ?>%</td>
    <td>
        <?php if (!empty($log['site_files'])):
            foreach (json_decode($log['site_files'], true) as $file):
                $url = base_url('public/stamp/'.$file);
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        ?>
            <?php if (in_array($ext, ['jpg','jpeg','png'])): ?>
                <a href="<?= $url ?>" target="_blank">
                    <img src="<?= $url ?>" style="width:60px;height:60px;">
                </a>
            <?php else: ?>
                <a href="<?= $url ?>" target="_blank"><?= htmlspecialchars($file) ?></a><br>
            <?php endif; ?>
        <?php endforeach; endif; ?>
    </td>
    <td><?= htmlspecialchars($log['remarks']) ?></td>

<td>
    <button type="button"
            class="btn btn-danger btn-sm delete-log"
            data-id="<?= $log['log_id'] ?>">
        Delete
    </button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<script>
let allFiles = [];

function updateProgress(val){
    document.getElementById('progressValue').innerText = val + '%';
    document.getElementById('progressBar').style.width = val + '%';
    document.getElementById('progressBar').innerText = val + '%';
}

document.getElementById('siteFilesInput').addEventListener('change', function () {

    let newFiles = Array.from(this.files);

    newFiles.forEach(newFile => {

        let isDuplicate = allFiles.some(existingFile =>
            existingFile.name === newFile.name &&
            existingFile.size === newFile.size &&
            existingFile.lastModified === newFile.lastModified
        );

        if (isDuplicate) {
            alert('File already added: ' + newFile.name);
        } else {
            allFiles.push(newFile);
        }

    });

    renderFiles();

    // VERY IMPORTANT
    this.value = ''; // reset input
});

function renderFiles(){
    const list = document.getElementById('selectedFiles');
    list.innerHTML = '';
    allFiles.forEach((file, i) => {
        const d = document.createElement('div');
        d.innerHTML = `${file.name}
            <button type="button" onclick="removeFile(${i})">Remove</button>`;
        list.appendChild(d);
    });
}

function removeFile(i){
    allFiles.splice(i,1);
    renderFiles();
}

document.getElementById('progressForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const status   = document.querySelector('select[name="current_status"]').value;
    const progress = parseFloat(document.getElementById('progressRange').value) || 0;

    // ✅ NEW VALIDATION
    if (status === 'Completed' && progress < 100) {
        alert('A project cannot be marked as Completed until progress reaches 100%.');
        return;
    }

    // Existing validation
    if (this.start_time.value >= this.end_time.value) {
        alert('End time must be greater than start time');
        return;
    }

    const fd = new FormData(this);
    allFiles.forEach(f => fd.append('site_files[]', f));

    fetch(this.action, { method: 'POST', body: fd })
        .then(() => location.reload())
        .catch(err => console.error(err));
});

document.addEventListener('DOMContentLoaded', () => {
    updateProgress(document.getElementById('progressRange').value);
});
document.querySelector('select[name="current_status"]').addEventListener('change', function(){
    const progress = parseFloat(document.getElementById('progressRange').value) || 0;

    if(this.value === 'Completed' && progress < 100){
        alert('Progress must be 100% before marking as Completed.');
        this.value = 'In Progress';
    }
});

document.addEventListener('click', function(e){
    if(e.target.classList.contains('delete-log')){

        const logId = e.target.getAttribute('data-id');

        if(!confirm('Are you sure you want to delete this entry?')){
            return;
        }

        fetch('<?= base_url("index.php/Project/delete_progress_log") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'log_id=' + logId
        })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success'){
                alert('Deleted successfully');
                location.reload();
            } else {
                alert(res.message || 'Delete failed');
            }
        })
        .catch(err => console.error(err));
    }
});
</script>
