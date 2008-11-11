<?php if (isset($form)) : ?>
<?php include_box('form','アプリケーション追加','',array(
  'form' => array($form),
  'url' => 'application/list',
  'button' => 'add'
)) ?>
<?php endif ?>
<?php echo include_box('ApplicationListError','Application List','No application.');
