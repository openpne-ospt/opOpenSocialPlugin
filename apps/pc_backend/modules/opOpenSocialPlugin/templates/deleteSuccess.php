<?php use_helper('Javascript') ?>
<h2><?php echo __('アプリ削除') ?></h2>
<p>本当に削除してもよろしいですか？</p>
<p>※このアプリのユーザの設定値も失われます。</p>
<?php echo $form->renderFormTag(url_for('opOpenSocialPlugin/delete?id='.$sf_request->getParameter('id'))) ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="削除">
</form>
<?php echo link_to_function(__('Back to previous page'), 'history.back()') ?>
