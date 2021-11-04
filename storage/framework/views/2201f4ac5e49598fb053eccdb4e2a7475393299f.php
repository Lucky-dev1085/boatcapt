<?php $__env->startSection('title', 'Reset Password'); ?>
<?php $__env->startSection('header'); ?>
    <page-header v-bind:dark="true" v-bind:param="<?php echo e(json_encode(['avatar' => null, 'searchable' => true, 'login' => false])); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php if($errors->any()): ?>
    <reset-password v-bind:message="<?php echo e(json_encode(['status' => 'failed', 'body' => $errors->all()])); ?>" v-bind:param="<?php echo e(json_encode(['email' => ($email ? $email : old('email')), 'token' => $token])); ?>"></reset-password>
    <?php else: ?>
    <reset-password v-bind:param="<?php echo e(json_encode(['email' => '', 'token' => $token])); ?>"></reset-password>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>