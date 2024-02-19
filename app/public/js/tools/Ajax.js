/**
 * Sends an AJAX request to the specified URL using the specified HTTP method.
 *
 * @param {string} url - The URL to send the AJAX request to.
 * @param {string} [type="POST"] - The HTTP method to use for the request. Default is "POST".
 * @param {string} data - The data to send along with the request.
 * @param {Function} onSuccess - The success callback function to handle the response.
 * @param {Function} onError - The error callback function to handle any errors.
 * @return {void} - This method does not return a value.
 */
export function sendAjaxRequest(url, type = "POST", data, onSuccess, onError) {
    let xhr;
    xhr = processRequest(url, type, data);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status === 201) {
                onSuccess(xhr, xhr.responseText);
            }
            else if (xhr.status === 400 ||
                xhr.status === 500) {
                onError(xhr);
            }
        }
    };
}
function processRequest(url, type = 'POST', data) {
    let dataString = generateDataString(data);
    let postData = "";
    let getData = "";
    if (type == 'POST' ||
        type == 'PUT')
        postData = dataString;
    else
        getData = "?" + dataString;
    let xhr = new XMLHttpRequest();
    xhr.open(type, url + getData, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhr.send(postData);
    return xhr;
}
function generateDataString(data) {
    let json = JSON.parse(data);
    let arr1 = [];
    let result;
    for (const data in json) {
        arr1.push(data + "=" + json[data]);
    }
    result = [arr1].join('&').replace(',', '&');
    return result;
}
