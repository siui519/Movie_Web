<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie['Title'] }} - {{ __('movies.title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .movie-header {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('{{ $movie['Poster'] !== 'N/A' ? $movie['Poster'] : 'https://via.placeholder.com/1920x1080' }}');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0 40px;
        }
        .n-a-text {
            color: #6c757d;
        }
        .action-buttons {
            margin-top: 20px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
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
                            <i class="fas fa-arrow-left me-1"></i>{{ __('navigation.back') }}
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

    <!-- Movie Header -->
    <div class="movie-header">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">{{ $movie['Title'] }}</h1>
            <p class="lead">
                <i class="fas fa-calendar me-2"></i>
                {{ $movie['Released'] ?? $movie['Year'] }}
                <span class="mx-3">|</span>
                <i class="fas fa-film me-2"></i>
                {{ ucfirst($movie['Type']) }}
                @if($movie['Runtime'] && $movie['Runtime'] !== 'N/A')
                    <span class="mx-3">|</span>
                    <i class="fas fa-clock me-2"></i>
                    {{ $movie['Runtime'] }}
                @endif
            </p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="container">
        <div class="action-buttons">
            <button type="button" class="btn btn-outline-primary favorite-toggle" 
                    data-id="{{ $movie['imdbID'] }}"
                    data-title="{{ $movie['Title'] }}"
                    data-year="{{ $movie['Year'] }}"
                    data-type="{{ $movie['Type'] }}"
                    data-poster="{{ $movie['Poster'] }}">
                <i class="fas fa-heart me-2"></i>{{ __('movies.add_to_favorites') }}
            </button>
            <button type="button" class="btn btn-danger favorite-remove" 
                    data-id="{{ $movie['imdbID'] }}" style="display:none;">
                <i class="fas fa-heart me-2"></i>{{ __('movies.remove_from_favorites') }}
            </button>
            <a href="https://www.imdb.com/title/{{ $movie['imdbID'] }}/" 
               target="_blank" 
               class="btn btn-primary">
                <i class="fas fa-external-link-alt me-2"></i>{{ __('movies.view_on_imdb') }}
            </a>
        </div>
    </div>

    <!-- Movie Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                @if($movie['Poster'] && $movie['Poster'] !== 'N/A')
                    <img src="{{ $movie['Poster'] }}" 
                         class="img-fluid rounded shadow" alt="{{ $movie['Title'] }}">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                         style="height: 400px;">
                        <i class="fas fa-film fa-5x"></i>
                    </div>
                @endif
                
                <div class="mt-4">
                    <h5>{{ __('movies.information') }}</h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>{{ __('movies.imdb_rating') }}</strong></td>
                            <td>
                                @if($movie['imdbRating'] && $movie['imdbRating'] !== 'N/A')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-star me-1"></i>{{ $movie['imdbRating'] }}/10
                                    </span>
                                @else
                                    <span class="n-a-text">N/A</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('movies.released') }}</strong></td>
                            <td>{{ $movie['Released'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('movies.runtime') }}</strong></td>
                            <td>{{ $movie['Runtime'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('movies.genre') }}</strong></td>
                            <td>{{ $movie['Genre'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('movies.director') }}</strong></td>
                            <td>{{ $movie['Director'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('movies.writer') }}</strong></td>
                            <td>{{ $movie['Writer'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('movies.language') }}</strong></td>
                            <td>{{ $movie['Language'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('movies.country') }}</strong></td>
                            <td>{{ $movie['Country'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('movies.awards') }}</strong></td>
                            <td>{{ $movie['Awards'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('movies.box_office') }}</strong></td>
                            <td>{{ $movie['BoxOffice'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('movies.production') }}</strong></td>
                            <td>{{ $movie['Production'] ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4>{{ __('movies.overview') }}</h4>
                        <p class="card-text">
                            @if($movie['Plot'] && $movie['Plot'] !== 'N/A')
                                {{ $movie['Plot'] }}
                            @else
                                <span class="n-a-text">{{ __('movies.no_plot_available') }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($movie['Actors'] && $movie['Actors'] !== 'N/A')
                <div class="card mb-4">
                    <div class="card-body">
                        <h4>{{ __('movies.cast') }}</h4>
                        <div class="d-flex flex-wrap gap-2">
                            @php
                                $actors = explode(', ', $movie['Actors']);
                            @endphp
                            @foreach($actors as $actor)
                                <span class="badge bg-primary">{{ $actor }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($movie['Ratings']) && is_array($movie['Ratings']) && count($movie['Ratings']) > 0)
                <div class="card mb-4">
                    <div class="card-body">
                        <h4>{{ __('movies.ratings') }}</h4>
                        <ul class="list-group">
                            @foreach($movie['Ratings'] as $rating)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong>{{ $rating['Source'] }}</strong>
                                    <span class="badge bg-info">{{ $rating['Value'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const movieId = '{{ $movie["imdbID"] }}';
            const favorites = JSON.parse(localStorage.getItem('movie_favorites') || '[]');
            const isFavorite = favorites.some(f => f.imdbID === movieId);
            
            if (isFavorite) {
                $('.favorite-toggle').hide();
                $('.favorite-remove').show();
            } else {
                $('.favorite-toggle').show();
                $('.favorite-remove').hide();
            }

            $('.favorite-toggle').click(function() {
                const movieData = {
                    imdbID: movieId,
                    Title: '{{ $movie["Title"] }}',
                    Year: '{{ $movie["Year"] }}',
                    Type: '{{ $movie["Type"] }}',
                    Poster: '{{ $movie["Poster"] }}'
                };
                
                let favorites = JSON.parse(localStorage.getItem('movie_favorites') || '[]');
                favorites.push(movieData);
                localStorage.setItem('movie_favorites', JSON.stringify(favorites));
                
                $(this).hide();
                $(this).siblings('.favorite-remove').show();
                alert('{{ __('movies.added_to_favorites') }}');
            });

            $('.favorite-remove').click(function() {
                if (confirm('{{ __('movies.confirm_remove') }}')) {
                    let favorites = JSON.parse(localStorage.getItem('movie_favorites') || '[]');
                    favorites = favorites.filter(f => f.imdbID !== movieId);
                    localStorage.setItem('movie_favorites', JSON.stringify(favorites));
                    
                    $(this).hide();
                    $(this).siblings('.favorite-toggle').show();
                    alert('{{ __('movies.removed_from_favorites') }}');
                }
            });
        });
    </script>
</body>
</html>