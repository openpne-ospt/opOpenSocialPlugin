<?php op_mobile_page_title(__('My Apps')) ?>

<?php if ($pager->getNbResults()): ?>
<center>
<?php op_include_pager_total($pager) ?>
</center>
<?php
$list = array();
foreach ($pager->getResults() as $memberApplication)
{
  $list[] = link_to($memberApplication->getApplication()->getTitle(), '@application_render?id='.$memberApplication->getApplicationId());
}
op_include_list('memberApplicationList', $list, array('border' => true));
?>
<?php op_include_pager_navigation($pager, '@my_application_list?page=%d', array('is_total' => false)) ?>
<?php else: ?>
<?php echo __("You haven't the app.") ?>
<?php echo __("The Apps can be added from %0%.", array('%0%' => link_to(__('App Gallery'), '@application_gallery'))) ?>
<?php endif; ?>

<?php slot('op_mobile_footer_menu') ?>
<?php echo link_to(__('App Gallery'), '@application_gallery')?>
<?php end_slot(); ?>
