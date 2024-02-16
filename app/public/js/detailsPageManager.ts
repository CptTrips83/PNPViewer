
let currentPage : number;
let firstPage : number;
let lastPage : number;

let pages : NodeListOf<Element>;

document.addEventListener("DOMContentLoaded", PageManagerOnLoadPage);

function PageManagerOnLoadPage() : void {
    pages = document.querySelectorAll(".details-page");

    for (let i = 0; i < pages.length; i++) {
        let element = pages[i];
        if (isOnAllPages(element)) continue;


    }
}

function isOnAllPages(element : Element) : boolean {
    return element.classList.contains("page-all");
}