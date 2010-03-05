<?php slot('body') ?>
<?php echo __('Generate or reset consumer secret of this app?') ?>
<?php end_slot() ?>

<?php op_include_yesno('updateConsumerSecret', new BaseForm(), new BaseForm(array(), array(), false), array(
  'title' => __('Generate or reset consumer secret: %0%', array('%0%' => $application->getTitle())),
  'body' => get_slot('body'),
  'yes_url' => url_for('@application_update_consumer_secret?id='.$application->getId()),
  'no_url' => url_for('@application_info?id='.$application->getId()),
  'no_method' => 'get'
)) ?>
