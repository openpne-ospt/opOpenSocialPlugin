<?php echo include_box('ApplicationError', __('アプリケーション'), __('このアプリケーションにはアクセスできません。')) ?>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?></p>
