


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

        if(title.length === 0) {
            showToast('Add title');
            document.querySelector('#addMovieModal [data-movie-title]').focus();
            return;
        }
        if(actors.length === 0) {
            showToast('Add at least one actor');
            document.querySelector('#addMovieModal [data-movie-actor]').focus();
            return;

        }
        if(year.length === 0) {
            showToast('Add year');
            document.querySelector('#addMovieModal [data-movie-year]').focus();
            return;
        }


        const createResult = await requestCreateMovie({
            title: title,
            year: year,
            format_id: formatId,
            actors: actors
        });


        if(createResult.error) {
            console.log(createResult.error);
            modalAddMovie.hide();
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