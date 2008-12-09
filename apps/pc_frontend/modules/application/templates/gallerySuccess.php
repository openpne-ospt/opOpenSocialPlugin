<?php use_helper('Pagination'); ?>
<?php use_helper('OpenSocial'); ?>

<?php echo pager_navigation($pager, 'application/gallery?page=%d'); ?>
<?php foreach($pager->getResults() as $application) : ?>
<?php include_application_information_box(
  'item_'.$application->getId(),
  $application->getId(),
  0,
  false,
  $application->getTitle(),
  $application->getDescription(),
  $application->getThumbnail(),
  $application->getAuthor(),
  $application->getAuthorEmail()
) ?>
<?php endforeach ?>
<?php echo pager_navigation($pager, 'application/gallery?page=%d'); ?>
