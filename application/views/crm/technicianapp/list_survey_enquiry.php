<style>
    label,
    h4 {
        color: black;
        font-weight: bold;
    }
</style>

<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_content">

                <!-- Enquiry Selection -->
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Enquiry <span class="text-danger">*</span></label>
                        <?= form_error('enquirySelect', '<small class="text-danger">', '</small>') ?>
                        <select name="enquirySelect" class="form-control" id="enquirySelect">
                            <option value="">Select</option>
                            <?php foreach ($enquiry_list as $enquiry): ?>
                                <option value="<?= $enquiry->enquiry_id ?>">
                                    <?= $enquiry->enquiry_code ?> => <?= $enquiry->project_name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Enquiry & Survey Details -->
                <div id="detailsBlock" style="display: none;margin-top:20px;">
                    <hr>
                    <h4>Enquiry Details</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Project Name: <strong><span id="project_name"></span></strong></p>
                        </div>
                        <div class="col-md-6">
                            <p>Project Subject:<strong><span id="project_code">--</span></strong> </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p>Project Location: <strong><span id="project_location"></span></strong></p>
                        </div>
                        <div class="col-md-6">
                            <p>Enquiry Category: <strong><span id="enquiry_category">--</span></strong></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p>Enquiry Code: <strong><span id="enquiry_code">--</span></strong></p>
                        </div>
                        <div class="col-md-6">
                            <p>Enquiry Date: <strong><span id="enquiry_date">--</span></strong></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p>Enquiry Source: <strong><span id="enquiry_source">--</span></strong></p>
                        </div>
                        <div class="col-md-6">
                            <p>Client Ref No: <strong><span id="client_ref">--</span></strong></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <p>Remarks/Comments: <strong><span id="remarks">--</span></strong></p>
                        </div>
                    </div>

                    <hr>
                    <h4>Survey Details</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <p>Scheduled Date: <strong><span id="scheduled_date">--</span></strong></p>
                        </div>
                        <div class="col-md-3">
                            <p>Start Date & Time: <strong><span id="start_time">--</span></strong></p>
                        </div>
                        <div class="col-md-3">
                            <p>End Date & Time: <strong><span id="end_time">--</span></strong></p>
                        </div>
                        <div class="col-md-2">
                            <p>Total Hours: <strong><span id="total_hours">--</span></strong></p>
                        </div>
                    </div>

                    <!-- Actual Data -->
                    <hr>
                    <h4>Enter Actual Survey Data</h4>
                    <form method="post" action="<?= base_url() ?>index.php/CRM/save_survey_details" enctype="multipart/form-data">
                        <input type="hidden" name="enquiry_id" id="enquiry_id" value="">

                        <div class="row">
                            <div class="col-md-3">
                                <label>Actual Date</label>
                                <input type="date" name="actual_date" id="actual_date" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label>Actual Start</label>
                                <div class="input-group">
                                    <button type="button" id="btnStart" class="btn btn-success btn-sm">Start Survey</button>
                                </div>
                                <input type="hidden" name="actual_start" id="actual_start">
                            </div>

                            <div class="col-md-3">
                                <label>Actual End</label>
                                <div class="input-group">
                                    <button type="button" id="btnEnd" class="btn btn-danger btn-sm">End Survey</button>
                                </div>
                                <input type="hidden" name="actual_end" id="actual_end">
                            </div>

                            <div class="col-md-3">
                                <label>Actual Hours</label>
                                <input type="number" name="actual_hours" id="actual_hours" class="form-control" step="0.01" readonly>
                            </div>
                        </div>


                        <!-- Attachments -->
                        <hr>
                        <h4>Attachments</h4>
                       <div class="row mb-2" id="attachments-wrapper">
    <small class="text-muted">Supported formats: PDF, PNG, JPG, JPEG, GIF, WEBP. Max size: 5MB.</small>
</div>
                            <!-- <div class="col-md-4">
                                <label>Attachment 1</label>
                                <input type="file" name="attachment1" class="form-control">
                            </div> -->
                            <!-- <div class="col-md-4">
                                <label>Attachment 2</label>
                                <input type="file" name="attachment2" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Attachment 3</label>
                                <input type="file" name="attachment3" class="form-control">
                            </div> -->

                        <!-- </div> -->
                        <button type="button" id="add-attachment" class="btn btn-sm btn-primary mt-2">+ Add Attachment</button>
                        <!-- Products -->
                        <hr>
                        <h4>Products</h4>

                        <div id="mainMenuContainer">
                            <div class="row">
                                <!-- Comments / Remarks -->
                                <div class="col-md-12 col-sm-12 form-group">
                                    <label for="survey_comments">Comments / Remarks</label>
                                    <textarea name="survey_comments" id="survey_comments" class="form-control" rows="4"
                                        placeholder="Enter comments or remarks here"><?= isset($survey_data['survey_comments']) ? $survey_data['survey_comments'] : '' ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Material Details -->
                                <div class="col-md-12 col-sm-12 form-group">
                                    <label for="material_details">Material Details</label>
                                    <textarea name="material_details" id="material_details" class="form-control" rows="4"
                                        placeholder="Enter material details here"><?= isset($survey_data['material_details']) ? $survey_data['material_details'] : '' ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" id="add" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div><!-- /detailsBlock -->

            </div><!-- /x_content -->
        </div><!-- /x_panel -->
    </div>
