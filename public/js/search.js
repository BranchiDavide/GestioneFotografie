const searchInput = document.getElementById("searchInput");
const catalogoDiv = document.getElementsByClassName("catalogoDiv")[0];

searchInput.addEventListener("keyup", async() => {
    if(getFilterData().length > 0){
        const data = {
            "filters": getFilterData(),
            "value": searchInput.value.trim()
        };
        let response = await fetch(URL + "fotografie/search", {
            method: "POST",
            headers: {
                "Content-Type":"application/json"
            },
            body: JSON.stringify(data)
        });
        let json = await response.json();
        if(json.status == "SUCCESS"){
            loadCatalogo(json);
        }else{
            Swal.fire({
                title: "Errore nella ricerca!",
                icon: "error"
            });
        }
    }else{
        Swal.fire({
            title: "Impossibile effettuare la ricerca senza filtri!",
            icon: "error"
        });
    }
});

function getFilterData(){
    let filterCheckboxes = document.querySelectorAll("input[name='fields[]']:checked");
    let fieldsNames = [];
    for(let checkbox of filterCheckboxes){
        fieldsNames.push(checkbox.value);
    }
    return fieldsNames;
}

function loadCatalogo(json){
    catalogoDiv.innerHTML = "";
    let out = "";
    for(let item of json.data){
        let html = `
         <div class="card col-md-3 col-sm-12">
            <a href="${URL}fotografie/dettagli/${item.id}" class="text-decoration-none">
                <img class="card-img-top" src="${URL}${item.path}" alt="Card image cap">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Data: </strong>${item.data_ora}</li>
                    <li class="list-group-item"><strong>Luogo: </strong>${item.luogo}</li>
                </ul>
            </a>
        </div>
        `;
        out += html;
    }
    if(out.length == 0){
        out += `<p align="center">Nessuna fotografia trovata!</p>`;
    }
    catalogoDiv.innerHTML = out;
}