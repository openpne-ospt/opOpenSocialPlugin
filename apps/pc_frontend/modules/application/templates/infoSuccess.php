<?php use_helper('Javascript') ?>

<div class="applicationInfo">
<?php
$appInfoList = array(
  __('Name')  => link_to_if($application->getTitleUrl() ,$application->getTitle(), $application->getTitleUrl(), array('target' => '_blank')),
  __('Screen Shot')  => '<div class="applicationScreenShot">'.($application->getScreenshot() ? image_tag($application->getScreenshot(), array('alt' => $application->getTitle())) : '').'</div>',
  __('Thumbnail')    => '<div class="applicationThumbnail">'.($application->getThumbnail() ? image_tag($application->getThumbnail(), array('alt' => $application->getTitle())) : '').'</div>',
  __('Description') => $application->getDescription(),
  __('Users') => link_to_if($application->getMembers()->count(), $application->getMembers()->count(), '@application_member?id='.$application->getId())
);
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

