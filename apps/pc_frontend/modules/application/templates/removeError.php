<?php include_box('ApplicationRemoveError', __('アプリケーション削除'), __('アプリケーションの削除に失敗しました。'));

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?></p>
