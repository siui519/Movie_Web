<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class FavoriteController extends Controller
{
    protected $apiKey = '64b99a18';
    protected $baseUrl = 'http://www.omdbapi.com';

    public function index()
    {
        $favoriteIds = Session::get('favorites', []);
        $favorites = [];
        
        if (!empty($favoriteIds)) {
            $favorites = $this->getFavoriteMovies($favoriteIds);
        }
        
        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request, $id)
    {
        $favorites = Session::get('favorites', []);
        
        if (!in_array($id, $favorites)) {
            $favorites[] = $id;
            Session::put('favorites', $favorites);
            
            return response()->json([
                'success' => true,
                'message' => 'Movie berhasil ditambahkan ke favorites'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Movie sudah ada di favorites'
        ]);
    }

    public function destroy($id)
    {
        $favorites = Session::get('favorites', []);
        $favorites = array_filter($favorites, function($favoriteId) use ($id) {
            return $favoriteId != $id;
        });
        
        Session::put('favorites', array_values($favorites));
        
        return redirect()->route('favorites.index')
            ->with('success', 'Movie berhasil dihapus dari favorites');
    }

    protected function getFavoriteMovies($ids)
    {
        $movies = [];
        
        foreach ($ids as $id) {
            try {
                $response = Http::get($this->baseUrl, [
                    'apikey' => $this->apiKey,
                    'i' => $id,
                    'plot' => 'short',
                    'r' => 'json'
                ]);
                
                $data = $response->json();
                
                if (isset($data['Response']) && $data['Response'] === 'True') {
                    $movies[] = $data;
                }
            } catch (\Exception $e) {
                // Skip if error
            }
        }
        
        return $movies;
    }
}