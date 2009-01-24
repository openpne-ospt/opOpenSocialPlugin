<?php echo include_box('ApplicationInfoError', __('アプリケーション情報'), __('存在しないアプリケーションです。'));

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?></p>
