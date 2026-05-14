<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(__('auth.login')); ?> - <?php echo e(__('movies.title')); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-film fa-3x text-primary mb-3"></i>
                            <h3 class="card-title"><?php echo e(__('movies.title')); ?></h3>
                            <p class="text-muted"><?php echo e(__('auth.welcome')); ?></p>
                        </div>

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <?php echo e(__('auth.credentials_error')); ?>

                            </div>
                        <?php endif; ?>

                        <?php if(session('success')): ?>
                            <div class="alert alert-success">
                                <?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('login')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label for="username" class="form-label"><?php echo e(__('auth.username')); ?></label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo e(old('username')); ?>" required autofocus>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label"><?php echo e(__('auth.password')); ?></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-sign-in-alt me-2"></i><?php echo e(__('auth.login_button')); ?>

                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Username: <strong>aldmic</strong> | Password: <strong>123abc123</strong>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\movies_web\resources\views/auth/login.blade.php ENDPATH**/ ?>