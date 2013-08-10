<?php
define("CODE_DEFAULT", 0);

header("Content-Type: application/json; charset=utf-8");

$response = array("response" => CODE_DEFAULT, "message" => "Default message");

if (!empty($_GET["msg"])) {
    $response["message"] = $_GET["msg"];
}

echo json_encode($response);
?>

