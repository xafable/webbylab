document.addEventListener('DOMContentLoaded', initMovies);

const currentMoviesOptions = {
    orderBy: 'title',
    orderDir: 'asc',
    search: false,
    searchBy: false,
};


let modalMovieInfo = false;
let modalConfirmDelete = false;
let toast = false;



function initMovies() {

    modalMovieInfo = new bootstrap.Modal(document.getElementById('movieInfoModal'))
    modalConfirmDelete = new bootstrap.Modal(document.getElementById('confirmDeleteModal'))
    toast = new bootstrap.Toast(document.getElementById('liveToast'))


    listenClickMovieShowInfo();
    listenOrderBySelect();
    listenOrderDirSelect();
    listenSearchButton();
    listenClearSearchButton();

    listenClickDeleteMovie();
    listenClickAddMovie();
    listenClickUploadMovie();
    listenLogout();
}

async function loadMovies(userParams = false) {

    let ulrParams = `?orderBy=${currentMoviesOptions.orderBy}&orderDir=${currentMoviesOptions.orderDir}`;

    if(currentMoviesOptions.search) {
        ulrParams += `&search=${currentMoviesOptions.search}&searchBy=${currentMoviesOptions.searchBy}`;
    }

    const resultMovies = await requestGetMovies(ulrParams);

    if(resultMovies.error) {
        console.log(resultMovies.error);
        return;
    }

    renderMovieItems(resultMovies.success.movies);

    console.log('movies Ressss',resultMovies);

    listenClickMovieShowInfo();
    listenClickDeleteMovie();
    

}

function renderMovieItems(movies) {
    let _html = '';

    movies.forEach((movie) => {
        _html += renderMovieItem(movie);
    });

    document.querySelector('[data-movies-list]').innerHTML = _html;
}

function renderMovieItem(item) {
    let _html = `<div class='card w-100 mb-3'>
    <div class='card-body'>
        <h5 class='card-title'>${item.title}</h5>
        <p class='card-text'>${item.year}</p>

        <button type='button' class='btn btn-outline-light' data-movie-show-info data-movie-id='${item.id}'>Show
            info</button>
        <button type='button' class='btn btn-outline-danger 'data-movie-delete data-movie-id='${item.id}'>Delete</button>
    </div>
    </div>`;

    return _html;
}
function listenClickMovieShowInfo() {
    document.querySelectorAll('[data-movie-show-info]').forEach((el) => {
        el.addEventListener('click', (event) => {
            console.log('clicked');
            event.preventDefault();

            const id = event.currentTarget.getAttribute('data-movie-id');

            showMovieInfo(id);

            
        });
    });
}

function listenOrderBySelect() {
    document.querySelector('#moviesOrderBySelect').addEventListener('change', (event) => {
        console.log(event.currentTarget.value);
        currentMoviesOptions.orderBy = event.currentTarget.value;
        loadMovies();
    })
}

function listenOrderDirSelect() {
    document.querySelector('#moviesOrderDirSelect').addEventListener('change', (event) => {
        console.log(event.currentTarget.value);
        currentMoviesOptions.orderDir = event.currentTarget.value;
        loadMovies();
    })
}

function listenSearchButton() {
    document.querySelector('#searchButton').addEventListener('click', (event) => {
        event.preventDefault();

        currentMoviesOptions.search = document.querySelector('#moviesSearchInput').value;
        currentMoviesOptions.searchBy = document.querySelector('#moviesSearchBySelect').value;

        document.querySelector('#clearSearchButton').classList.remove('d-none');

        loadMovies(true);
    })
}

function listenClearSearchButton() {
    document.querySelector('#clearSearchButton').addEventListener('click', (event) => {
        event.preventDefault();

        currentMoviesOptions.search = false;
        currentMoviesOptions.searchBy = false;

        document.querySelector('#moviesSearchInput').value = '';
        document.querySelector('#moviesSearchBySelect').value = 'all';
        document.querySelector('#clearSearchButton').classList.add('d-none');

        loadMovies();
    });
}

