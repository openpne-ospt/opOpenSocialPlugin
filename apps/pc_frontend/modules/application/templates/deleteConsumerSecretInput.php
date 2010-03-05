<?php slot('body') ?>
<?php echo __('Delete consumer secret of this app?') ?>&nbsp;
<?php echo __('It becomes impossible to access with API when the key is deleted.') ?>
<?php end_slot() ?>

<?php op_include_yesno('deleteConsumerSecret', new BaseForm(), new BaseForm(array(), array(), false), array(
  'title' => __('Delete consumer secret: %0%', array('%0%' => $application->getTitle())),
  'body' => get_slot('body'),
  'yes_url' => url_for('@application_delete_consumer_secret?id='.$application->getId()),
  'no_url' => url_for('@application_info?id='.$application->getId()),
  'no_method' => 'get'
)) ?>
