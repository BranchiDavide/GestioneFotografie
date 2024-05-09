const deleteIcons = document.getElementsByClassName("deleteCommentIcon");
const editIcons = document.getElementsByClassName("editCommentIcon");

for(let icon of deleteIcons){
    icon.addEventListener("click", () => {
        Swal.fire({
            title: "Sei sicuro di eliminare il commento?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Annulla",
            confirmButtonText: "Si"
        }).then(async(result) => {
            if (result.isConfirmed) {
                const data = {
                    "id":icon.getAttribute("data-commentId")
                };
                let response = await fetch(URL + "fotografie/eliminacommento", {
                   method: "POST",
                   headers: {
                       "Content-Type":"application/json"
                   },
                   body: JSON.stringify(data)
                });
                let json = await response.json();
                if(json.status == "SUCCESS"){
                    Swal.fire({
                        title: "Commento eliminato con successo!",
                        icon: "success"
                    }).then(() => {
                        window.location.reload();
                    });
                }else{
                    Swal.fire({
                        title: "Errore nell'eliminazione del commento!",
                        icon: "error"
                    });
                }
            }
        });
    });
}

for(let icon of editIcons){
    icon.addEventListener("click", () => {
        Swal.fire({
            title: 'Modifica commento',
            html: `<textarea id="updateCommentTextArea" class="form-control" maxlength="500" placeholder="Inserisci testo" style="height: 100px">${icon.nextElementSibling.nextElementSibling.textContent}</textarea>
                    <div id="fileHelp" class="form-text">Max. 500 caratteri</div>`,
            showCancelButton: true,
            confirmButtonText: 'Modifica',
            cancelButtonText: 'Annulla',
            preConfirm: async() => {
                const contenuto = document.getElementById('updateCommentTextArea').value;
                if(contenuto.trim().length == 0){
                    Swal.fire({
                        title: "Il commento non può essere vuoto!",
                        icon: "error"
                    });
                    return;
                }
                const data = {
                    "id":icon.getAttribute("data-commentId"),
                    "contenuto":contenuto
                };
                let response = await fetch(URL + "fotografie/modificacommento", {
                    method: "POST",
                    headers: {
                        "Content-Type":"application/json"
                    },
                    body: JSON.stringify(data)
                });
                let json = await response.json();
                if(json.status == "SUCCESS"){
                    Swal.fire({
                        title: "Commento modificato con successo!",
                        icon: "success"
                    }).then(() => {
                        window.location.reload();
                    });
                }else{
                    Swal.fire({
                        title: "Errore nella modifica del commento!",
                        icon: "error"
                    });
                }
            }
        });
    });
}