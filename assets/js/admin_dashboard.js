$(document).ready(function() {
    // start DataTable
    var table = $('#projectsTable').DataTable({
        responsive: true,
        autoWidth: false,
        dom: 'Bfrtip',
        lengthMenu: [10],
        buttons: 
        [
            {
                extend: 'collection',
                text: '<i class="bi bi-download"></i> Export',
                className: 'btn custom-btn dropdown-toggle',
                buttons: 
                [
                    {
                        extend: 'copy',
                        text: '<i class="bi bi-clipboard me-2"></i> Copy',
                        className: 'dropdown-item buttons-copy text-white my-2'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="bi bi-file-earmark-spreadsheet me-2 my-2"></i> Excel',
                        className: 'dropdown-item buttons-excel text-white my-2'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="bi bi-filetype-csv me-2 my-2"></i> CSV',
                        className: 'dropdown-item buttons-csv text-white my-2'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="bi bi-file-pdf-fill me-2 my-2"></i> PDF',
                        className: 'dropdown-item buttons-pdf text-white my-2'
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer-fill me-2 my-2"></i> Print',
                        className: 'dropdown-item buttons-print text-white my-2'
                    }
                ]
            }
        ],
        "ajax": "/CPMS/controllers/fetch_project.php",
        "columns": [
            { "data": "project_number",
            "render": function(data) {
                return data;
            }},
            { "data": "project_name" },
            { "data": "project_type" },
            { "data": "project_location" },
            { 
                "data": "contractor_name", 
                "render": function(data) {
                    return data ? data : "<i>Not Assigned</i>";
                }
            },
            { "data": "project_start" },
            { "data": "project_end" },
            {
                "data": "project_status",
                "render": function(data) {
                    let colorClass = "";
                    if (data === "Pending") colorClass = "bg-warning";
                    else if (data === "Ongoing") colorClass = "bg-info";
                    else if (data === "Completed") colorClass = "bg-success";
                    return `<span class="badge ${colorClass} text-dark">${data}</span>`;
                }
            },
            {"data": "project_budget"},
            { "data": "materials", "render": data => data ? data : "<i>No Materials</i>" },
            { "data": "employees", "render": data => data ? data : "<i>No Employees</i>" },
            { 
                "data": null,
                "render": function(data, type, row) {
                    return `
                        
                        <button class="btn btn-sm editBtn w-100 my-2" data-id="${row.project_id}"><i class="bi bi-pencil-square"></i></button>
                        <button class="btn btn-sm deleteBtn w-100" data-id="${row.project_id}"><i class="bi bi-trash3"></i></button>
                    `;
                }
            }
        ],
        "order": [[0, "desc"]]
    });
 

    // add new material 
    $("#addMaterialBtn").click(function () {
        let materialRow = `
            <div class="row mb-2 material-item">
                <div class="col-md-4">
                    <input type="text" name="material_name[]" class="form-control" placeholder="Material Name" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="material_price[]" class="form-control" placeholder="Price" step="0.01" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="material_quantity[]" class="form-control" placeholder="Quantity" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-danger removeMaterialBtn">Remove</button>
                </div>
            </div>`;
        $("#materialsContainer").append(materialRow);
    });

    // remove material 
    $(document).on("click", ".removeMaterialBtn", function () {
        $(this).closest(".material-item").remove();
    });

    // submit add project form
    $("#projectForm").submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: "/CPMS/controllers/add_project.php",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                var res = response;

                if (res.success) {
                    Swal.fire({
                        title: "Project added!",
                        text: res.message,
                        icon: 'success',
                        timer: 2500,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                    $('#projectForm')[0].reset();
                    $('#addProjectModal').modal('hide');
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: res.message,
                        icon: 'error',
                        showConfirmButton: true
                    });
                }
            },
            error: function() {
                alert("Error inserting project!");
            }
        });
    });

    // edit button
    $('#projectsTable').on('click', '.editBtn', function() {
        var id = $(this).data('id');
        $.getJSON('/CPMS/controllers/get_project.php', { project_id: id }, function(data) {
            if (data.error) {
                alert(data.error);
                return;
            }
            $('#edit_project_id').val(data.project_id);
            $('#edit_project_name').val(data.project_name);
            $('#edit_project_type').val(data.project_type);
            $('#edit_project_location').val(data.project_location);
            $('#edit_project_start').val(data.project_start);
            $('#edit_project_end').val(data.project_end);
            $('#edit_project_status').val(data.project_status);
            $('#edit_project_budget').val(data.project_budget);
            var modal = new bootstrap.Modal(document.getElementById('editProjectModal'));
            modal.show();
        });
    });

    // SUBMIT EDIT FORM
    $('#editProjectForm').submit(function(e) {
        e.preventDefault();
        $.post('/CPMS/controllers/update_project.php', $(this).serialize(), function(response) {
            var res = JSON.parse(response);
            if (res.success) {
                Swal.fire({
                    title: 'Updated!',
                    text: 'Project updated successfully!',
                    icon: 'success',
                    showConfirmButton: true
                });
                table.ajax.reload(null, false);
                $('#editProjectModal').modal('hide');
            } else {
                alert('Failed: ' + res.error);
            }
        });
    });

    // DELETE BUTTON
    $('#projectsTable').on('click', '.deleteBtn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Delete?',
            text: 'Are you sure you want to delete this project?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#3b6ba5",
            cancelButtonColor: "#e03e2f",
            confirmButtonText: "Yes, delete!"
        }).then((result) => {
            if(result.isConfirmed){
                $.post('/CPMS/controllers/delete_project.php', { project_id: id }, function(response) {
                var res = JSON.parse(response);
                    if (res.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Project deleted successfully!',
                            icon: 'error',
                        });
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            title: 'Deleted!',
                            timer: 2000,
                            text: `Failed ${res.error}`,
                            icon: 'error',
                        });
                    }
                });
            }
        })
    });
});
