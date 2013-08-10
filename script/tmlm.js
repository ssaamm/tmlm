function onLoad() {
    window.console && console.log("Hello");
}

function onGoClicked() {
    var request;
    try {
        request = new XMLHttpRequest();
    } catch (e) {
        request = new ActiveXObject("Microsoft.XMLHTTP");
    }
    request.open("GET", "", true);
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

if (window.addEventListener) {
    window.addEventListener("load", onLoad, false);
} else if (window.attachEvent) {// IE
    window.attachEvent("onload", onLoad);
}
