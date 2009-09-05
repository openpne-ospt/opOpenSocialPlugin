<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2>アプリケーション追加</h2>
<?php echo $form->renderFormTag(url_for('opOpenSocialPlugin/add')) ?>
<table>
<?php echo $form ?>
<td colspan="2"><input type="submit" value="<?php echo __('Add') ?>" /></td>
</table>
</form>
