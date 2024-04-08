


let modalAddMovie = false;

document.addEventListener('DOMContentLoaded', function () {
    listenAddActorButton();
    listenConfirmAddButton();
});

function initAddMovie() {

    console.log('addMovie.js');

    if(!modalAddMovie) {
        modalAddMovie = new bootstrap.Modal(document.getElementById('addMovieModal'));
    }

    modalAddMovie.show();

}

function listenAddActorButton() {

    document.querySelector('#addActorButton').addEventListener('click', (event) => {
        console.log('add actor', document.querySelector('#addMovieModal [data-movie-actors-list]').innerHTML);
      
        
        let listElement = document.querySelector('#addMovieModal [data-movie-actors-list]');

        var input = document.createElement('li');
        input.classList.add('list-group-item');
        input.innerHTML = '<input type="text" class="form-control" data-movie-actor placeholder="Actor name" value="">';
        
        // Append the new element to the target element
        listElement.appendChild(input);
    });
}

function listenConfirmAddButton() {

    document.querySelector('#addMovieModal .confirm').addEventListener('click',async (event) => {
        
        event.preventDefault();

        const title = document.querySelector('#addMovieModal [data-movie-title]').value;
        const year = document.querySelector('#addMovieModal [data-movie-year]').value;
        const formatId = document.querySelector('#addMovieModal [data-movie-format]').value;
        const actorsElements = document.querySelectorAll('#addMovieModal [data-movie-actor]');

        
        let actors = [];
        actorsElements.forEach((el) => {
            if(el.value.length > 0) actors.push(el.value);
        });

        if(title.trim().length === 0) {
            showToast('Add title');
            document.querySelector('#addMovieModal [data-movie-title]').focus();
            return;
        }

        if(year.trim().length === 0 || year < 1850 || year > 2030) {
            showToast('Valid date range 1850 - 2030');
            document.querySelector('#addMovieModal [data-movie-year]').focus();
            return;
        }

        if(actors.length === 0) {
            showToast('Add at least one actor');
            document.querySelector('#addMovieModal [data-movie-actor]').focus();
            return;
        }

        let actorsValid = true;
        actorsElements.forEach((el) => {
            if(!containsOnlyValidChars(el.value.trim())) {
                showToast('Only letters and spaces allowed in actor names');
                el.focus();
                actorsValid = false;
                return;
            }});
        if(!actorsValid) {
            return;
        }

 

        const createResult = await requestCreateMovie({
            title: title.trim(),
            year: year.trim(),
            format_id: formatId,
            actors: actors
        });

        console.log('hghghgh',createResult);

        if(createResult.error) {
            console.log(createResult.error);
            showToast(createResult.error);
            //modalAddMovie.hide();
            return;
        }

        console.log(createResult.success);
        modalAddMovie.hide();
        
        loadMovies();
        clearInputs();

        showToast('Movie created');
       



    });
}


async function requestCreateMovie(data) {
    const url = '/movies';

    return await pRequest(url, 'POST', data);
}
function clearInputs(){

    document.querySelector('#addMovieModal [data-movie-title]').value = '';
    document.querySelector('#addMovieModal [data-movie-year]').value = '';
    document.querySelector('#addMovieModal [data-movie-format]').value = 1;

    document.querySelector('#addMovieModal [data-movie-actors-list]').innerHTML = `
    <li class="list-group-item">
    <input placeholder="Actor name" type="text" class="form-control" data-movie-actor value="">
    </li>`;

}

function containsOnlyValidChars(inputString) {
    let regex = /^[a-zA-Z\-]+$/;
    return regex.test(inputString);
  }