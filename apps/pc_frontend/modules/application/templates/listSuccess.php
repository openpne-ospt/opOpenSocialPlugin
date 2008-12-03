<?php use_helper('Javascript') ?>
<?php use_helper('OpenSocial') ?>
<?php if (isset($form)) : ?>
<?php include_box('form','アプリケーション追加','',array(
  'form' => array($form),
  'url' => 'application/list',
  'button' => 'add'
)) ?>
<?php endif ?>
<div id="order">
<?php foreach ($apps as $app) : ?>
<?php include_application_information_box(
  'item_'.$app->getApplication()->getId(),
  $app->getId(),
  $app->getApplication()->getTitle(),
  $app->getAPplication()->getDescription(),
  $app->getApplication()->getThumbnail(),
  $app->getApplication()->getAuthor(),
  $app->getApplication()->getAuthorEmail()
) ?>
<?php endforeach ?>
</div>
<?php echo sortable_element('order', array(
  'tag'    => 'div',
)) ?>
