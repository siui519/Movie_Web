<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('movies.favorites') }} - {{ __('movies.title') }}</title>
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
        .search-box {
            max-width: 800px;
            margin: 20px auto;
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
                        <a class="nav-link active" href="{{ route('favorites.index') }}">
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

    <!-- Favorites Content -->
    <div class="container mt-4">
        <h2 class="mb-4">
            <i class="fas fa-heart text-danger me-2"></i>{{ __('movies.favorite_movies') }}
        </h2>
        
        <div id="favorites-container">
            <div class="row" id="favorites-grid">
                <!-- Favorites akan di-load via JavaScript -->
            </div>
            
            <div id="empty-favorites" class="text-center py-5" style="display: none;">
                <i class="fas fa-heart-broken fa-3x text-muted mb-3"></i>
                <p class="text-muted">{{ __('movies.no_favorites') }}</p>
                <a href="{{ route('movies.index') }}" class="btn btn-primary">
                    <i class="fas fa-film me-1"></i>{{ __('movies.search_movies') }}
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        $(document).ready(function() {
            loadFavorites();
        });

        function loadFavorites() {
            const favorites = JSON.parse(localStorage.getItem('movie_favorites') || '[]');
            const grid = $('#favorites-grid');
            const empty = $('#empty-favorites');

            grid.empty();

            if (favorites.length === 0) {
                empty.show();
                return;
            }

            empty.hide();

            // Load details untuk setiap favorite
            favorites.forEach(favorite => {
                loadMovieCard(favorite.imdbID, grid);
            });
        }

        function loadMovieCard(imdbID, container) {
            axios.get(`http://www.omdbapi.com/?apikey=64b99a18&i=${imdbID}`)
                .then(response => {
                    const movie = response.data;
                    if (movie.Response === 'True') {
                        const card = createMovieCard(movie);
                        container.append(card);
                    }
                })
                .catch(error => {
                    console.error('Error loading movie:', error);
                });
        }

        function createMovieCard(movie) {
            const poster = movie.Poster && movie.Poster !== 'N/A' 
                ? `<img src="${movie.Poster}" class="card-img-top" alt="${movie.Title}" style="height: 350px; object-fit: cover;">`
                : `<div class="card-img-top d-flex align-items-center justify-content-center" style="height: 350px; background: #e9ecef;">
                    <i class="fas fa-film fa-4x text-secondary"></i>
                  </div>`;

            return `
                <div class="col-md-3 mb-4">
                    <div class="card movie-card h-100">
                        <div class="position-absolute top-0 end-0 p-2">
                            <button class="btn btn-sm btn-outline-danger remove-favorite" data-id="${movie.imdbID}">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        ${poster}
                        <div class="card-body">
                            <h5 class="card-title">${movie.Title.length > 40 ? movie.Title.substring(0, 40) + '...' : movie.Title}</h5>
                            <p class="card-text text-muted">
                                <i class="fas fa-calendar me-1"></i> ${movie.Year}
                            </p>
                            <p class="card-text">
                                <small class="text-muted">
                                    <strong>{{ __('movies.type_label') }}:</strong> ${movie.Type.charAt(0).toUpperCase() + movie.Type.slice(1)}
                                </small>
                            </p>
                            <a href="/movies/${movie.imdbID}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            `;
        }

        $(document).on('click', '.remove-favorite', function() {
            const button = $(this);
            const imdbID = button.data('id');

            if (confirm('{{ __('movies.confirm_remove') }}')) {
                let favorites = JSON.parse(localStorage.getItem('movie_favorites') || '[]');
                favorites = favorites.filter(f => f.imdbID !== imdbID);
                localStorage.setItem('movie_favorites', JSON.stringify(favorites));

                // Remove card
                button.closest('.col-md-3').fadeOut(300, function() {
                    $(this).remove();
                    
                    // Check if empty
                    if (favorites.length === 0) {
                        $('#favorites-grid').empty();
                        $('#empty-favorites').show();
                    }
                });
            }
        });
    </script>
</body>
</html>