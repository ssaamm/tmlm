/**
 * file: tmlm.js
 * author: Samuel Taylor
 *
 * Contains function defining the behavior of the "leave a message" button
 */

/**
 * onGoClicked
 *
 * Makes a request to the message API (assumed to be at "./script/message.php")
 * with the entered message (assumed to be in a text input with id "msg_in") and
 * displays the message the API returns (in an element with id "msg").
 */
function onGoClicked() {
    var request;
    try {
        request = new XMLHttpRequest();
    } catch (e) {// IE
        request = new ActiveXObject("Microsoft.XMLHTTP");
    }
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var response = eval("(" + request.responseText + ")");
            document.getElementById("msg").innerHTML = response["message"]; 
        }
    };
    var msg = document.getElementById("msg_in").value;
    request.open("GET", "./api/message.php?msg=" + msg, true);
    request.send();
}

/* This is here to allow usage with the advanced optimizations in Google's
   Closure Compiler */
window["onGoClicked"] = onGoClicked;

