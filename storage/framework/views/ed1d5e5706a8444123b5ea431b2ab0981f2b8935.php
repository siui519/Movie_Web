<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($movie['Title']); ?> - <?php echo e(__('movies.title')); ?></title>
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
                        url('<?php echo e($movie['Poster'] !== 'N/A' ? $movie['Poster'] : 'https://via.placeholder.com/1920x1080'); ?>');
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
            <a class="navbar-brand" href="<?php echo e(route('movies.index')); ?>">
                <i class="fas fa-film me-2"></i><?php echo e(__('movies.title')); ?>

            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('movies.index')); ?>">
                            <i class="fas fa-arrow-left me-1"></i><?php echo e(__('navigation.back')); ?>

                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('favorites.index')); ?>">
                            <i class="fas fa-heart me-1"></i><?php echo e(__('movies.favorites')); ?>

                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-language me-1"></i><?php echo e(app()->getLocale() == 'id' ? '🇮🇩 ID' : '🇺🇸 EN'); ?>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('lang.switch', 'id')); ?>">
                                    <i class="fas fa-flag me-2"></i>🇮🇩 Bahasa Indonesia
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('lang.switch', 'en')); ?>">
                                    <i class="fas fa-flag me-2"></i>🇺🇸 English
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-link nav-link">
                                <i class="fas fa-sign-out-alt me-1"></i><?php echo e(__('auth.logout')); ?>

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
            <h1 class="display-4 fw-bold mb-3"><?php echo e($movie['Title']); ?></h1>
            <p class="lead">
                <i class="fas fa-calendar me-2"></i>
                <?php echo e($movie['Released'] ?? $movie['Year']); ?>

                <span class="mx-3">|</span>
                <i class="fas fa-film me-2"></i>
                <?php echo e(ucfirst($movie['Type'])); ?>

                <?php if($movie['Runtime'] && $movie['Runtime'] !== 'N/A'): ?>
                    <span class="mx-3">|</span>
                    <i class="fas fa-clock me-2"></i>
                    <?php echo e($movie['Runtime']); ?>

                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="container">
        <div class="action-buttons">
            <button type="button" class="btn btn-outline-primary favorite-toggle" 
                    data-id="<?php echo e($movie['imdbID']); ?>"
                    data-title="<?php echo e($movie['Title']); ?>"
                    data-year="<?php echo e($movie['Year']); ?>"
                    data-type="<?php echo e($movie['Type']); ?>"
                    data-poster="<?php echo e($movie['Poster']); ?>">
                <i class="fas fa-heart me-2"></i><?php echo e(__('movies.add_to_favorites')); ?>

            </button>
            <button type="button" class="btn btn-danger favorite-remove" 
                    data-id="<?php echo e($movie['imdbID']); ?>" style="display:none;">
                <i class="fas fa-heart me-2"></i><?php echo e(__('movies.remove_from_favorites')); ?>

            </button>
            <a href="https://www.imdb.com/title/<?php echo e($movie['imdbID']); ?>/" 
               target="_blank" 
               class="btn btn-primary">
                <i class="fas fa-external-link-alt me-2"></i><?php echo e(__('movies.view_on_imdb')); ?>

            </a>
        </div>
    </div>

    <!-- Movie Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <?php if($movie['Poster'] && $movie['Poster'] !== 'N/A'): ?>
                    <img src="<?php echo e($movie['Poster']); ?>" 
                         class="img-fluid rounded shadow" alt="<?php echo e($movie['Title']); ?>">
                <?php else: ?>
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                         style="height: 400px;">
                        <i class="fas fa-film fa-5x"></i>
                    </div>
                <?php endif; ?>
                
                <div class="mt-4">
                    <h5><?php echo e(__('movies.information')); ?></h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong><?php echo e(__('movies.imdb_rating')); ?></strong></td>
                            <td>
                                <?php if($movie['imdbRating'] && $movie['imdbRating'] !== 'N/A'): ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-star me-1"></i><?php echo e($movie['imdbRating']); ?>/10
                                    </span>
                                <?php else: ?>
                                    <span class="n-a-text">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('movies.released')); ?></strong></td>
                            <td><?php echo e($movie['Released'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('movies.runtime')); ?></strong></td>
                            <td><?php echo e($movie['Runtime'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('movies.genre')); ?></strong></td>
                            <td><?php echo e($movie['Genre'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('movies.director')); ?></strong></td>
                            <td><?php echo e($movie['Director'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('movies.writer')); ?></strong></td>
                            <td><?php echo e($movie['Writer'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('movies.language')); ?></strong></td>
                            <td><?php echo e($movie['Language'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('movies.country')); ?></strong></td>
                            <td><?php echo e($movie['Country'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('movies.awards')); ?></strong></td>
                            <td><?php echo e($movie['Awards'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('movies.box_office')); ?></strong></td>
                            <td><?php echo e($movie['BoxOffice'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('movies.production')); ?></strong></td>
                            <td><?php echo e($movie['Production'] ?? 'N/A'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4><?php echo e(__('movies.overview')); ?></h4>
                        <p class="card-text">
                            <?php if($movie['Plot'] && $movie['Plot'] !== 'N/A'): ?>
                                <?php echo e($movie['Plot']); ?>

                            <?php else: ?>
                                <span class="n-a-text"><?php echo e(__('movies.no_plot_available')); ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <?php if($movie['Actors'] && $movie['Actors'] !== 'N/A'): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h4><?php echo e(__('movies.cast')); ?></h4>
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                                $actors = explode(', ', $movie['Actors']);
                            ?>
                            <?php $__currentLoopData = $actors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $actor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-primary"><?php echo e($actor); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(isset($movie['Ratings']) && is_array($movie['Ratings']) && count($movie['Ratings']) > 0): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h4><?php echo e(__('movies.ratings')); ?></h4>
                        <ul class="list-group">
                            <?php $__currentLoopData = $movie['Ratings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong><?php echo e($rating['Source']); ?></strong>
                                    <span class="badge bg-info"><?php echo e($rating['Value']); ?></span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const movieId = '<?php echo e($movie["imdbID"]); ?>';
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
                    Title: '<?php echo e($movie["Title"]); ?>',
                    Year: '<?php echo e($movie["Year"]); ?>',
                    Type: '<?php echo e($movie["Type"]); ?>',
                    Poster: '<?php echo e($movie["Poster"]); ?>'
                };
                
                let favorites = JSON.parse(localStorage.getItem('movie_favorites') || '[]');
                favorites.push(movieData);
                localStorage.setItem('movie_favorites', JSON.stringify(favorites));
                
                $(this).hide();
                $(this).siblings('.favorite-remove').show();
                alert('<?php echo e(__('movies.added_to_favorites')); ?>');
            });

            $('.favorite-remove').click(function() {
                if (confirm('<?php echo e(__('movies.confirm_remove')); ?>')) {
                    let favorites = JSON.parse(localStorage.getItem('movie_favorites') || '[]');
                    favorites = favorites.filter(f => f.imdbID !== movieId);
                    localStorage.setItem('movie_favorites', JSON.stringify(favorites));
                    
                    $(this).hide();
                    $(this).siblings('.favorite-toggle').show();
                    alert('<?php echo e(__('movies.removed_from_favorites')); ?>');
                }
            });
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\movies_web\resources\views/movies/show.blade.php ENDPATH**/ ?>