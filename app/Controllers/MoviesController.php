<?php

namespace App\Controllers;

use App\Auth;
use App\Http\Request;
use App\Http\Response;
use App\Models\Actor;
use App\Models\Movie;
use App\Services\DataBaseClient;

class MoviesController
{


    public function __construct(private DataBaseClient $dbClient)
    {}

  
    public function index(Request $request)
    {

        $orderBy = 'title';
        $orderDir = 'asc';

        if($request->has('orderBy')){
            $orderBy = $request->orderBy;
            $orderDir = $request->orderDir;
        }

       
        if($request->has('search')) {

            if($request->searchBy == 'movie') {            
                
                $movies = Movie::getByTitle($request->search, $orderBy, $orderDir);

            }

            if($request->searchBy == 'actor') {

                $movies = Movie::getByActor($request->search, $orderBy, $orderDir);
                    
            }

            if($request->searchBy == 'all') {

                $movies = Movie::getByMovieOrActor($request->search, $orderBy, $orderDir);
            }

        }
        else {
            $movies = Movie::query()->orderBy($orderBy, $orderDir);
            $movies = $movies->get();
        }


        if($request->wantsJson) {

            $tmpMovies = [];
            foreach($movies as $movie) {
                $tmpMovies[] = $movie->toArray();
            }

            Response::json([
                'message' => 'Movies fetched',
                'movies' => $tmpMovies
            ]);
        }   
        else {
            Response::View('movies', ['movies' => $movies]);
        }
        
    }

    public function show(Request $request)
    {
        $movieId = $request->id;

        $movie = Movie::query()
            ->select(['movies.*', 'formats.title as format_tile'])
            ->join('formats', 'movies.format_id','formats.id')
            ->where('movies.id', '=', $movieId)
            ->get()[0];
        
            
        $actors = Actor::query()
            ->select(['actors.*'])
            ->join('movie_actors', 'actors.id', 'movie_actors.actor_id')
            ->where('movie_actors.movie_id', '=', $movieId)
            ->get();    

        $tmpActors = [];
        foreach($actors as $actor) {
            $tmpActors[] = $actor->toArray();
        } 

        $movie->actors = $tmpActors;

        Response::json([
            'message' => 'Movie fetched',
            'movie' => $movie->toArray(),
        ]);    
        
    }


    public function create(Request $request)
    {
        $actorsNames = $request->actors;



        $movieExists = Movie::query()
            ->dbClient()
            ->exists('title', $request->title);

        
        if($movieExists) {
            Response::json([
                'message' => 'Movie already exists'
            ], 400);
        }   


        $actorsIds = [];
        foreach($actorsNames as $actorName) {
            $actorId = Actor::query()
                ->dbClient()
                ->exists('name', $actorName);

            if(!$actorId) {
                $actor = Actor::query()
                    ->create([
                        'name' => $actorName
                    ]);

                $actorsIds[] = $actor->id;   
            }   
            else {
                $actorsIds[] = $actorId;
            }
        }


        $movie = Movie::query()
        ->create([
            'title' => $request->title,
            'format_id' => $request->format_id,
            'year' => $request->year,
            'created_by' => Auth::id()
        ]);

      

        foreach($actorsIds as $actorId) {
            $this->dbClient
                ->table('movie_actors')
                ->insert([
                    'movie_id' => $movie->id,
                    'actor_id' => $actorId
                ]);
        }
       

        Response::json([
            'message' => 'Movie created',
            'movie' => $movie
        ]);
    }

    public function delete(Request $request)
    {

        $movieId = $request->id;

        $maResult = $this->dbClient
            ->table('movie_actors')
            ->where('movie_id', '=', $movieId)
            ->delete();

        $mResult = Movie::query()
            ->where('id', '=', $movieId)
            ->delete();
            

        if($mResult && $maResult) {
            Response::json([
                'message' => 'Movie deleted'
            ]);
        }
        else {
            Response::json([
                'message' => 'Movie not deleted'
            ]);
        }   

    }
}