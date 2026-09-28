<?php
$ongoing_projects_query   =   "SELECT COUNT(*) as count FROM tbl_project WHERE project_status = 'Ongoing'";
$completed_projects_query =   "SELECT COUNT(*) as count FROM tbl_project WHERE project_status = 'Completed'";

$ongoing_result     = $conn->query($ongoing_projects_query);
$completed_result   = $conn->query($completed_projects_query);

$ongoing_projects   = $ongoing_result->fetch_assoc()['count'];
$completed_projects = $completed_result->fetch_assoc()['count'];

$total_projects     = $ongoing_projects + $completed_projects;
?>