<!DOCTYPE html>
<?php
include "/var/www_be/tmlm/hits.php";
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>take a message, leave a message</title>
        <link href="./style/tmlm.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="./script/tmlm.js"></script>
        <!-- Hi there! You may be interested in seeing the source code for all
             of this at github.com/ssaamm/tmlm -->
    </head>
    <body>
        <div id="wrapper">
            <h1>take a message, leave a message</h1>
            <input type="text" id="msg_in" placeholder="enter message, then click below "><br>
            <button id="go_button" onclick="onGoClicked()">leave a message</button>
            <p><a href="./stats.php">stats</a></p>
            <p id="msg"></p>
        </div>
    </body>
</html>

