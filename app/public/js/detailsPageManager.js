var currentPage;
var firstPage;
var lastPage;
var pages;
function OnLoadPage() {
    pages = document.querySelectorAll(".details-page");
    for (var i = 0; i < pages.length; i++) {
        var element = pages[i];
        if (isOnAllPages(element))
            continue;
    }
}
function isOnAllPages(element) {
    return element.classList.contains("page-all");
}
