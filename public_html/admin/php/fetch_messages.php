<?php
include '../../../config/config.php';
include '../php/function.php';
$messages = getMessagesList();
echo json_encode($messages);

?>
