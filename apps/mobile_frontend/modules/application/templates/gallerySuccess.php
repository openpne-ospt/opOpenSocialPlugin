<?php op_mobile_page_title(__('App Gallery')) ?>

<?php if ($pager->getNbResults()): ?>
<center>
<?php op_include_pager_total($pager) ?>
</center>
<?php
$list = array();
foreach ($pager->getResults() as $application)
{
  $list[] = link_to($application->getTitle(), '@application_info?id='.$application->getId());
}
op_include_list('applicationList', $list, array('border' => true));
?>
<?php op_include_pager_navigation($pager, '@application_gallery?page=%d', array('is_total' => false, 'use_current_query_string' => true)) ?>
<?php else: ?>
<?php echo __('Your search queries did not match any Apps.') ?>
<?php endif; ?>

<?php
$options = array(
  'url' => url_for('@application_gallery'),
  'button' => __('Search'),
  'method' => 'get',
  'align' => 'center'
);
?>

<table width="100%">
<tbody><tr><td bgcolor="<?php echo $op_color["core_color_5"] ?>">
<font color="<?php $op_color["core_color_25"] ?>">
<?php echo __('Search Apps') ?>
</font><br/>
</td></tr>
</tbody></table>

<?php op_include_form('searchMember', $searchForm, $options); ?>

<?php slot('op_mobile_footer_menu') ?>
<?php echo link_to(__('My Apps'), '@my_application_list') ?>
<?php end_slot(); ?>
