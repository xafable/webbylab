<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid w-80">
    <a class="navbar-brand" href="#">WebbyLab</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
   
      <form class="d-flex" role="search" id="searchMovieForm">
          <select class="form-select" aria-label="Default select example" id="moviesSearchBySelect">
              <option value="movie" selected>Movie</option>
              <option value="actor">Actor</option>
          </select>
          <input class="form-control me-2 search-input" type="search" placeholder="Search" aria-label="Search" id="moviesSearchInput">
         
          <button class="btn btn-outline-success" id="searchButton">Search</button>
          <button type="button" class="btn btn-outline-danger d-none" id="clearSearchButton">X</button>
      </form>

      <a href="/logout" class="btn btn-outline-danger" id="logoutButton">Logout</a>
    </div>
  </div>
</nav>