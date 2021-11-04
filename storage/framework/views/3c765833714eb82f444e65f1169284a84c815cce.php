<?php $__env->startSection('title', 'Request a Captain'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<?php if($errors->any()): ?>
    <owner-request-any-captain v-bind:message="<?php echo e(json_encode(['status' => 'failed', 'body' => $errors->all()])); ?>" v-bind:old-input="<?php echo e(json_encode(old())); ?>"></owner-request-any-captain>
    <?php else: ?>
    <owner-request-any-captain></owner-request-any-captain>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>