


let modalUploadMovie = false;
let file = false;

document.addEventListener('DOMContentLoaded', function () {

    listenConfirmUploadButton();
    listenFileSelect();
});

function initUploadMovies() {

    console.log('initUploadMovies.js');

    if(!modalUploadMovie) {
        modalUploadMovie = new bootstrap.Modal(document.getElementById('uploadMovieModal'));
    }

    modalUploadMovie.show();

}



function listenConfirmUploadButton() {

    document.querySelector('#uploadMovieModal .confirm').addEventListener('click',async (event) => {
        
        event.preventDefault();

      
        const maxSize = 1024 * 1024; 
        if (file.size > maxSize) {
            showToast('File size exceeds the limit of 1MB.');
            return;
        }

  
        const allowedFormats = ['text/plain']; // Allowed formats
        if (!allowedFormats.includes(file.type)) {
            showToast('Invalid file format. Please select a file in one of the following formats: .txt, .pdf, .jpg, .png');
            return;
        }


        const uploadResult = await requestUpload(file);


        if(uploadResult.error) {
            console.log(createResult.error);
            modalUploadMovie.hide();
            return;
        }

        console.log(uploadResult.success);
        modalUploadMovie.hide();
        
        loadMovies();
        _clearInputs();

        showToast(uploadResult.success.message);    



    });
}

function listenFileSelect() {
    document.querySelector('#uploadMovieModal [data-movies-file]').addEventListener('change', (event) => {
        console.log(event.currentTarget.value);
        file = event.currentTarget.files[0];
        console.log(file);
    });
}


async function requestUpload(file) {
    const url = '/upload';

    const formData = new FormData();
    formData.append('file', file);

    return await pRequest(url, 'POST', formData, true);
}
function _clearInputs(){

    document.querySelector('#uploadMovieModal [data-movies-file]').value = '';
    file = false;

}