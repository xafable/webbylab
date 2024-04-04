<?php

use App\Utils;

include(__DIR__ . "/components/header.php");
?>

<body>

    <?php
    include(__DIR__ . "/components/navbar.php");
    ?>

    <div class="container">

        <div class="login-alert" role="alert">
            <?php Utils::displayFlashMessage(); ?>
        </div>

        <div class="container ">
           <button type="button" class="btn btn-outline-success" id="addMovieButton">Add new movie</button>
           <button type="button" class="btn btn-outline-info" id="uploadFileButton">Upload from file</button>

            <div class="row" >
                <div class="col-8" data-movies-list>
                <?php foreach ($data['movies'] as $movie) {
            echo "<div class='card w-100 mb-3'>
                  <div class='card-body'>
                  <h5 class='card-title'>{$movie->title}</h5>
                  <p class='card-text'>{$movie->year}</p>

                  <button type='button' class='btn btn-outline-light' data-movie-show-info data-movie-id='{$movie->id}'>Show info</button>
                  <button type='button' class='btn btn-outline-danger' data-movie-delete data-movie-id='{$movie->id}'>Delete</button>

                  </div>
                 </div>";
        }
        ?>
                </div>
        <div class="col-4">
                <div class="input-group mb-3  w-50">
            <label class="input-group-text" for="inputGroupSelect01">Order by</label>
            <select class="form-select" id="moviesOrderBySelect">
                <option value="title" selected>Title</option>
                <option value="year">Year</option>
            </select>
        </div>

        <div class="input-group mb-3  w-50">
            <label class="input-group-text" for="inputGroupSelect01">Order Dir</label>
            <select class="form-select" id="moviesOrderDirSelect">
                <option value="asc" selected>Asc</option>
                <option value="desc">Desc</option>
            </select>
        </div>
        </div>


            </div>
        </div>
        
     

 

    </div>

<!-- Modal view movie -->
<div class="modal fade" id="movieInfoModal" tabindex="-1"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" >Movie info</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <div class="mb-3">
          <label  class="form-label">ID</label>
          <input readonly type="text" class="form-control" data-movie-id >
      </div>


      <div class="mb-3">
          <label  class="form-label">Title</label>
          <input readonly type="text" class="form-control"  data-movie-title>
      </div>

      <div class="mb-3">
          <label  class="form-label">Year</label>
          <input readonly type="text" class="form-control" data-movie-year>
      </div>

      
      <div class="mb-3">
          <label  class="form-label">Format</label>
          <input readonly type="text" class="form-control" data-movie-format >
      </div>

      <label  class="form-label">Actors</label>
      <ul class="list-group" data-movie-actors-list>
          <li class="list-group-item">An item</li>
          <li class="list-group-item">A second item</li>
          <li class="list-group-item">A third item</li>
          <li class="list-group-item">A fourth item</li>
          <li class="list-group-item">And a fifth one</li>
      </ul>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- END Modal view movie -->

<!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirm delete <span>Movie</span></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger confirm">Confirm</button>
      </div>
    </div>
  </div>
</div>
<!-- END Confirm Delete Modal -->

<!-- Modal  movie Add -->
<div class="modal fade" id="addMovieModal" tabindex="-1"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" >Add movie</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


      <div class="mb-3">
          <label  class="form-label">Title</label>
          <input  type="text" class="form-control"  data-movie-title>
      </div>

      <div class="mb-3">
          <label  class="form-label">Year</label>
          <input  type="number" class="form-control" data-movie-year>
      </div>

      
      <div class="mb-3">
          <label  class="form-label">Format</label>
          <div class="input-group mb-3  w-100">
            <select class="form-select" data-movie-format>
                <option value="1" selected>VHS</option>
                <option value="2">DVD</option>
                <option value="3">Blu-ray</option>
            </select>
        </div>
      </div>

      <label  class="form-label">Actors</label> 
      <button type="button" class="btn btn-outline-light" id="addActorButton" >add field</button>
      <ul class="list-group mt-2" data-movie-actors-list>

          <li class="list-group-item">
          <input placeholder="Actor name" type="text" class="form-control" data-movie-actor value="">
          </li>


      </ul>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success confirm" >Confirm</button>
    </div>
  </div>
</div>
</div>
<!-- END Modal  movie Add  -->

<!-- Modal  movie Upload -->
<div class="modal fade" id="uploadMovieModal" tabindex="-1"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" >Upload movies</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <div class="input-group mb-3">
      <input type="file" class="form-control" data-movies-file accept="text/plain">
     </div>




      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success confirm" >Confirm</button>
    </div>
  </div>
</div>
</div>
<!-- END Modal  movie Upload  -->

    <script src="../../public/js/movies.js"></script>
    <script src="../../public/js/addMovie.js"></script>
    <script src="../../public/js/uploadMovies.js"></script>

    <?php
    include(__DIR__ . "/components/footer-data.php");
    ?>
</body>