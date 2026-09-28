$(document).ready(function() {
    // START DATATABLE
    var table = $('#contractorProjectsTable').DataTable({
        responsive: true,
        autoWidth: false,
        dom: 'Bfrtip',
        lengthMenu: [10],
        buttons: 
        [
            {
                extend: 'collection',
                text: '<i class="bi bi-download"></i> Export',
                className: 'btn custom-btn dropdown-toggle m-0',
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
        "ajax": "/CPMS/controllers/fetch_contractor_projects.php",
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
                    return `<span class="badge ${colorClass}">${data}</span>`;
                }
            },
            {"data": "project_budget"},
            { "data": "materials", "render": data => data ? data : "<i>No Materials</i>" },
            { "data": "employees", "render": data => data ? data : "<i>No Employees</i>" },
            { 
                "data": null,
                "render": function(data, type, row) {
                    if (row.project_status === 'Pending') {
                        return `<button class="btn btn-sm editBtn w-100" data-id="${row.project_id}"><i class="bi bi-pencil-square"></i></button>`;
                    } else {
                        return `<button class="btn btn-sm btn-danger w-100" data-id="${row.project_id}" disabled><i class="bi bi-ban"></i></button>`;
                    }
                }
            }
        ],
        "order": [[0, "desc"]]
    });

    //MINIMUM DATE
    function setMinDates() {
        const today = new Date().toISOString().split('T')[0];
        $('input[type="date"]').each(function(){
            $(this).attr('min', today);
        });
    }
    setMinDates();
    
    //min date start/end (add project)
    $('#project_start').change(function(){
        const startDate = $(this).val();
        const endDateInput = $('#project_end');

        if (startDate) {
            endDateInput.attr('min', startDate);

            if (endDateInput.val() && endDateInput.val() < startDate) {
                endDateInput.val('');
            }
        }
    });
    
    //min date start/end (edit project)
    $('#contractor_edit_project_start').change(function(){
        const startDate = $(this).val();
        const endDateInput = $('#contractor_edit_project_end');

        if (startDate) {
            endDateInput.attr('min', startDate);
            
            if (endDateInput.val() && endDateInput.val() < startDate) {
                endDateInput.val('');
            }
        }
    });

    // ADD NEW MATERIAL
    $("#contractorAddMaterialBtn").click(function () {
        let materialRow = `
            <div class="row mb-2 gap-3 p-2 material-item">
                <div class="col-12">
                    <input type="text" name="material_name[]" class="form-control" placeholder="Material Name" required>
                </div>
                <div class="col-12">
                    <input type="number" name="material_price[]" class="form-control" placeholder="Price" step="0.01" min="0" required>
                </div>
                <div class="col-12">
                    <input type="number" name="material_quantity[]" class="form-control" placeholder="Quantity" min="0" required>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-danger removeMaterialBtn">Remove</button>
                </div>
            </div>`;
        $("#contractorMaterialsContainer").append(materialRow);
    });

    // REMOVE MATERIAL
    $(document).on("click", ".removeMaterialBtn", function () {
        $(this).closest(".material-item").remove();
    });

    // SUBMIT CONTRACTOR ADD PROJECT FORM
    $("#contractorProjectForm").submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: "/CPMS/controllers/add_contractor_project.php",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                var res = response;

                if (res.success) {
                    Swal.fire({
                        title: 'Submitted!',
                        text: "Project submitted successfully for admin approval!",
                        icon: 'success',
                        showConfirmButton: true
                    });
                    $('#contractorProjectForm')[0].reset();
                    $('#contractorAddProjectModal').modal('hide');
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: "Error: " + res.message,
                        icon: 'error',
                        showConfirmButton: true
                    });
                }
            },
            error: function() {
                Swal.fire({
                        title: 'Error!',
                        text: "Error submitting project!",
                        icon: 'error',
                        showConfirmButton: true
                });
            }
        });
    });

$("#employeeForm").submit(function(e) {
    e.preventDefault();

    const employeeName = $('input[name="employee_name"]').val().trim();
    const employeeRole = $('input[name="employee_role"]').val().trim();
    const employeeSalary = parseFloat($('input[name="employee_salary"]').val());

    if (!employeeName || !employeeRole || isNaN(employeeSalary)) {
        Swal.fire('Error!', 'Please fill in all fields with valid data.', 'error');
        return;
    }

    if (employeeSalary < 0) {
        Swal.fire('Error!', 'Salary cannot be negative.', 'error');
        return;
    }

    $.ajax({
        url: "/CPMS/controllers/add_employee.php",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonColor: '#3085d6'
                });
                
                $('#employeeForm')[0].reset();
                $('#addEmployeeModal').modal('hide');
                
                refreshEmployeeDropdowns();
                
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: response.message || 'Failed to add employee',
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: 'Server Error!',
                text: 'Failed to add employee. Please try again.',
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
        }
    });
});