async function showMovieInfo(id){

    const movieResult = await requestGetMovie(id);
    console.log('movie', movieResult);

    if(movieResult.error){
        console.log(movieResult.error);
        return;
    }

    let movieData = movieResult.success.movie;

    let actorsHtml = '';

    movieData.actors?.forEach((actor) => {
        actorsHtml += `<li class="list-group-item">${actor.name}</li>`;
    });

    console.log(movieData);

    document.querySelector('#movieInfoModal [data-movie-actors-list]').innerHTML = actorsHtml;

    document.querySelector('#movieInfoModal [data-movie-id]').value = movieData.id;
    document.querySelector('#movieInfoModal [data-movie-title]').value = movieData.title;
    document.querySelector('#movieInfoModal [data-movie-year]').value = movieData.year;
    document.querySelector('#movieInfoModal [data-movie-format]').value = movieData.format_tile;

    modalMovieInfo.show();

  
}

function listenClickDeleteMovie() {
    document.querySelectorAll('[data-movie-delete]').forEach((el) => {
        el.addEventListener('click', (event) => {
            event.preventDefault();

            console.log('delete movie');
            const id = event.currentTarget.getAttribute('data-movie-id');

            modalConfirmDelete.show();

            document.querySelector('#confirmDeleteModal .confirm').addEventListener('click', async (event) => {

                const deleteResult = await requestDeleteMovie(id);

                if(deleteResult.error) {
                    console.log(deleteResult.error);

                    modalConfirmDelete.hide();
                    return;
                }

                console.log(deleteResult.success);

                modalConfirmDelete.hide();
                loadMovies();
                showToast('Movie deleted');

            });
           
        });
    });
}

function listenClickAddMovie() {
    document.querySelector('#addMovieButton').addEventListener('click', (event) => {
        initAddMovie();
    });
}

function listenClickUploadMovie() {
    document.querySelector('#uploadFileButton').addEventListener('click', (event) => {
        initUploadMovies();
    });
}

function listenLogout() {
    document.querySelector('#logoutButton').addEventListener('click', async (event) => {
        event.preventDefault();
        let res = await requestLogout();

        if(res.success){
            window.location.href = '/login';
        }

        else{
            console.log(res.error);
        }
    });
}

async function requestLogout() {
    const url = `/logout`;

    return await pRequest(url, 'POST');
        
}
async function requestGetMovie(id){
    const url = `/movie?id=${id}`;

    return await pRequest(url);
}

async function requestGetMovies(params = ''){
    const url = `/movies${params}`;

    return await pRequest(url);
}

async function requestCreateMovie(data){
    const url = `/movies`;
    return await pRequest(url, 'POST', data);
}

async function requestDeleteMovie(id){
    const url = `/movies`;
    return await pRequest(url, 'DELETE', {id: id});
}

async function pRequest (path, type = 'GET', data = {}, isFormData = false) {


    if (isFormData) {
        headers = {}
        //data.append('_token', csrfToken)
    } else {
        headers = {
            Accept: isFormData ? null : 'application/json',
            'Content-Type': 'application/json',
        }
        data = JSON.stringify(data)
    }

    const options = {
        method: type,
        headers: headers
    }

    if (type !== 'GET') {
        options.body = data
    }


    try {
        let response = await fetch(path, options)

        let result = '';
        try {
             result = await response.json()
        }
        catch (error) {
             console.error(error)
        }
        

        console.log('result', result)

        if (!response.ok) {
            throw new Error(`${result.message}`, { cause: response })
        } else {
            return {
                success: result
            }
        }
    } catch (error) {

        console.error(error)

        return {
            error: error
        }
    }
    
}

function showToast(message) {
    //toast.hide();
    document.querySelector('#liveToast .toast-body').textContent = message;
    toast.show();
}
