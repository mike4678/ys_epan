var http_request = false; var callback_func;
function dy() { sendRequest(parent.fa1(), cl); setTimeout("dy();", 15000); }
function cl(rmsg) { parent.fa2(rmsg); }

function sendRequest(url, func) {
    http_request = false; callback_func = func;
    if (window.XMLHttpRequest) {
        http_request = new XMLHttpRequest();
        if (http_request.overrideMimeType) {
            http_request.overrideMimeType('text/plain'); 
        }
    } else if (window.ActiveXObject) {
        try { http_request = new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) { try { http_request = new ActiveXObject("Microsoft.XMLHTTP"); } catch (e) { } }
    }
    http_request.onreadystatechange = alertContents;
    http_request.open('GET', url, true);
    http_request.send(null);
}
function alertContents() { if (http_request.readyState == 4) { if (http_request.status == 200) callback_func(http_request.responseText); } }