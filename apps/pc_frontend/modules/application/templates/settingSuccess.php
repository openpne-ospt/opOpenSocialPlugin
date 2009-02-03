<?php if(isset($isValid) && $isValid): ?>
<?php use_helper('Javascript') ?>
<?php echo javascript_tag("
var modal = parent.$('opensocial_modal_box');
var modalContents = parent.$('opensocial_modal_box_contents');
var modalIframe = modalContents.getElementsByTagName('iframe')[0];
Element.hide(modal);
Element.hide(modalContents);
"); ?>
<?php if($sf_request->getParameter('is_reload')) : ?>
<?php echo javascript_tag("
parent.location.href = parent.location.pathname + parent.location.search;
") ?>
<?php endif ?>
<?php endif ?>

<?php include_box('formAppSetting', __('Application Settings').': '.$appName, '', array(
  'form' => $forms,
  'url' => 'application/setting?id='.$sf_request->getParameter('id').'&is_reload='.$sf_request->getParameter('is_reload',false)
)) ?>
