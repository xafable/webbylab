<?php 

namespace App\Models;

class Movie extends Model
{

    protected static $table = 'movies';

    public function __construct(protected $attributes = [])
    {}

    public static function getByTitle($title, $orderBy = 'title', $orderDir = 'asc')
    {
        return self::query()
        ->where('title', 'LIKE', '%' . $title . '%')
        ->orderBy($orderBy, $orderDir)
        ->get();
    }

    public static function getByActor($actorTitle, $orderBy = 'title', $orderDir = 'asc')
    {
        $actors = Actor::query()
            ->select(['movie_actors.movie_id'])
            ->join('movie_actors', 'actors.id', 'movie_actors.actor_id')
            ->where('name', 'LIKE', '%' . $actorTitle . '%')
            ->get();

        $movieIds = [];
        foreach ($actors as $actor) {
            $movieIds[] = $actor->movie_id;
        }

        $inString = implode(',', $movieIds);
        return self::query()
            ->where('id', 'IN',  $inString)
            ->orderBy($orderBy, $orderDir)
            ->get();
    }

    public static function getByMovieOrActor($term, $orderBy = 'title', $orderDir = 'asc')
    {
        $actors = Actor::query()
            ->select(['movie_actors.movie_id'])
            ->join('movie_actors', 'actors.id', 'movie_actors.actor_id')
            ->where('name', 'LIKE', '%' . $term . '%')
            ->get();

        $movieIds = [];
        foreach ($actors as $actor) {
            $movieIds[] = $actor->movie_id;
        }

        $inString = implode(',', $movieIds);
        return self::query()
            ->where('id', 'IN',  $inString)
            ->orWhere('title', 'LIKE', '%' . $term . '%')
            ->orderBy($orderBy, $orderDir)
            ->get();
    }
}


   