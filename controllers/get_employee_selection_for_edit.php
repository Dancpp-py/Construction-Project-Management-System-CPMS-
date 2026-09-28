<?php
session_start();
include_once '../config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = intval($_POST['project_id'] ?? 0);
    $contractor_id = intval($_POST['contractor_id'] ?? 0);

    $response = [
        'employees' => [],
        'currently_assigned_count' => 0,
        'debug' => [] 
    ];


    $check_query = "SELECT COUNT(*) as emp_count FROM tbl_employee WHERE contractor_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $contractor_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $check_row = $check_result->fetch_assoc();
    $response['debug']['total_employees_for_contractor'] = $check_row['emp_count'];

    $query = "
        SELECT 
            e.employee_id, 
            e.employee_name, 
            e.employee_role,
            CASE 
                WHEN EXISTS (
                    SELECT 1 
                    FROM tbl_project_employee pe 
                    WHERE pe.employee_id = e.employee_id 
                    AND pe.project_id = ?
                ) THEN 1 
                ELSE 0 
            END AS currently_assigned
        FROM tbl_employee e
        WHERE e.contractor_id = ?
        AND (
            -- Employees currently assigned to this project
            e.employee_id IN (
                SELECT employee_id FROM tbl_project_employee WHERE project_id = ?
            )
            OR
            -- Employees not assigned to any Ongoing or Pending projects (excluding current project)
            e.employee_id NOT IN (
                SELECT DISTINCT pe.employee_id 
                FROM tbl_project_employee pe 
                JOIN tbl_project p ON pe.project_id = p.project_id 
                WHERE p.project_status IN ('Ongoing', 'Pending')
                AND p.project_id != ?
            )
        )
        ORDER BY e.employee_name ASC
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        $response['debug']['query_error'] = $conn->error;
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param("iiii", $project_id, $contractor_id, $project_id, $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response['employees'][] = $row;
        if ($row['currently_assigned']) {
            $response['currently_assigned_count']++;
        }
    }

    $response['debug']['employees_found'] = count($response['employees']);
    echo json_encode($response);
    exit;
}

echo json_encode(['employees' => [], 'currently_assigned_count' => 0]);
?>