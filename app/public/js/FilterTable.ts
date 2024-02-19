function filterTable(inputFieldId : string,
                     tableId : string,
                     tableFilterIndex : number) : void {
    let input : HTMLSelectElement;
    let filter : string;
    let table : HTMLElement;
    let tr : HTMLCollectionOf<HTMLTableRowElement>;
    let td : HTMLElement;
    let i : number;

    input = document.getElementById(inputFieldId) as HTMLSelectElement;
    filter = input.options[input.selectedIndex].value.toUpperCase();
    table = document.getElementById(tableId) as HTMLElement;
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[tableFilterIndex];
        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}