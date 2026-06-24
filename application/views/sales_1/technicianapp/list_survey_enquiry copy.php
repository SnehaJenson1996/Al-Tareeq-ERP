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
                    <form method="post" action="<?= base_url()?>index.php/Sales/save_survey_details" enctype="multipart/form-data">
                        <input type="hidden" name="enquiry_id"  id="enquiry_id" value="">

                        <div class="row">
                            <div class="col-md-3">
                                <label>Actual Date</label>
                                <input type="date" name="actual_date" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>Actual Start Date & Time</label>
                                <input type="datetime-local" name="actual_start" id="actual_start" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>Actual End Date & Time</label>
                                <input type="datetime-local" name="actual_end" id="actual_end" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>Actual Hours</label>
                                <input type="number" name="actual_hours" id="actual_hours" class="form-control" step="0.01" readonly>
                            </div>
                        </div>

                        <!-- Attachments -->
                        <hr>
                        <h4>Attachments</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Attachment 1</label>
                                <input type="file" name="attachment1" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Attachment 2</label>
                                <input type="file" name="attachment2" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Attachment 3</label>
                                <input type="file" name="attachment3" class="form-control">
                            </div>
                        </div>

                        <!-- Products -->
                        <hr>
                        <h4>Products</h4>

                        

                        <div id="mainMenuContainer"></div>

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
    // Global indices for dynamic elements
    let mainMenuIndex = 0; 
    const subIndices = {}; // track subheading count per main menu

    // Generate Sub Heading + Product Table HTML
    function generateSubHeadingHTML(mainIndex) {
        if (subIndices[mainIndex] === undefined) subIndices[mainIndex] = 0;
        const subIndex = subIndices[mainIndex]++;
        
        return `
        <tr class="subheading-row" data-sub-index="${subIndex}">
            <td colspan="3">
                <label><strong>Sub Heading</strong></label>
                <div class="input-group mb-2">
                    <input type="text" name="sub_heading[${mainIndex}][${subIndex}]" class="form-control form-control-sm" placeholder="Enter Sub Heading">
                    <button type="button" class="btn btn-success btn-sm addSubHeading" data-main-index="${mainIndex}">+</button>
                    <button type="button" class="btn btn-danger btn-sm removeSubHeading">🗑</button>
                </div>
                ${generateProductTableHTML(mainIndex, subIndex)}
            </td>
        </tr>`;
    }

    // Generate Product Table HTML
    function generateProductTableHTML(mainIndex, subIndex) {
        return `
        <table class="table table-bordered productTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Remarks</th>
                    <th style="width:50px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" name="product[${mainIndex}][${subIndex}][]" class="form-control"></td>
                    <td><input type="number" name="quantity[${mainIndex}][${subIndex}][]" class="form-control" step="0.01"></td>
                    <td><input type="text" name="remarks[${mainIndex}][${subIndex}][]" class="form-control"></td>
                    <td><button type="button" class="btn btn-success btn-sm addProductRow">+</button></td>
                </tr>
            </tbody>
        </table>`;
    }

    // Generate full Main Menu HTML block
    function generateMainMenuHTML(index) {
        return `
        <div id="product_div${index}" class="main-menu-block" style="margin-bottom: 20px;">
            <table class="table table-bordered table-hover" id="pdetails${index}">
                <thead>
                    <tr style="background-color:#94C973; color:black; font-weight:bold">
                        <th width="40%">
                            Main Heading
                            <input type="text" name="main_heading[${index}]" class="form-control form-control-sm" placeholder="Add Main Heading" required>
                        </th>
                        <th>
                            Details:
                            <textarea name="main_details[${index}]" class="form-control form-control-sm" placeholder="Enter Details"></textarea>
                        </th>
                        <th width="10%">
                            <button type="button" class="btn btn-sm bg-orange addMainMenu" title="Add Main Menu">
                                <span class="fa fa-plus"></span>
                            </button>
                            <button type="button" class="btn btn-xs bg-orange removeMainRow" data-main-index="${index}" title="Remove Main Menu">
                                <span class="fa fa-trash"></span>
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody class="subheading-container">
                    ${generateSubHeadingHTML(index)}
                </tbody>
            </table>
        </div>`;
    }

    // On page load: add first main menu block dynamically
    $(document).ready(function() {
        $("#mainMenuContainer").append(generateMainMenuHTML(mainMenuIndex));
        mainMenuIndex++;
    });

    // Add Main Menu button click handler
    $(document).on("click", ".addMainMenu", function() {
        $("#mainMenuContainer").append(generateMainMenuHTML(mainMenuIndex));
        mainMenuIndex++;
    });

    // Remove Main Menu block
    $(document).on("click", ".removeMainRow", function() {
        let mainIndex = $(this).data("main-index");
        $("#product_div" + mainIndex).remove();
        // Optionally delete subIndices[mainIndex] to clean memory
        delete subIndices[mainIndex];
    });

    // Add Sub Heading row after the clicked subheading row
    $(document).on("click", ".addSubHeading", function() {
        let mainIndex = $(this).data("main-index");
        let subHTML = generateSubHeadingHTML(mainIndex);
        $(this).closest(".subheading-row").after(subHTML);
    });

    // Remove Sub Heading row
    $(document).on("click", ".removeSubHeading", function() {
        $(this).closest(".subheading-row").remove();
    });

    // Add Product Row inside the product table
    $(document).on("click", ".addProductRow", function() {
        let tbody = $(this).closest("tbody");

        // Get the name attribute of the first product input to preserve naming
        let firstProductInput = tbody.find('input[name^="product"]').first();
        if (!firstProductInput.length) return;

        let baseName = firstProductInput.attr('name'); // e.g. product[0][0][]
        let quantityName = baseName.replace('product', 'quantity');
        let remarksName = baseName.replace('product', 'remarks');

        let newRow = `<tr>
            <td><input type="text" name="${baseName}" class="form-control"></td>
            <td><input type="number" name="${quantityName}" class="form-control" step="0.01"></td>
            <td><input type="text" name="${remarksName}" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm removeProductRow">🗑</button></td>
        </tr>`;

        tbody.append(newRow);
    });

    // Remove Product Row
    $(document).on("click", ".removeProductRow", function() {
        $(this).closest("tr").remove();
    });

    // Calculate Actual Hours (existing code)
    document.getElementById('actual_start').addEventListener('change', calculateHours);
    document.getElementById('actual_end').addEventListener('change', calculateHours);

    function calculateHours() {
        let start = document.getElementById('actual_start').value;
        let end = document.getElementById('actual_end').value;

        if (start && end) {
            let startDate = new Date(start);
            let endDate = new Date(end);
            let diffHours = (endDate - startDate) / (1000 * 60 * 60);
            document.getElementById('actual_hours').value = diffHours.toFixed(2);
        }
    }

    // Ajax load enquiry data (existing code)
    $(document).on("change", "#enquirySelect", function () {
        let enquiryId = $(this).val();

        if (enquiryId) {
            $.ajax({
                url: "<?= base_url('index.php/Ajax/get_enquirydata_for_survey') ?>",
                type: "POST",
                data: { enquiry_id: enquiryId },
                dataType: "json",
                success: function (response) {
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
                error: function () {
                    alert("Error retrieving enquiry data.");
                }
            });
        }
    });
</script>