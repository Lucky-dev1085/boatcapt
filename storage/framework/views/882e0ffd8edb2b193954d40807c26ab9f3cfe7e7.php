<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $__env->yieldContent('title', 'Lander'); ?> | BoatCaptain</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="BoatCaptain">
        <?php echo $__env->yieldContent('meta'); ?>
        <link rel="icon" href="<?php echo e(url('/favicon.ico')); ?>">
        <link href="<?php echo e(url('/public/css/app.css')); ?>" rel=stylesheet >
        <?php echo $__env->yieldContent('styles'); ?>
    </head>
    <body style="background-color: initial;">
        <div id="app">   
            <?php echo $__env->yieldContent('content'); ?>
        </div>
        <?php echo $__env->yieldContent('scripts'); ?>
    </body>
</html>