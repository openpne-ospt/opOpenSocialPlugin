<?php slot('body') ?>
<?php echo __('Do you really delete this App?') ?>
<?php echo __('If you delete this app, this app is deleted from all users.'); ?>
<?php end_slot() ?>

<?php
$options = array(
  'title'   => __('Delete the App').": ".$application->getTitle(),
  'body'    => get_slot('body'),
  'no_url'  => url_for('@application_info?id='.$application->getId()),
  'no_method' => 'get',
);

op_include_yesno('ApplicationDeleteConfirm', new BaseForm(), new BaseForm(array(),array(),false), $options);
