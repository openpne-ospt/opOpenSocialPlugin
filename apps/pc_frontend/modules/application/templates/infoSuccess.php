<?php use_helper('Javascript') ?>

<?php slot('op_sidemenu') ?>
<?php op_include_parts('applicationImageBox', 'applicationImageBox', array(
  'object' => $application,
)) ?>
<?php if ($memberListPager->getNbResults()): ?>
<?php op_include_parts('nineTable', 'applicationUsers', array(
  'link_to'  => 'member/profile?id=',
  'title'    => __('App Users'),
  'list'     => $memberListPager->getResults(),
  'moreInfo' => array(
    link_to(sprintf('%s(%d)', __('Show all'), $memberListPager->getNbResults()), '@application_member?id='.$application->getId())
  )
))  ?>
<?php endif; ?>
<?php end_slot(); ?>

<?php if (!$application->isActive()): ?>
<?php op_include_box('AppInfo', __('This app is waiting to approval by the SNS administrator, or is inactive.')) ?>
<?php endif; ?>

<div class="appInfo">
<?php
$appInfoList = array();
if ($application->getMemberId())
{
  $appInfoList[__('Member who Added App')] = link_to($application->getAdditionalMember()->getName(), '@member_profile?id='.$application->getMemberId());
}
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
op_include_parts('listBox', 'ApplicationInfoList', array('title' => __('About App'), 'list' => $appInfoList));
op_include_parts('listBox', 'AuthorInfoList', array('title' => __('About Author'), 'list' => $authorInfoList));
?>

<?php if ($member->getId() === $application->getMemberId()): ?>
<?php $form = new sfForm(); ?>
<?php slot('update_button'); ?>
<?php echo $form->renderFormTag(url_for('@application_update?id='.$application->getId())) ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Update') ?>" />
</form>
<?php end_slot(); ?>
<?php
$developerInfoList = array(
  __('Gadget XML') => $application->getUrl(),
  __('Update App Info') => get_slot('update_button'),
  __('Delete App') => link_to(__('Delete'), '@application_delete?id='.$application->getId()),
);
?>
<?php op_include_parts('listBox', 'DeveloperInfoList', array('title' => __('Information for Developer'), 'list' => $developerInfoList)); ?>
<?php endif; ?>
<div class="moreInfo">
<ul class="moreInfo">
<?php if ($application->isActive()): ?>
<li><?php echo link_to(__('Add this App'), '@application_add?id='.$application->getId()) ?></li>
<?php endif; ?>
<li><?php echo link_to_function(__('Back to previous page'), 'history.back()') ?></li>
</ul>
</div>
</div>
