<?php include_box('ApplicationAddError',__('アプリケーション追加'), __('アプリケーションの追加に失敗しました。')) ?>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?></p>
