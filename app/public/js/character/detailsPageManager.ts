
let currentPage : number;
let firstPage : number;
let lastPage : number;

let pages : NodeListOf<Element>;

let arrowPrev : HTMLElement;
let arrowNext : HTMLElement;
let pageCounter : HTMLElement;

// TODO Page Control
//document.addEventListener("DOMContentLoaded", onLoadPage);

/**
 * Searches for details pages and assigns page numbers to each page.
 * If the page already has a page number class, it skips assigning a new one.
 *
 * @returns {void}
 */
function onLoadPage() : void {
    pages = document.querySelectorAll(".details-page");
    arrowPrev = document.querySelector("#btn-prev-page") as HTMLElement;
    arrowNext = document.querySelector("#btn-next-page") as HTMLElement;
    pageCounter = document.querySelector("#page-counter") as HTMLElement;

    firstPage = 1;
    currentPage = 1;

    for (let i = 0; i < pages.length; i++) {
        let element = pages[i];

        if (!isOnAllPages(element) &&
            !hasPageClass(element)) {
            addPageNumberToElement(element, currentPage);
            currentPage++;
        }
    }

    lastPage = pages.length - 1;
    currentPage = 1;
    switchPage(currentPage);
}

/**
 * @function nextPage
 * @description Navigates to the next page and updates the current page.
 *
 * @returns {void}
 */
function nextPage() : void {
    currentPage++;
    switchPage(currentPage);
}

/**
 * @function nextPage
 * @description Navigates to the next page and updates the current page.
 *
 * @return {void}
 */
function previousPage() : void {
    currentPage--;
    switchPage(currentPage);
}

/**
 * Switches the current page to a new page.
 *
 * @param {number} newPage - The number of the new page to switch to.
 *
 * @return {void}
 */
function switchPage(newPage : number) : void {
    if (newPage > lastPage) return;
    if (newPage < firstPage) return;

    for (let i = 0; i < pages.length; i++) {
        let element = pages[i];
        let htmlElement = element as HTMLElement;

        htmlElement.style.display = "none";

        if (isOnAllPages(element)) htmlElement.style.display = "flex";
        if (isOnPage(element, newPage)) htmlElement.style.display = "flex";
    }

    switchButtons(newPage);

    pageCounter.innerText = "Page " + newPage + " / " + lastPage;
}

/**
 * Switches the visibility of the previous and next buttons based on the specified page number.
 *
 * @param {number} newPage - The page number to determine the visibility of the buttons.
 * @return {void}
 */
function switchButtons(newPage : number) : void {
    if (newPage === firstPage) {
        arrowPrev.style.display = "none";
    } else {
        arrowPrev.style.display = "inline-block";
    }

    if (newPage === lastPage) {
        arrowNext.style.display = "none";
    } else {
        arrowNext.style.display = "inline-block";
    }
}


/**
 * Checks if the given element is on the specified page.
 *
 * @param {Element} element - The element to check.
 * @param {number} page - The page number to check against.
 * @return {boolean} Returns true if the element is on the specified page, otherwise false.
 */
function isOnPage(element : Element, page : number) : boolean {
    return element.classList.contains("page-" + page);
}

/**
 * Determines if the given element is present on all pages.
 *
 * @param {Element} element - The element to check.
 * @return {boolean} - Returns true if the element is present on all pages, otherwise false.
 */
function isOnAllPages(element : Element) : boolean {
    return element.classList.contains("page-all");
}

/**
 * Checks if the given element has a class that starts with "page-".
 *
 * @param {Element} element - The element to check.
 * @return {boolean} - True if the element has a class that starts with "page-", false otherwise.
 */
function hasPageClass(element : Element) : boolean {
    for (let i = 0; i < element.classList.length; i++) {
        if (element.classList[i].indexOf("page-") !== -1) return true;
    }
    return false;
}

/**
 * Adds the page number as a class to the given element.
 *
 * @param {Element} element - The element to add the page number class to.
 * @param {number} page - The page number to be added as a class.
 * @return {void}
 */
function addPageNumberToElement(element : Element, page : number) : void {
    element.classList.add("page-" + page);
}