function refreshEmployeeDropdowns() {
    $.getJSON('/CPMS/controllers/get_available_employees.php', function(employees) {
        const projectSelect = $('#contractorEmployees');
        if (projectSelect.length) {
            projectSelect.empty();
            
            if (employees.length > 0) {
                employees.forEach(emp => {
                    projectSelect.append(
                        `<option value="${emp.employee_id}">${emp.employee_name} (Role: ${emp.employee_role})</option>`
                    );
                });
            } else {
                projectSelect.append('<option disabled>No available employees found</option>');
            }
        }
    }).fail(function() {
        Swal.fire('Error!', 'Failed to refresh employee list', 'error');
    });

    const editProjectId = $('#contractor_edit_project_id').val();
    if (editProjectId) {
        loadEmployeeSelectionForEdit(editProjectId);
    }
}

// EDIT CONTRACTOR PROJECT BUTTON
$('#contractorProjectsTable').on('click', '.editBtn', function() {
    var id = $(this).data('id');
    
    $.getJSON('/CPMS/controllers/get_contractor_project.php', { project_id: id }, function(data) {
        if (data.error) {
            Swal.fire('Error!', data.error, 'error');
            return;
        }
        
        $('#contractor_edit_project_id').val(data.project_id);
        $('#contractor_edit_project_name').val(data.project_name);
        $('#contractor_edit_project_type').val(data.project_type);
        $('#contractor_edit_project_location').val(data.project_location);
        $('#contractor_edit_project_start').val(data.project_start);
        $('#contractor_edit_project_end').val(data.project_end);
        
        loadEditableProjectMaterials(id);
        loadEmployeeSelectionForEdit(id, data.contractor_id); // NOW PASS contractor_id
        
        var modal = new bootstrap.Modal(document.getElementById('contractorEditProjectModal'));
        modal.show();
    }).fail(function() {
        Swal.fire('Error!', 'Failed to load project data', 'error');
    });
});

function loadEditableProjectMaterials(projectId) {
    $.getJSON('/CPMS/controllers/get_project_materials.php', { project_id: projectId }, function(materials) {
        const container = $('#contractorEditMaterialsContainer');
        container.empty();
        
        if (materials.length > 0) {
            materials.forEach((material, index) => {
                container.append(`
                    <div class="row mb-2 p-2 border rounded material-edit-item">
                        <input type="hidden" name="material_ids[]" value="${material.material_id}">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="material_names[]" value="${material.material_name}" placeholder="Material Name" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="material_prices[]" value="${material.material_price}" step="0.01" min="0" placeholder="Price" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="material_quantities[]" value="${material.material_quantity}" min="0" placeholder="Quantity" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-sm btn-danger remove-edit-material">Remove</button>
                        </div>
                    </div>
                `);
            });
        } else {
            container.append('<div class="text-muted text-center py-3">No materials added to this project.</div>');
        }
    }).fail(function() {
        Swal.fire('Error!', 'Failed to load materials', 'error');
    });
}

function loadEmployeeSelectionForEdit(projectId, contractorId) {
    $.ajax({
        url: "/CPMS/controllers/get_employee_selection_for_edit.php",
        type: "POST",
        data: {
            project_id: projectId,
            contractor_id: contractorId
        },
        dataType: "json",
        success: function(response) {
            const employeeSelect = $('#contractorEditEmployees');
            employeeSelect.empty();
            
            if (response.employees && response.employees.length > 0) {
                response.employees.forEach(emp => {
                    const selected = emp.currently_assigned ? 'selected' : '';
                    employeeSelect.append(
                        `<option value="${emp.employee_id}" ${selected}>${emp.employee_name} (Role: ${emp.employee_role})</option>`
                    );
                });
            } else {
                employeeSelect.append('<option disabled>No employees available</option>');
            }
        },
        error: function() {
            Swal.fire('Error!', 'Failed to load employees', 'error');
        }
    });
}

$('#contractorEditAddMaterialBtn').click(function() {
    const container = $('#contractorEditMaterialsContainer');
    
    container.find('.text-muted').remove();
    
    container.append(`
        <div class="row mb-2 p-2 border rounded material-edit-item">
            <input type="hidden" name="material_ids[]" value="new">
            <div class="col-md-4">
                <input type="text" class="form-control" name="material_names[]" placeholder="Material Name" required>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="material_prices[]" placeholder="Price" step="0.01" min="0" required>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="material_quantities[]" placeholder="Quantity" min="0" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-danger remove-edit-material">Remove</button>
            </div>
        </div>
    `);
});

$(document).on('click', '.remove-edit-material', function() {
    $(this).closest('.material-edit-item').remove();
});

