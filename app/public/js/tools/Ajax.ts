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
export function sendAjaxRequest(
    url : string,
    type : string = "POST",
    data : string,
    onSuccess : (xhr : XMLHttpRequest) => void,
    onError : (xhr : XMLHttpRequest) => void
) : void {

    let xhr: XMLHttpRequest;
    try {
        xhr = processRequest(
            url,
            type,
            data
        );
    } catch (e) {
        throw e;
    }

    xhr.onreadystatechange = function () {
        try {
            if (xhr.readyState == 4) {
                if (xhr.status >= 200 &&
                    xhr.status <= 299) {
                    onSuccess(
                        xhr
                    );
                } else if (xhr.status === 400 ||
                    xhr.status === 500) {
                    onError(xhr);
                }
            }
        } catch (e){
            if (typeof e === "string") console.error(e);
            else if (e instanceof Error) console.error(e.message);
        }
    }
}


/**
 * Sends an HTTP request to the specified URL with the given data.
 *
 * @param {string} url - The URL to send the request to.
 * @param {string} [type='POST'] - The type of HTTP request to send (either 'POST', 'PUT', or any other valid HTTP method).
 * @param {string} data - The data to send with the request.
 *
 * @return {XMLHttpRequest} - The XMLHttpRequest object that represents the ongoing request.
 */
function processRequest(
    url : string,
    type : string = 'POST',
    data : string
    ) : XMLHttpRequest {

    let dataString : string;

    try {
        dataString = generateDataString(data);
    } catch (e){
        throw e;
    }

    let postData : string = "";
    let getData : string = "";

    if (type == 'POST' ||
        type =='PUT')
        postData = dataString;
    else
        getData = "?" + dataString;

    let xhr : XMLHttpRequest = new XMLHttpRequest()
    xhr.open(type, url + getData, true)
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8')
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.send(postData);

    return xhr;

}

/**
 * Generates a data string by converting a JSON object to a query string.
 * Each key-value pair in the JSON object is converted to the format `key=value`.
 * The resulting key-value pairs are joined with ampersand (&) separators to form the final data string.
 *
 * @param {string} data - The JSON data object to be converted to a data string.
 * @return {string} - The generated data string.
 */
function generateDataString(data : string) : string {
    let result : string;

    try{
        let json = JSON.parse(data)
        let arr1 : any[] = [];

        for (const data in json) {
            arr1.push(data + "=" + json[data]);
        }
        result = [arr1].join('&').replace(',', '&');
    } catch (e) {
        throw e;
    }

    return result;
}