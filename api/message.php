<?php
/**
 * file: message.php
 * author: Samuel Taylor
 *
 * The message API. Adds a given message and outputs an unused message from the
 * database. tmlm.js expects the output of this API to be valid JSON.
 */
require_once("/var/www_be/tmlm/log.php");
require_once("/var/www_be/tmlm/creds.php");
// Response codes
define("CODE_EMPTY_MSG", 400);
define("CODE_NO_CREDS", 501);
define("CODE_SQL_ERR", 500);
define("CODE_SUCCESS", 200);
// When DEBUG is true, extra SQL error info is put in $response["message"]
define("DEBUG", false);

header("Content-Type: application/json; charset=utf-8");
include "/var/www_be/tmlm/hits.php";

$response = array("response" => CODE_NO_CREDS, "message" => "Server error");

if (!isset($un) || !isset($pw)) {// creds.php should define these
    Log::e("creds.php not loaded (from api/message.php)");
    die(json_encode($response));
}
if (empty($_GET["msg"])) {
    Log::i("empty message");
    $response["response"] = CODE_EMPTY_MSG;
    $response["message"]  = "Please enter a message";
    die(json_encode($response));
} elseif (strlen($_GET["msg"]) > 10000) {
    Log::i("long message of length " . strlen($_GET["msg"]));
    $response["response"] = CODE_LONG_MSG;
    $response["message"]  = "Please enter a shorter message";
}

$db = new PDO("mysql:host=localhost;dbname=tmlm;charset=utf8", $un, $pw, 
    array(PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
try {
    // Insert the given message
    $stmt = $db->prepare("INSERT INTO messages(used, message) VALUES(0, :msg)");
    $stmt->bindValue(":msg", $_GET["msg"], PDO::PARAM_STR);
    $stmt->execute();
    // Get an unused message
    $stmt = $db->query("SELECT message FROM `messages` WHERE used = 0 LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $msg = $row["message"];
    // Set that message as used
    $stmt = $db->prepare("UPDATE messages SET used = 1 " .
        "WHERE message = :msg AND used = 0 LIMIT 1");
    $stmt->bindValue(":msg", $msg, PDO::PARAM_STR);
    $stmt->execute();
    $response["response"] = CODE_SUCCESS;
    $response["message"]  = htmlspecialchars($msg, ENT_HTML5);
} catch (PDOException $e) {
    $response["response"] = CODE_SQL_ERR;
    $response["message"]  = "SQL error";
    if (DEBUG) { $response["message"] .= " " . $e->getMessage(); }
    Log::e("PDOException thrown (api/message.php)" . PHP_EOL .
        $e->getMessage());
    die(json_encode($response));
}

echo json_encode($response);
?>

