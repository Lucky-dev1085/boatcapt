<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <admin-dashboard v-bind:new-trips-count="<?php echo e($newTripsCount); ?>"></admin-dashboard>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>