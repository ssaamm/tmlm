<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Take a Message, Leave a Message Stats</title>
        <link href="./style/tmlm.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="wrapper">
            <h1>take a message, leave a message</h1>
<?php
include "/var/www_be/tmlm/creds.php";
$db = new PDO("mysql:host=localhost;dbname=tmlm;charset=utf8", $un, $pw,
    array(PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$wordCounts = array();
try {
    $stmt = $db->query("SELECT message FROM `messages`");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $words = explode(" ", $row["message"]);
        foreach ($words as $word) {
            $lowerWord = strtolower($word);
            if (isset($wordCounts[$lowerWord])) {
                $wordCounts[$lowerWord]++;
            } else {
                $wordCounts[$lowerWord] = 1;
            }
        }
    }
    arsort($wordCounts);
    echo "<table>";
    echo "<tr><th>Word</th><th>Count</th></tr>";
    foreach ($wordCounts as $word => $count) {
        echo "<tr><td>$word</td><td>$count</td></tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
}
?>
        </div>
    </body>
</html>