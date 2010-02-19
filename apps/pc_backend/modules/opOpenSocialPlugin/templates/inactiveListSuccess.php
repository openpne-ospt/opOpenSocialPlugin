<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('Inactive Apps') ?>
<?php end_slot() ?>

<?php if ($pager->getNbResults()): ?>
<?php slot('pager') ?>
<?php op_include_pager_navigation($pager, '@op_opensocial_inactive_list?page=%d') ?>
<?php end_slot(); ?>
<?php include_slot('pager') ?>
<ul>
<?php foreach ($pager->getResults() as $application): ?>
<li><?php echo link_to($application->getTitle(), '@op_opensocial_info?id='.$application->getId()) ?></li>
<?php endforeach; ?>
</ul>
<?php include_slot('pager') ?>
<?php else: ?>
<?php echo __('There are no inactive app.') ?>
<?php endif; ?>
