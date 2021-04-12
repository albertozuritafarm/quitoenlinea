<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo e(config('app.name')); ?> - Url shortener</title>

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">

        <style>
            html, body {
                height: 100vh;
            }

            .wrapper {
                min-height: 100vh;
            }

            .logo-title {
                max-height: 100px;
            }
        </style>

        <?php echo $__env->yieldPushContent('styles'); ?>
    </head>
    <body>
        <div class="container h-100">
            <div class="wrapper row align-items-center">
                <?php echo $__env->yieldContent('shorturl.content'); ?>
            </div>
        </div>
        <?php echo $__env->yieldPushContent('scripts'); ?>
    </body>
</html>
<?php /**PATH C:\wamp64\www\magnussucre\vendor\gallib\laravel-short-url\resources\views\layout.blade.php ENDPATH**/ ?>