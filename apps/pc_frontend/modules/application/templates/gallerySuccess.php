<?php use_helper('OpenSocial'); ?>

<?php include_box('searchApplication',__('Search Applications'),'',
array('form' => $filters, 'url' => 'application/gallery', 'button' => __('Search'))) ?>

<?php if ($pager->getNbResults()): ?>
<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'application/gallery?page=%d'); ?></p></div>
<?php foreach($pager->getResults() as $application) : ?>
<?php include_application_information_box(
  'item_'.$application->getId(),
  $application->getId(),
  0,
  false,
  $application
) ?>
<?php endforeach ?>
<div class="pagerRelative"><p><?php echo pager_navigation($pager, 'application/gallery?page=%d'); ?></p></div>
<?php else : ?>
<?php include_box('ApplicationGalleryError', __('Search Results'), __('Your search queries did not match any applications.')) ?>
<?php endif ?>
