import { sendAjaxRequest } from "../tools/Ajax.js";
document.addEventListener("DOMContentLoaded", start);
/**
 * Adds an event listener for "change" event to all elements with class "input_edit_character".
 * When the event is triggered, the onChange function will be called.
 *
 * @returns {void}
 */
function start() {
    let elements = document.querySelectorAll(".input_edit_character");
    for (let i = 0; i < elements.length; i++) {
        elements[i].addEventListener("change", onChange);
    }
}
/**
 * Handle the change event of an input element.
 *
 * @param {Object} event - The change event object.
 * @return {void}
 */
function onChange(event) {
    let element = event.target;
    let id = element.id;
    let newValue = element.value;
    let url = element.getAttribute('data-path');
    let splitId = id.split('-');
    if (splitId.length <= 1)
        return;
    let valueId = splitId[1];
    if (valueId == "")
        return;
    if (url == null)
        return;
    let data = '{ "valueId" : "' + valueId + '" , "newValue" : "' + newValue + '" }';
    sendAjaxRequest(url, "POST", data, onSuccess, onError);
}
/**
 * Logs a successful modification of character detail.
 *
 * @param {XMLHttpRequest} xhr - The XMLHttpRequest object used for the request.
 * @param {string} response - The response received from the server.
 * @return {void}
 */
function onSuccess(xhr) {
    console.log("Successful Modified Character-Detail (timeout: " + xhr.timeout + ") " + xhr.responseText);
}
/**
 * Handles error occurred during modifying character details.
 *
 * @param {XMLHttpRequest} xhr - The XMLHttpRequest object containing error details.
 *
 * @return {void}
 */
function onError(xhr) {
    console.log("Failed To Modified Character-Detail (timeout: " + xhr.timeout + ")");
}
