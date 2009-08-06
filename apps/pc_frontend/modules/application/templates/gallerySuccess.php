<?php use_helper('OpenSocial'); ?>


<?php include_box('searchApplication',__('Search Applications'),'', array(
    'form' => $searchForm, 
    'url' => '@application_gallery',
    'method' => 'get',
    'button' => __('Search')
  ))
?>

<?php if (isset($pager) && $pager->getNbResults()): ?>
<?php slot('pager') ?>
<?php op_include_pager_navigation($pager, '@application_gallery?page=%d', array('use_current_query_string' => true)) ?>
<?php end_slot() ?>
<?php include_slot('pager') ?>
<?php foreach ($pager->getResults() as $application) : ?>
<?php op_include_application_information_box(
  'item_'.$application->getId(),
  $application,
  null,
  false
)?>
<?php include_slot('pager') ?>
<?php endforeach; ?>
<?php else : ?>
<?php include_box('ApplicationGalleryError', __('Search Results'), __('Your search queries did not match any applications.')) ?>
<?php endif; ?>
