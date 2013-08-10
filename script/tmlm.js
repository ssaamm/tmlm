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

window["onGoClicked"] = onGoClicked;

