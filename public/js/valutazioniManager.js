const stars = document.getElementsByClassName("star");
const addValutazione = document.getElementById("addValutazione");
const updateValutazione = document.getElementById("updateValutazione");
const foto = document.getElementById("foto");
const fotoId = foto.getAttribute("data-id");
let starsCount = 0;

for(let i = 0; i < stars.length; i++){
    stars[i].addEventListener("click", () => {
        resetStars();
        for(let j = 0; j <= i; j++){
            stars[j].src = URL + "public/assets/images/star_filled.png";
            starsCount++;
        }
    });
}

function resetStars(){
    starsCount = 0;
    for(let star of stars){
        star.src = URL + "public/assets/images/star_empty.png";
    }
}

if(addValutazione){
    addValutazione.addEventListener("click", async() => {
        if(starsCount > 0){
            const data = {
                "foto_id":fotoId,
                "stelle":starsCount,
                "action":"insert"
            };
            let response = await fetch(URL + "fotografie/valuta", {
                method: "POST",
                headers: {
                    "Content-Type":"application/json"
                },
                body: JSON.stringify(data)
            });
            let json = await response.json();
            if(json.status == "SUCCESS"){
                Swal.fire({
                    title: "Valutazione aggiunta con successo!",
                    icon: "success"
                }).then(() => {
                    window.location.reload();
                });
            }else{
                Swal.fire({
                    title: "Errore nell'inserimento della valutazione",
                    icon: "error"
                });
            }
        }else{
            Swal.fire({
                title: "Impossibile aggiungere valutazione con 0 stelle!",
                icon: "error"
            });
        }
    });
}

if(updateValutazione){
    updateValutazione.addEventListener("click", async() => {
        const data = {
            "foto_id":fotoId,
            "stelle":starsCount,
            "action":"update"
        };
        let response = await fetch(URL + "fotografie/valuta", {
            method: "POST",
            headers: {
                "Content-Type":"application/json"
            },
            body: JSON.stringify(data)
        });
        let json = await response.json();
        if(json.status == "SUCCESS"){
            Swal.fire({
                title: "Valutazione aggiornata con successo!",
                icon: "success"
            }).then(() => {
                window.location.reload();
            });
        }else{
            Swal.fire({
                title: "Errore nell'inserimento della valutazione",
                icon: "error"
            });
        }
    });
}

function countStars(){
    starsCount = 0;
    for(let star of stars){
        if(star.src.includes("filled")){
            starsCount++;
        }
    }
}

if(document.getElementById("resetStarsBtn")){
    document.getElementById("resetStarsBtn").addEventListener("click" , () => {
        resetStars();
    });
}

window.onload = () => {
    countStars();
}