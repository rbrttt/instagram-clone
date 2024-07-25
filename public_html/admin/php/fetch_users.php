<?php
include '../../../config/config.php';
include '../php/function.php';
$users = getUsersList();
echo json_encode($users);
?>
