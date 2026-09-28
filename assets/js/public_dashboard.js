$(document).ready(function() {
    // start DataTable
    var table = $('#publicProjectsTable').DataTable({
        responsive: true,
        autoWidth: false,
        dataType: "json",
        lengthMenu: [10],
        "ajax": "/CPMS/controllers/fetch_public_projects.php",
        "columns": [
            { "data": 'project_number',
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
            { "data": "employees", "render": data => data ? data : "<i>No Employees</i>" }
        ],
        "order": [[0, "desc"]]
    });
});