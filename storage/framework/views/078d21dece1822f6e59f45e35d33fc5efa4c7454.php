<?php $__env->startSection('title', 'Book Captain Confirm'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<?php if($errors->any()): ?>
    <owner-book-captain-confirm v-bind:captain-info="<?php echo e($captainInfo); ?>" v-bind:trip-info="<?php echo e($tripInfo); ?>" v-bind:is-admin="<?php echo e(isset($isAdmin)?$isAdmin:0); ?>" v-bind:message="<?php echo e(json_encode(['status' => 'failed', 'body' => $errors->all()])); ?>"></owner-book-captain-confirm>
    <?php else: ?>
    <owner-book-captain-confirm v-bind:captain-info="<?php echo e($captainInfo); ?>" v-bind:trip-info="<?php echo e($tripInfo); ?>" v-bind:is-admin="<?php echo e(isset($isAdmin)?$isAdmin:0); ?>"></owner-book-captain-confirm>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>