import { sendAjaxRequest } from "../tools/Ajax.js";
document.addEventListener("DOMContentLoaded", start);
function start() {
    let elements = document.querySelectorAll(".input_edit_character");
    for (let i = 0; i < elements.length; i++) {
        elements[i].addEventListener("change", onChange);
    }
}
function onChange(event) {
    let element = event.target;
    let id = element.id;
    let newValue = element.value;
    let url = element.getAttribute('data-path');
    let valueId = id.split('-')[1];
    if (url == null)
        return;
    let data = '{ "valueId" : "' + valueId + '" , "newValue" : "' + newValue + '" }';
    sendAjaxRequest(url, "POST", data, onSuccess, onError);
}
function onSuccess(xhr, response) {
    console.log("Successful Modified Character-Detail (timeout: " + xhr.timeout + ") " + response);
}
function onError(xhr) {
    console.log("Failed To Modified Character-Detail (timeout: " + xhr.timeout + ")");
}
