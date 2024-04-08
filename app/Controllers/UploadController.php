<?php

namespace App\Controllers;

use App\Auth;
use App\Http\Response;
use App\Models\Actor;
use App\Models\Format;
use App\Models\Movie;
use App\Services\DataBaseClient;

class UploadController
{
    public function __construct(private DataBaseClient $dbClient)
    {}
    public function handle()
    {
        $importedCount = 0;
        $totalCount = 0;
        $existsCount = 0;

        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];

        $allowed_extensions = array("txt"); 

        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        if (!in_array($file_extension, $allowed_extensions)) {
            Response::json(['message' => 'File extension not allowed, please choose a txt file.'],422);
        }

        $max_file_size = 5 * 1024 * 1024; // 5 MB
        if ($file_size > $max_file_size) {
            Response::json(['message' => 'File size must be less than 5 MB.'],422);
        }
        
        move_uploaded_file($file_tmp, "storage/uploads/" . $file_name);
        $file_content =  file_get_contents("storage/uploads/" . $file_name);

        $movies = explode("\r\n\r\n", $file_content);

        $parsed_movies = [];

        foreach ($movies as $movie) {
            $totalCount++;
            $title = '';
            $release_year = '';
            $format = '';
            $stars = '';

            $lines = explode("\n", $movie);

            foreach ($lines as $line) {
                if (strpos($line, 'Title:') === 0) {
                    //var_dump($movie);
                    $title = trim(substr($line, strlen('Title:')));
                } elseif (strpos($line, 'Release Year:') === 0) {
                    $release_year = trim(substr($line, strlen('Release Year:')));
                } elseif (strpos($line, 'Format:') === 0) {
                    $format = trim(substr($line, strlen('Format:')));
                } elseif (strpos($line, 'Stars:') === 0) {
                    $stars = trim(substr($line, strlen('Stars:')));
                }
            }
            $parsed_movies[] = [
                'Title' => $title,
                'Release Year' => $release_year,
                'Format' => $format,
                'Stars' => $stars
            ];
        }

        foreach ($parsed_movies as $movie) {

            if($movie['Title'] == '') {
                continue;
            }

            $movieExists = Movie::query()
                ->dbClient()
                ->exists('title', $movie['Title']);

            if($movieExists) {
                $existsCount++;
                continue;
            }

            $actorsNames = explode(',', $movie['Stars']);
            
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
    
            $format = Format::query()
                ->where('title','=',$movie['Format'])
                ->get();

            if(!empty($format)) {
                $formatId = $format[0]->id;
            }   
            else {
                $format = Format::query()
                    ->create([
                        'title' => $movie['Format']
                    ]);
                $formatId = $format->id;
            }

    
            $movie = Movie::query()
            ->create([
                'title' => $movie['Title'],
                'format_id' => $formatId,
                'year' => $movie['Release Year'],
                'created_by' => Auth::id()
            ]);

            $importedCount++;
    
    
            foreach($actorsIds as $actorId) {
                $this->dbClient
                    ->table('movie_actors')
                    ->insert([
                        'movie_id' => $movie->id,
                        'actor_id' => $actorId
                    ]);
            }
        }

        if($totalCount === $existsCount) {
            Response::json(['message' => 'No movies imported. All movies already exist.'],200);
        }
       
        Response::json(['message' => "Imported $importedCount of $totalCount movies."],200);
     
    }
}
