<?php use_helper('OpenSocial') ?>

<?php if ($isOwner) : ?>
<?php echo make_app_setting_modal_box('opensocial_modal_box') ?>
<?php if ($isAddApplication) : ?>
<?php op_include_form('form', $form ,array(
  'title' => __('Add a new application'),
  'url' => url_for('application/list'),
  'button' => __('Add'),
)) ?>
<?php endif ?>
<?php endif ?>

<div class="applicationList">
<?php if (isset($memberApplications) && count($memberApplications)) : ?>
<?php if ($isOwner): ?>
<ul>
<li>アプリケーションはドラッグにより順序を並び替えることができます。</li>
<li>最大N個のアプリケーションがホームに表示されます。</li>
</ul>
<?php endif; ?>
<div id="order">
<?php foreach ($memberApplications as $memberApplication) : ?>
<?php op_include_application_information_box(
  'item_'.$memberApplication->getId(),
  $memberApplication->getApplication(),
  $memberApplication->getId(),
  $isOwner
) ?>
<?php endforeach ?>
</div>
<?php else : ?>
<?php slot('no_application_alert') ?>
アプリケーションがありません。
<?php if ($isOwner) : ?>
アプリケーションギャラリーから追加することができます。
<?php endif; ?>
<?php end_slot(); ?>
<?php op_include_parts('alertBox', 'NoApplication', array('body' => get_slot('no_application_alert'))) ?>
<?php endif; ?>

<?php if ($isOwner) : ?>
<?php echo sortable_element('order', array(
  'url'    => '@application_sort',
  'tag'    => 'div',
  'only'   => 'sortable'
)); ?>
<?php endif; ?>
<div class="moreInfo">
<ul class="moreInfo">
<li>
<?php echo link_to(__('Application Gallery'), '@application_gallery') ?>
</li>
</ul>
</div>
</div>
