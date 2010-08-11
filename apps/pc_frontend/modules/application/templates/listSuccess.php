<?php use_helper('OpenSocial') ?>

<?php if ($isOwner) : ?>
<?php echo make_app_setting_modal_box('opensocial_modal_box') ?>
<?php if ($isAddApplication) : ?>
<?php op_include_form('form', $form ,array(
  'title' => __('Add a new application'),
  'url' => url_for('application/list'),
  'button' => __('Add'),
)) ?>
<?php endif ?>
<?php endif ?>
<div class="applicationList">
<?php if (isset($memberApps) && count($memberApps)) : ?>
<div id="order">
<?php foreach ($memberApps as $memberApp) : ?>
<?php include_application_information_box(
  'item_'.$memberApp->getId(),
  $memberApp->getApplication()->getId(),
  $memberApp->getId(),
  $isOwner,
  $memberApp->getApplication()
) ?>
<?php endforeach ?>
</div>
<?php 
if ($isOwner)
{
echo sortable_element('order', array(
  'url'  => 'application/sortApplication',
  'tag'  => 'div',
  'only' => 'sortable',
  'with' => 'Sortable.serialize("order")+"&'.urlencode($tokenForm->getCSRFFieldName()).'='.urlencode($tokenForm->getCSRFToken()).'"'
));
} ?>
<?php endif ?>
<?php if ($isOwner): ?>
<div class="moreInfo">
<ul class="moreInfo">
<li>
<?php echo link_to(__('Application Gallery'), 'application/gallery') ?>
</li>
</ul>
</div>
<?php endif ?>
</div>
