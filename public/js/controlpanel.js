const approveBtns = document.getElementsByClassName("approveBtn");
const denyBtns = document.getElementsByClassName("denyBtn");

for(let btn of approveBtns){
    btn.addEventListener("click", async() => {
       let id = btn.getAttribute("data-requestId");
       let response = await approveRequest(id);
       if(response.status == "SUCCESS"){
           Swal.fire({
               title: "Richiesta approvata con successo!",
               icon: "success"
           }).then(() => {
               window.location.reload();
           });
       }else{
           Swal.fire({
               title: "Errore nell'approvazione della richiesta!",
               icon: "error"
           });
       }
    });
}

for(let btn of denyBtns){
    btn.addEventListener("click", async() => {
        let id = btn.getAttribute("data-requestId");
        let response = await denyRequest(id);
        if(response.status == "SUCCESS"){
            Swal.fire({
                title: "Richiesta rifiutata con successo!",
                icon: "success"
            }).then(() => {
                window.location.reload();
            });
        }else{
            Swal.fire({
                title: "Errore nel rifiuto della richiesta!",
                icon: "error"
            });
        }
    });
}

async function approveRequest(id){
    const data = {
        "id": id,
        "action": "approve"
    }
    let response = await fetch(URL + "controlpanel/managerequest", {
       method: "POST",
       headers: {
           "Content-Type": "application/json"
       },
       body: JSON.stringify(data)
    });
    let json = await response.json();
    return json;
}

async function denyRequest(id){
    const data = {
        "id": id,
        "action": "deny"
    }
    let response = await fetch(URL + "controlpanel/managerequest", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    });
    let json = await response.json();
    return json;

}