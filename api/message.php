<?php
define("CODE_NO_CREDS", 501);
define("CODE_EMPTY_MSG", 400);
define("CODE_SQL_ERR", 500);
define("CODE_SUCCESS", 200);
define("DEBUG", true);

function clean($str) {
    static $specialChars = array("\\",   "%",  "_",  "'");
    static $replaceWith  = array("\\\\", "\%", "\_", "\\'");
    return str_replace($specialChars, $replaceWith, $str);
}

header("Content-Type: application/json; charset=utf-8");
include "/var/www_be/tmlm/creds.php";

$response = array("response" => CODE_NO_CREDS, "message" => "Server error");

if (!isset($un) || !isset($pw)) {
    die(json_encode($response));
}
if (empty($_GET["msg"])) {
    $response["response"] = CODE_EMPTY_MSG;
    $response["message"]  = "Please enter a message";
    die(json_encode($response));
}

$db = new PDO("mysql:host=localhost;dbname=tmlm;charset=utf8", $un, $pw, 
    array(PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
try {
    // Insert the given message
    $stmt = $db->prepare("INSERT INTO messages(used, message) VALUES(0, '" .
        clean($_GET["msg"]) . "')");
    $stmt->execute();
    // Get an unused message
    $stmt = $db->query("SELECT message FROM `messages` WHERE used = 0 LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $msg = $row["message"];
    // Set that message to be used
    $stmt = $db->prepare("UPDATE messages SET used = 1 " .
        "WHERE message = '" . clean($msg) . "' AND used = 0 LIMIT 1");
    $stmt->execute();

    $response["response"] = CODE_SUCCESS;
    $response["message"]  = $msg;
} catch (PDOException $e) {
    $response["response"] = CODE_SQL_ERR;
    $response["message"]  = "SQL error";
    if (DEBUG) {
        $response["message"] .= " " . $e->getMessage();
        $response["message"] .= " " . "INSERT INTO messages(used, message) " .
            "VALUES(0, '" . clean($_GET["msg"]) . "')";
    }
    die(json_encode($response));
}

echo json_encode($response);
?>

