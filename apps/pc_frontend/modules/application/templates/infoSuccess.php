<?php use_helper('Javascript') ?>

<?php slot('op_sidemenu') ?>
<?php op_include_parts('applicationImageBox', 'applicationImageBox', array(
  'object' => $application,
)) ?>
<?php if ($memberListPager->getNbResults()): ?>
<?php op_include_parts('nineTable', 'applicationUsers', array(
  'link_to'  => 'member/profile?id=',
  'title'    => __('Application Users'),
  'list'     => $memberListPager->getResults(),
  'moreInfo' => array(
    link_to(sprintf('%s(%d)', __('Show all'), $memberListPager->getNbResults()), '@application_member?id='.$application->getId())
  )
))  ?>
<?php endif; ?>
<?php end_slot(); ?>

<div class="applicationInfo">
<?php
$appInfoList = array();
if ($application->getScreenshot())
{
  $appInfoList[__('Screen Shot')] = content_tag('div', image_tag($application->getScreenshot(), array('alt' => $application->getTitle())), array('class' => 'applicationScreenShot'));
}
$appInfoList[__('Description')] = $application->getDescription();
$authorInfoList = array(
  __('Name') => $application->getAuthorEmail() ? mail_to($application->getAuthorEmail(), $application->getAuthor(), array('encode' => true)) : $application->getAuthor(),
  __('Affiliation')   => $application->getAuthorAffiliation(),
  __('Aboutme') => $application->getAuthorAboutme(),
  __('Photo')   => $application->getAuthorPhoto() ? image_tag($application->getAuthorPhoto(), array('alt' => $application->getAuthor())) : '',
  __('Link') => $application->getAuthorLink() ? link_to(null , $application->getAuthorLink(), array('target' => '_blank')) : '',
  __('Quote')   => $application->getAuthorQuote(),
);
include_list_box('ApplicationInfoList', $appInfoList, array('title' => __('About Application')));
include_list_box('AuthorInfoList', $authorInfoList, array('title' => __('About Author')));
?>
<div class="moreInfo">
<ul class="moreInfo">
<li><?php echo link_to(__('Add this application'), 'application/add?id='.$application->getId()) ?></li>
<li><?php echo link_to_function(__('Back to previous page'), 'history.back()') ?></li>
</ul>
</div>
</div>

