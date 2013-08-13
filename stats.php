<?php
/**
 * file: stats.php
 * author: Samuel Taylor
 *
 * Displays the most commonly used words.
 */
include "/var/www_be/tmlm/creds.php";
include "/var/www_be/tmlm/log.php";
define("DEBUG", false);
// The number of words to show
define("MAX_WORDS", 15);
define("TABLE_TAB_LEVEL", str_repeat("    ", 3));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>take a message, leave a message stats</title>
        <link href="./style/stats.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="wrapper">
            <h1>take a message, leave a message</h1>
            <h2><?php echo MAX_WORDS; ?> most common words</h2>
<?php
$db = new PDO("mysql:host=localhost;dbname=tmlm;charset=utf8", $un, $pw,
    array(PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$wordCounts = array();
$numMessages = 0;
$numWords = 0;
try {
    $stmt = $db->query("SELECT message FROM `messages`");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $words = explode(" ", $row["message"]);

        $numMessages++;
        $numWords += count($words);

        foreach ($words as $word) {
            $lowerWord = strtolower($word);
            if (isset($wordCounts[$lowerWord])) {
                $wordCounts[$lowerWord]++;
            } else {
                $wordCounts[$lowerWord] = 1;
            }
        }
    }
} catch (PDOException $e) {
    if (DEBUG) { echo $e->getMessage(); }
    Log::e("PDOException thrown (stats.php)" . PHP_EOL .
        $e->getMessage());
}

arsort($wordCounts);// Sort on values, high to low
echo TABLE_TAB_LEVEL . "<table>" . PHP_EOL;
echo TABLE_TAB_LEVEL . "<tr><th>rank</th><th>word</th><th>count</th></tr>" . PHP_EOL;
$rank = 1;
foreach ($wordCounts as $word => $count) {
    echo TABLE_TAB_LEVEL . "<tr><td>$rank</td><td>$word</td><td>$count</td></tr>" . PHP_EOL;
    $rank++;
    if ($rank > MAX_WORDS) { break; }
}
echo TABLE_TAB_LEVEL . "</table>" . PHP_EOL;
?>
            <h2>word counts</h2>
            <p>number of messages: <?php echo $numMessages; ?></p>
            <p>number of words: <?php echo $numWords; ?></p>
            <p>average words per message: <?php echo round($numWords/$numMessages, 2); ?></p>
        </div>
    </body>
</html>

