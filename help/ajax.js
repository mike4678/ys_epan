var http_request = false;
var callback_func;

function sendRequest(url, argstr, func, method) {
    //传入中文数据时把中文 escape()
    //服务器输出中文时需用 Response.Charset("GB2312"),则无乱码
    http_request = false;
    callback_func = func;
    if (method == "") { method = "GET"; }
    if (window.XMLHttpRequest) { // Mozilla, Safari,...
        http_request = new XMLHttpRequest();
        if (http_request.overrideMimeType) {
            http_request.overrideMimeType('text/xml');
        }
    } else if (window.ActiveXObject) { // IE
        try {
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                http_request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) { }
        }
    }

    if (!http_request) {
        alert('Giving up :( Cannot create an XMLHTTP instance');
        return false;
    }
    http_request.onreadystatechange = alertContents;

    if (method == 'GET') {
        http_request.open('GET', url + '?' + argstr, true);
        http_request.send(null);
    } else {

        http_request.open('POST', url, true);
        http_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
        http_request.send(argstr);
    }
}

function alertContents() {

    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
            callback_func(http_request.responseText);
            //callback_func(http_request.responseXML);
        } else {
            alert('There was a problem with the request.');
        }
    }
}