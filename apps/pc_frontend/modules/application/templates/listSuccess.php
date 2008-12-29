<?php use_helper('Javascript') ?>
<?php use_helper('OpenSocial') ?>
<?php if ($isOwner) : ?>
<?php echo link_to(__('Application Gallery'), 'application/gallery') ?>
<?php if ($isAddApplication) : ?>
<?php include_box('form','アプリケーション追加','',array(
  'form' => array($form),
  'url' => 'application/list',
  'button' => 'add'
)) ?>
<?php endif ?>
<?php endif ?>
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
  'url'    => 'application/sortApplication',
  'tag'    => 'div',
  'only'   => 'sortable'
));
} ?>
<?php else: ?>
<?php include_box('ApplicationListError',__('アプリケーションリスト'), __('インストールされたアプリケーションがありません。')) ?>
<?php endif ?>
