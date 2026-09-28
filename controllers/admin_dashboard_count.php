<?php
include '../../config/db.php';

$pending_projects_query   =   "SELECT COUNT(*) as count FROM tbl_project WHERE project_status = 'Pending'";
$ongoing_projects_query   =   "SELECT COUNT(*) as count FROM tbl_project WHERE project_status = 'Ongoing'";
$completed_projects_query =   "SELECT COUNT(*) as count FROM tbl_project WHERE project_status = 'Completed'";

$pending_result     = $conn->query($pending_projects_query);
$ongoing_result     = $conn->query($ongoing_projects_query);
$completed_result   = $conn->query($completed_projects_query);

$pending_projects   = $pending_result->fetch_assoc()['count'];
$ongoing_projects   = $ongoing_result->fetch_assoc()['count'];
$completed_projects = $completed_result->fetch_assoc()['count'];

$total_projects     = $pending_projects + $ongoing_projects + $completed_projects;

$contractor_query   = "SELECT COUNT(*) as total_contractors FROM tbl_contractor";

$contractor_result  = $conn->query($contractor_query);
$total_contractors  = $contractor_result->fetch_assoc()['total_contractors'];
?>