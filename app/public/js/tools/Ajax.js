/**
 * Sends an Ajax request to the specified URL.
 *
 * @param {string} url - The URL to send the request to.
 * @param {string} [type="POST"] - The type of request to send. Defaults to "POST".
 * @param {SuccessCallback<any>} onSuccess - The function to be executed if the request is successful.
 * @param {Callback<any>} onError - The function to be executed if an error occurs during the request.
 */
function sendAjaxRequest(url, type, onSuccess, onError) {
    if (type === void 0) { type = "POST"; }
    $.ajax({
        url: url,
        type: type,
        dataType: 'json',
        async: true,
        success: onSuccess,
        error: function (xhr, textStatus, errorThrown) {
            if (onError)
                onError(xhr, textStatus, errorThrown);
            else
                console.warn("No error Callback on Ajax-Function to URL " + url);
        }
    });
}