</div>

<script>
    // Calculate Actual Hours (existing code)
    // get current datetime in ISO for hidden fields
    // Format datetime as "YYYY-MM-DD HH:mm:ss"
    function getCurrentDateTimeForDB() {
        let now = new Date();
        let yyyy = now.getFullYear();
        let mm = String(now.getMonth() + 1).padStart(2, '0');
        let dd = String(now.getDate()).padStart(2, '0');
        let hh = String(now.getHours()).padStart(2, '0');
        let min = String(now.getMinutes()).padStart(2, '0');
        let ss = String(now.getSeconds()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd} ${hh}:${min}:${ss}`;
    }

    // Format readable time like "2:29 PM"
    function getReadableTime() {
        return new Date().toLocaleTimeString([], {
            hour: 'numeric',
            minute: '2-digit'
        });
    }

    // Start button
    document.getElementById("btnStart").addEventListener("click", function() {
        let startVal = getCurrentDateTimeForDB();
        document.getElementById("actual_start").value = startVal;

        // also auto-fill actual_date if empty
        if (!document.getElementById("actual_date").value) {
            document.getElementById("actual_date").value = startVal.split(" ")[0];
        }

        alert("Survey started at: " + getReadableTime());
    });

    // End button
    document.getElementById("btnEnd").addEventListener("click", function() {
        let endVal = getCurrentDateTimeForDB();
        document.getElementById("actual_end").value = endVal;

        calculateHours();
        alert("Survey ended at: " + getReadableTime());
    });

    // Calculate total hours
  function calculateHours() {
    let start = document.getElementById("actual_start").value;
    let end = document.getElementById("actual_end").value;

    if (start && end) {
        let startDate = new Date(start);
        let endDate = new Date(end);
        let diffHours = (endDate - startDate) / (1000 * 60 * 60); // ms → hours
        document.getElementById("actual_hours").value = diffHours.toFixed(4); // show 4 decimals
    }
}

    // Ajax load enquiry data (existing code)
    $(document).on("change", "#enquirySelect", function() {
        let enquiryId = $(this).val();

        if (enquiryId) {
            $.ajax({
                url: "<?= base_url('index.php/Ajax/get_enquirydata_for_survey') ?>",
                type: "POST",
                data: {
                    enquiry_id: enquiryId
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#project_name").text(response.data.project_name || "--");
                        $("#project_code").text(response.data.project_subject || "--");
                        $("#project_location").text(response.data.project_location || "--");
                        $("#enquiry_category").text(response.data.enquiry_category || "--");
                        $("#enquiry_code").text(response.data.enquiry_code || "--");
                        $("#enquiry_date").text(response.data.enquiry_date || "--");
                        $("#enquiry_source").text(response.data.enquiry_source || "--");
                        $("#client_ref").text(response.data.client_ref_no || "--");
                        $("#remarks").text(response.data.remarks || response.data.comments || "--");

                        $("#scheduled_date").text(response.data.scheduled_date || "--");
                        $("#start_time").text(response.data.start_time || "--");
                        $("#end_time").text(response.data.end_time || "--");
                        $("#total_hours").text(response.data.scheduled_hours || response.data.total_hours || "--");
                        $("#enquiry_id").val(enquiryId);
                        $("#detailsBlock").show();
                    } else {
                        alert("No data found for this enquiry.");
                    }
                },
                error: function() {
                    alert("Error retrieving enquiry data.");
                }
            });
        }
    });
    $(document).ready(function() {
        let attachmentCount = 1; // tracks number of attachments

        $('#add-attachment').click(function() {
            attachmentCount++;

            let attachmentHtml = `
            <div class="col-md-4 mb-2">
                <label>Attachment ${attachmentCount}</label>
                <input type="file" name="attachment[]" class="form-control">
            </div>
        `;

            $('#attachments-wrapper').append(attachmentHtml);
        });
    });

    /* ================= IMAGE FILE VALIDATION ================= */

    
    const allowedFileTypes = [
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/gif',
    'image/webp',
    'application/pdf'
];

const maxFileSize = 5 * 1024 * 1024; // 5 MB

// Validate file input (dynamic or static)
$(document).on('change', 'input[type="file"]', function () {
    let file = this.files[0];

    if (!file) return;

    if (!allowedFileTypes.includes(file.type)) {
        alert('File type not supported. Allowed: PDF, JPG, JPEG, PNG, GIF, WEBP');
        $(this).val(''); // reset invalid file
        return false;
    }

    if (file.size > maxFileSize) {
        alert('File size exceeds 5MB limit');
        $(this).val('');
        return false;
    }
});
</script>
