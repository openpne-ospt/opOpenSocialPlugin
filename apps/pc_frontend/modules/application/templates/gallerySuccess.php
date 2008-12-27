<?php use_helper('OpenSocial'); ?>

<?php include_box('searchApplication',__('アプリケーション検索'),'',
array('form' => $filters, 'url' => 'application/gallery', 'button' => __('検索'))) ?>

<?php if ($pager->getNbResults()): ?>
<?php echo pager_navigation($pager, 'application/gallery?page=%d'); ?>
<?php foreach($pager->getResults() as $application) : ?>
<?php include_application_information_box(
  'item_'.$application->getId(),
  $application->getId(),
  0,
  false,
  $application
) ?>
<?php endforeach ?>
<?php echo pager_navigation($pager, 'application/gallery?page=%d'); ?>
<?php else : ?>
<?php include_box('ApplicationGalleryError', __('アプリケーション検索'), __('該当するアプリケーションは見つかりませんでした。')) ?>
<?php endif ?>
