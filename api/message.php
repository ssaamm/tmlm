<?php
/**
 * file: message.php
 * author: Samuel Taylor
 *
 * The message API. Adds a given message and outputs an unused message from the
 * database. tmlm.js expects the output of this API to be valid JSON.
 */
// Response codes
define("CODE_EMPTY_MSG", 400);
define("CODE_NO_CREDS", 501);
define("CODE_SQL_ERR", 500);
define("CODE_SUCCESS", 200);
// When DEBUG is true, extra SQL error info is put in $response["message"]
define("DEBUG", false);

/**
 * clean
 *
 * Gets rid of special SQL characters in a string.
 *
 * Parameters:
 *  str: the string to be cleaned
 *
 * Return value: the string, with special characters replaced.
 */
function clean($str) {
    static $specialChars = array("\\",   "%",  "_",  "'");
    static $replaceWith  = array("\\\\", "\%", "\_", "\\'");
    return str_replace($specialChars, $replaceWith, $str);
}

header("Content-Type: application/json; charset=utf-8");
include "/var/www_be/tmlm/creds.php";

$response = array("response" => CODE_NO_CREDS, "message" => "Server error");

if (!isset($un) || !isset($pw)) {// creds.php should define these
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
    if (DEBUG) { $response["message"] .= " " . $e->getMessage(); }
    die(json_encode($response));
}

echo json_encode($response);
?>

