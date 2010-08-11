<h2><?php echo __('アプリケーション削除') ?></h2>
<p>本当に削除してもよろしいですか？</p>
<p>※このアプリケーションのメンバーの設定値も失われます。</p>
<form action="<?php echo url_for('opOpenSocialPlugin/deleteApplication?id='.$sf_request->getParameter('id')) ?>" method="post">
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="削除">
</form>
