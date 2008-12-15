<?php use_helper('Javascript') ?>
<?php use_helper('OpenSocial') ?>
<?php if ($is_owner) : ?>
<?php echo link_to(__('Application Gallery'), 'application/gallery') ?>
<?php if ($is_add_application) : ?>
<?php include_box('form','アプリケーション追加','',array(
  'form' => array($form),
  'url' => 'application/list',
  'button' => 'add'
)) ?>
<?php endif ?>
<?php endif ?>
<?php if (isset($member_apps) && count($member_apps)) : ?>
<div id="order">
<?php foreach ($member_apps as $member_app) : ?>
<?php include_application_information_box(
  'item_'.$member_app->getId(),
  $member_app->getApplication()->getId(),
  $member_app->getId(),
  $is_owner,
  $member_app->getApplication()->getTitle(),
  $member_app->getAPplication()->getDescription(),
  $member_app->getApplication()->getThumbnail(),
  $member_app->getApplication()->getAuthor(),
  $member_app->getApplication()->getAuthorEmail()
) ?>
<?php endforeach ?>
</div>
<?php 
if ($is_owner)
{
echo sortable_element('order', array(
  'url'    => 'application/sortApplication',
  'tag'    => 'div',
  'only'   => 'sortable'
));
} ?>
<?php else: ?>
<?php include_box('ApplicationListError','Application List Error','No application.') ?>
<?php endif ?>
