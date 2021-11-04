<?php $__env->startSection('title', 'Forgot Password'); ?>
<?php $__env->startSection('header'); ?>
    <page-header v-bind:dark="true" v-bind:param="<?php echo e(json_encode(['avatar' => null, 'searchable' => true, 'login' => false])); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php if(session('status')): ?>
    <forgot-password v-bind:success="true"></forgot-password>
    <?php endif; ?>
    <?php if($errors->any()): ?>
    <forgot-password v-bind:message="<?php echo e(json_encode(['status' => 'failed', 'body' => $errors->all()])); ?>"></forgot-password>
    <?php else: ?>
    <forgot-password v-bind:message="<?php echo e(json_encode(['status' => 'failed', 'body' => $errors->all()])); ?>"></forgot-password>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>