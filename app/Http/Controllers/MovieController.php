<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    protected $apiKey = '64b99a18';
    protected $baseUrl = 'http://www.omdbapi.com';

    public function index(Request $request)
    {
        // OMDb tidak punya endpoint "popular", jadi kita search dengan query umum
        // atau tampilkan beberapa movie populer secara manual
        $defaultSearches = ['action', 'comedy', 'drama', 'sci-fi', 'horror'];
        $movies = [];

        if ($request->has('lang') && in_array($request->lang, ['id', 'en'])) {
            app()->setLocale($request->lang);
            session(['locale' => $request->lang]);
        }
        
        
        // Cari beberapa movie dari kategori berbeda
        foreach ($defaultSearches as $search) {
            $results = $this->searchMoviesApi($search, null, 1);
            $movies = array_merge($movies, array_slice($results, 0, 5)); // Ambil 5 dari tiap kategori
        }
        
        // Remove duplicates
        $movies = $this->uniqueMovies($movies);
        
        return view('movies.index', compact('movies'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $year = $request->get('year');
        $type = $request->get('type'); // movie, series, episode
        
        $movies = $this->searchMoviesApi($query, $year, 1, $type);
        
        return view('movies.index', compact('movies', 'query', 'year', 'type'));
    }

    public function show($id)
    {
        $movie = $this->getMovieDetail($id);
        
        return view('movies.show', compact('movie'));
    }

    protected function searchMoviesApi($query, $year = null, $page = 1, $type = null)
    {
        try {
            $params = [
                'apikey' => $this->apiKey,
                's' => $query,
                'page' => $page,
                'r' => 'json'
            ];

            if ($year) {
                $params['y'] = $year;
            }

            if ($type) {
                $params['type'] = $type;
            }

            $response = Http::get($this->baseUrl, $params);
            $data = $response->json();

            // OMDb response structure berbeda
            if (isset($data['Search'])) {
                return $data['Search'];
            }
            
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getMovieDetail($id)
    {
        try {
            $response = Http::get($this->baseUrl, [
                'apikey' => $this->apiKey,
                'i' => $id,
                'plot' => 'full',
                'r' => 'json'
            ]);

            $data = $response->json();
            
            // OMDb returns error if not found
            if (isset($data['Response']) && $data['Response'] === 'False') {
                return null;
            }
            
            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function uniqueMovies($movies)
    {
        $unique = [];
        $ids = [];
        
        foreach ($movies as $movie) {
            if (!in_array($movie['imdbID'], $ids)) {
                $unique[] = $movie;
                $ids[] = $movie['imdbID'];
            }
        }
        
        return $unique;
    }
}