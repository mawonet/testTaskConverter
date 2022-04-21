<?php

require_once 'classes/Converter.php';

$obj = new Converter();
$obj->getInstance();
$response = $obj->convert($_POST['value'], $_POST['id']);
echo json_encode($response);
