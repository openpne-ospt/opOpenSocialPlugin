<?php if(isset($isValid) && $isValid): ?>
<?php use_helper('Javascript') ?>
<?php echo javascript_tag("
var modal = parent.document.getElementById('opensocial_modal_box');
var modalContents = parent.document.getElementById('opensocial_modal_box_contents');
var modalIframe = modalContents.getElementsByTagName('iframe')[0];
"); ?>
<?php if($sf_request->getParameter('is_reload')) : ?>
<?php echo javascript_tag("
parent.location.href = parent.location.pathname + parent.location.search;
") ?>
<?php echo javascript_tag("
modal.style.display = 'none';
modalContents.style.display = 'none';
"); ?>
<?php endif ?>
<?php endif ?>

<?php include_box('formAppSetting', __('アプリケーション設定: ').$appName, '', array(
  'form' => $forms,
  'url' => 'application/setting?id='.$sf_request->getParameter('id').'&is_reload='.$sf_request->getParameter('is_reload',false)
)) ?>
