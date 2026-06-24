<?php if (!empty($survey)): ?>
    <table class="table table-borderless mb-3">
        <tr>
            <td colspan="2"><strong>Assigned Person:</strong><?= $survey['employee_name']?></td>
        </tr>
        <tr>
            <td class="w-50">
                <strong>Scheduled Date:</strong> <?= date('d-m-Y', strtotime($survey['scheduled_date'])) ?>
            </td>
            <td class="w-50 text-right">
                <strong>Actual Date:</strong> <?= date('d-m-Y', strtotime($survey['actual_date'])) ?>
            </td>
        </tr>
        <tr>
            <td class="w-50">
                <strong>Scheduled Start Time:</strong> <?= date('H:i', strtotime($survey['start_time'])) ?>
            </td>
            <td class="w-50 text-right">
                <strong>Actual start Time:</strong> <?= date('h:i A', strtotime($survey['actual_start_time'])) ?>
            </td>
        </tr>
        <tr>
            <td class="w-50">
                <strong>Scheduled End Time:</strong> <?= date('h:i A', strtotime($survey['end_time'])) ?>
            </td>
            <td class="w-50 text-right">
                <strong>Actual End Time:</strong> <?= date('h:i A',strtotime($survey['actual_end_time'])) ?>
            </td>
        </tr>
        <tr>
            <td class="w-50">
                <strong>Scheduled Hours:</strong> <?= date('H:i', strtotime($survey['scheduled_hours'])) ?>
            </td>
            <td class="w-50 text-right">
                <strong>Actual Hours:</strong> <?= date('H:i', strtotime($survey['actual_hours'])) ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
               <strong> Comments</strong>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?= $survey['remarks'] ?>
            </td>
        </tr>  
         <tr>
            <td colspan="2">
                <strong>Comments/Remarks</strong>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?= $survey['survey_comments'] ?>
            </td>
        </tr>  
        <tr>
            <td colspan="2">
               <strong> Material details</strong>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?= $survey['material_details'] ?>
            </td>
        </tr>  
    </table>


   <?php if (!empty($survey['file_names'])): ?>
    <p><strong>Attachments:</strong></p>
    <?php foreach ($survey['file_names'] as $file): ?>
        <a href="<?= base_url('public/survey_files/'.$file) ?>" 
           target="_blank" 
           class="btn btn-sm btn-info mb-1">
            View <?= $file ?>
        </a>
    <?php endforeach; ?>
<?php endif; ?>
<?php else: ?>
    <p>No survey details found.</p>
<?php endif; ?>
