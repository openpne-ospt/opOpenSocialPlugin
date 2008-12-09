<?php use_helper('Javascript') ?>
<?php use_helper('OpenSocial') ?>
<?php if ($isOwner) : ?>
<?php echo link_to(__('Application Gallery'), 'application/gallery') ?>
<?php include_box('form','アプリケーション追加','',array(
  'form' => array($form),
  'url' => 'application/list',
  'button' => 'add'
)) ?>
<?php endif ?>
<?php if (isset($apps) && count($apps)) : ?>
<div id="order">
<?php foreach ($apps as $app) : ?>
<?php include_application_information_box(
  'item_'.$app->getApplication()->getId(),
  $app->getApplication()->getId(),
  $app->getId(),
  $isOwner,
  $app->getApplication()->getTitle(),
  $app->getAPplication()->getDescription(),
  $app->getApplication()->getThumbnail(),
  $app->getApplication()->getAuthor(),
  $app->getApplication()->getAuthorEmail()
) ?>
<?php endforeach ?>
</div>
<?php 
if ($isOwner)
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
