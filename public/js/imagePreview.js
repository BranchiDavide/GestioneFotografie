const previewImage = document.getElementById("previewImage");
const dataOraInput = document.getElementsByName("data-ora")[0];
document.getElementById("formFile").addEventListener("change", async(e) => {
    let file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = async function (e) {
            previewImage.src = e.target.result;
            document.getElementsByClassName("item-picture")[0].style.display = "block";

            try{
                // Lettura dati EXIF dell'immagine per ricavare data e ora
                const exifData = piexif.load(previewImage.src);
                const dateTime = exifData["0th"]["306"];
                if(dateTime){
                    dataOraInput.value = formatDateInput(dateTime);
                }
            }catch{}
        };
        reader.readAsDataURL(file);
    }
});
function formatDateInput(dateString) {
    const [date, time] = dateString.split(' ');
    const [year, month, day] = date.split(':');
    const [hour, minute, second] = time.split(':');
    const formattedDate = `${year}-${month}-${day}T${hour}:${minute}`;
    return formattedDate;
}