$('#contractorEditProjectForm').submit(function(e) {
    e.preventDefault();


    let hasMaterialErrors = false;
    $('input[name="material_names[]"]').each(function() {
        if (!$(this).val().trim()) {
            hasMaterialErrors = true;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    if (hasMaterialErrors) {
        Swal.fire('Error!', 'Please fill in all material names.', 'error');
        return;
    }

    const startDate = new Date($('#contractor_edit_project_start').val());
    const endDate = new Date($('#contractor_edit_project_end').val());
    const today = new Date(); 
    today.setHours(0, 0, 0, 0);

    if (startDate < today) {
        Swal.fire('Error!', 'Start date cannot be in the past!', 'error');
        return;
    }
    if (endDate < today) {
        Swal.fire('Error!', 'End date cannot be in the past!', 'error');
        return;
    }
    if (startDate > endDate) {
        Swal.fire('Error!', 'End date cannot be before start date!', 'error');
        return;
    }

    $.ajax({
        url: "/CPMS/controllers/update_contractor_project.php",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    $('#contractorEditProjectModal').modal('hide');
                    $('#contractorProjectsTable').DataTable().ajax.reload(null, false);
                });
            } else {
                Swal.fire('Error!', response.message, 'error');
            }
        },
        error: function() {
            Swal.fire('Server Error!', 'Failed to update project. Please try again.', 'error');
        }
    });
});
    
    // Reset modal when closed
    $('#contractorAddProjectModal').on('hidden.bs.modal', function() {
        const today = new Date().toISOString().split('T')[0];
        $('#project_start').val(today);
        $('#project_end').val('');
        $('#employeeAvailabilityMessage').text('');
    });

    // Date validation
    $('input[type="date"]').on('change', function() {
        const input = $(this);
        const value = input.val();
        const today = new Date().toISOString().split('T')[0];
        
        if (value && value < today) {
            input.addClass('is-invalid');
            if (!input.next('.invalid-feedback').length) {
                input.after('<div class="invalid-feedback">Date cannot be in the past</div>');
            }
        } else {
            input.removeClass('is-invalid');
            input.next('.invalid-feedback').remove();
        }
    });


    // DISPLAY AVAILABLE EMPLOYEES IN CONTRACTORS DASHBOARD
    $.ajax({
    url: '/CPMS/controllers/get_available_employees.php',
    type: 'GET',
    dataType: 'json',
    success: function(data) {
        var $employeeList = $('#employee-list');
        $employeeList.empty();

        if (data.length === 0) {
            $employeeList.html("<p class='text-muted'>No available employees.</p>");
            $('#total-employees').text(0);
            $('#available-employees-count').text(0);
            $('#busy-employees-count').text(0);
            return;
        }

        var total = data.length;
        var available = data.filter(emp => emp.status === 'available').length;
        var busy = data.filter(emp => emp.status === 'busy').length;

        $('#total-employees').text(total);
        $('#available-employees-count').text(available);
        $('#busy-employees-count').text(busy);

        data.forEach(function(emp) {

         var statusBadge = emp.status === 'available' 
                ? '<span class="badge badge-available me-1">Available</span>'
                : `<span class="badge badge-in-progress me-1">In Progress</span>`;
        
            var employeeHtml = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${emp.employee_name} Role: (${emp.employee_role}) Status: ${statusBadge}</strong> 
                        </div>
                    </div>
            `;
            $employeeList.append(employeeHtml);
        });
        },
        error: function() {
            $('#employee-list').html("<p class='text-danger'>Failed to load employees.</p>");
        }
    });

$('#removeEmployeeModal').on('show.bs.modal', function() {
    loadEmployeesForRemoval();
});

function loadEmployeesForRemoval() {
    $.ajax({
        url: "/CPMS/controllers/get_contractor_employees.php",
        type: "GET",
        dataType: "json",
        success: function(response) {
            const select = $('#removeEmployeeSelect');
            select.empty();
            select.append('<option value="">-- Choose an employee --</option>');
            
            if (response.employees && response.employees.length > 0) {
                response.employees.forEach(emp => {
                    select.append(
                        `<option value="${emp.employee_id}">${emp.employee_name} (Role: ${emp.employee_role})</option>`
                    );
                });
            } else {
                select.append('<option disabled>No employees found</option>');
            }
        },
        error: function() {
            Swal.fire('Error!', 'Failed to load employees', 'error');
        }
    });
}

// Submit remove employee form
$("#removeEmployeeForm").submit(function(e) {
    e.preventDefault();

    const employeeId = $('#removeEmployeeSelect').val();

    if (!employeeId) {
        Swal.fire('Error!', 'Please select an employee to remove.', 'error');
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone. The employee will be permanently removed.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/CPMS/controllers/remove_employee.php",
                type: "POST",
                data: { employee_id: employeeId },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6'
                        }).then(() => {
                            $('#removeEmployeeForm')[0].reset();
                            $('#removeEmployeeModal').modal('hide');
                            refreshEmployeeDropdowns();
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Failed to remove employee', 'error');
                }
            });
        }
    });
});
});
