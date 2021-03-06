<?php $__env->startSection('title', 'Owner Profile'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php if($message = session('status')): ?>
    <admin-owner-profile v-bind:message="<?php echo e(json_encode(['status' => 'success', 'body' => [$message]])); ?>" v-bind:user-info="<?php echo e($userInfo); ?>" v-bind:user-name="<?php echo e($userName); ?>"></admin-owner-profile>
    <?php elseif($errors->any()): ?>
    <admin-owner-profile v-bind:message="<?php echo e(json_encode(['status' => 'failed', 'body' => $errors->all()])); ?>" v-bind:user-info="<?php echo e($userInfo); ?>" v-bind:user-name="<?php echo e($userName); ?>" v-bind:old-input="<?php echo e(json_encode(old())); ?>"></admin-owner-profile>
    <?php else: ?>
    <admin-owner-profile v-bind:user-info="<?php echo e($userInfo); ?>" v-bind:user-name="<?php echo e($userName); ?>"></admin-owner-profile>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>