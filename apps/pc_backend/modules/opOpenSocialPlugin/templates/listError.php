<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<p>アプリケーションの追加に失敗しました。</p>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?></p>
