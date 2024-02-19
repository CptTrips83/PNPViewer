import {sendAjaxRequest} from "../tools/Ajax.js";

document.addEventListener("DOMContentLoaded", start);

function start() : void {
    let elements : NodeListOf<Element> = document.querySelectorAll(".input_edit_character");

    for(let i = 0; i < elements.length; i++) {
        elements[i].addEventListener("change", onChange);
    }
}

function onChange(event : any) : void {
    let element = event.target as HTMLInputElement;

    let id = element.id;
    let newValue = element.value;
    let url = element.getAttribute('data-path');

    let valueId = id.split('-')[1];

    if (url == null) return;

    let data = '{ "valueId" : "' + valueId + '" , "newValue" : "' + newValue + '" }';

    sendAjaxRequest(url, "POST", data, onSuccess, onError);

}

function onSuccess(
    xhr : XMLHttpRequest,
    response : string
) : void
{
    console.log("Successful Modified Character-Detail (timeout: " + xhr.timeout + ") " + response);
}

function onError(
    xhr : XMLHttpRequest
) : void
{
    console.log("Failed To Modified Character-Detail (timeout: " + xhr.timeout + ")");
}



