<?php
/**
 * file: hits.php
 * author: Samuel Taylor
 *
 * Allows for basic hit-tracking
 */
require_once("/var/www_be/tmlm/log.php");
require_once("/var/www_be/tmlm/creds.php");

$db = new PDO("mysql:host=localhost;dbname=tmlm;charset=utf8", $un, $pw, 
    array(PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
try {
    $stmt = $db->prepare("INSERT INTO hits(remoteAddr, userAgent, requestUri)" .
        " VALUES(:addr, :usra, :uri)");
    $stmt->bindValue(":addr", substr($_SERVER["REMOTE_ADDR"], 0, 15),
        PDO::PARAM_STR);
    $stmt->bindValue(":usra", substr($_SERVER["HTTP_USER_AGENT"], 0, 500),
        PDO::PARAM_STR);
    $stmt->bindValue(":uri",  substr($_SERVER["REQUEST_URI"], 0, 50),
        PDO::PARAM_STR);
    $stmt->execute();
} catch (PDOException $e) {
    Log::e($e->getMessage());
}
unset($stmt);
unset($db);
?>

