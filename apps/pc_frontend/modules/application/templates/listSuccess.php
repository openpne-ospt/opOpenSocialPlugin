<?php use_helper('Pagination') ?>
<?php if (isset($form)) : ?>
<?php include_box('form','アプリケーション追加','',array(
  'form' => array($form),
  'url' => 'application/list',
  'button' => 'add'
)) ?>
<?php endif ?>
<?php echo pager_navigation($pager, 'application/list?page=%d') ?>
<ul>
<?php foreach ($pager->getResults() as $app) : ?>
<li><?php echo link_to($app->getApplication()->getTitle(), 'application/canvas?mid='.$app->getId()) ?></li>
<?php endforeach ?>
</ul>
<?php echo pager_navigation($pager, 'application/list?page-%d') ?>
