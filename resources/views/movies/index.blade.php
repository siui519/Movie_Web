<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('movies.movie_list') }} - {{ __('movies.title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .movie-card {
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            height: 100%;
        }
        .movie-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .favorite-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }
        .search-box {
            max-width: 800px;
            margin: 20px auto;
        }
        .n-a-text {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('movies.index') }}">
                <i class="fas fa-film me-2"></i>{{ __('movies.title') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('movies.index') }}">
                            <i class="fas fa-list me-1"></i>{{ __('movies.movie_list') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('favorites.index') }}">
                            <i class="fas fa-heart me-1"></i>{{ __('movies.favorites') }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-language me-1"></i>{{ app()->getLocale() == 'id' ? '🇮🇩 ID' : '🇺🇸 EN' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('lang.switch', 'id') }}">
                                    <i class="fas fa-flag me-2"></i>🇮🇩 Bahasa Indonesia
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">
                                    <i class="fas fa-flag me-2"></i>🇺🇸 English
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">
                                <i class="fas fa-sign-out-alt me-1"></i>{{ __('auth.logout') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Search Form -->
    <div class="container mt-4 search-box">
        <form action="{{ route('movies.search') }}" method="GET" class="card p-3">
            <div class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="query" class="form-control" 
                           placeholder="{{ __('movies.search_placeholder') }}" value="{{ request('query') }}" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="year" class="form-control" 
                           placeholder="{{ __('movies.year') }}" value="{{ request('year') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">{{ __('movies.all_types') }}</option>
                        <option value="movie" {{ request('type') == 'movie' ? 'selected' : '' }}>{{ __('movies.movie') }}</option>
                        <option value="series" {{ request('type') == 'series' ? 'selected' : '' }}>{{ __('movies.series') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>{{ __('movies.search') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Movies Grid -->
    <div class="container mt-4">
        <div class="row">
            @forelse($movies as $movie)
                <div class="col-md-3 mb-4">
                    <div class="card movie-card h-100">
                        <div class="favorite-btn">
                            <button class="btn btn-sm btn-danger favorite-toggle" 
                                    data-id="{{ $movie['imdbID'] }}"
                                    data-title="{{ $movie['Title'] }}"
                                    data-year="{{ $movie['Year'] }}"
                                    data-type="{{ $movie['Type'] }}"
                                    data-poster="{{ $movie['Poster'] }}">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger favorite-remove" 
                                    data-id="{{ $movie['imdbID'] }}"
                                    style="display:none;">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        
                        @if($movie['Poster'] && $movie['Poster'] !== 'N/A')
                            <img src="{{ $movie['Poster'] }}" 
                                 class="card-img-top" alt="{{ $movie['Title'] }}"
                                 style="height: 350px; object-fit: cover;">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center" 
                                 style="height: 350px; background: #e9ecef;">
                                <i class="fas fa-film fa-4x text-secondary"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($movie['Title'], 40) }}</h5>
                            <p class="card-text text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $movie['Year'] }}
                            </p>
                            <p class="card-text">
                                <small class="text-muted">
                                    <strong>{{ __('movies.type_label') }}:</strong> {{ ucfirst($movie['Type']) }}
                                </small>
                            </p>
                            <a href="{{ route('movies.show', $movie['imdbID']) }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-film fa-3x text-muted mb-3"></i>
                    <p class="text-muted">{{ __('movies.no_movies_found') }}</p>
                    <p class="text-muted">{{ __('movies.try_another_keyword') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load favorites dari localStorage
            checkFavoritesStatus();

            $('.favorite-toggle').click(function() {
                var button = $(this);
                var movieId = button.data('id');
                var movieData = {
                    imdbID: movieId,
                    Title: button.data('title'),
                    Year: button.data('year'),
                    Type: button.data('type'),
                    Poster: button.data('poster')
                };
                
                // Simpan ke localStorage
                var favorites = JSON.parse(localStorage.getItem('movie_favorites') || '[]');
                favorites.push(movieData);
                localStorage.setItem('movie_favorites', JSON.stringify(favorites));
                
                // Update UI
                button.hide();
                button.siblings('.favorite-remove').show();
                
                alert('{{ __('movies.added_to_favorites') }}');
            });

            $('.favorite-remove').click(function() {
                var button = $(this);
                var movieId = button.data('id');
                
                if (confirm('{{ __('movies.confirm_remove') }}')) {
                    // Hapus dari localStorage
                    var favorites = JSON.parse(localStorage.getItem('movie_favorites') || '[]');
                    favorites = favorites.filter(function(favorite) {
                        return favorite.imdbID !== movieId;
                    });
                    localStorage.setItem('movie_favorites', JSON.stringify(favorites));
                    
                    // Update UI
                    button.hide();
                    button.siblings('.favorite-toggle').show();
                }
            });
        });

        function checkFavoritesStatus() {
            var favorites = JSON.parse(localStorage.getItem('movie_favorites') || '[]');
            
            $('.favorite-toggle').each(function() {
                var movieId = $(this).data('id');
                var isFavorite = favorites.some(function(fav) {
                    return fav.imdbID === movieId;
                });
                
                if (isFavorite) {
                    $(this).hide();
                    $(this).siblings('.favorite-remove').show();
                }
            });
        }
    </script>
</body>
</html>