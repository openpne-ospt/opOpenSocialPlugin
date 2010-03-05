<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('Delete consumer secret') ?>
<?php end_slot() ?>

<?php use_helper('Javascript') ?>
<p>
<?php echo __('Delete consumer secret of this app?') ?>&nbsp;
<?php echo __('It becomes impossible to access with API when the key is deleted.') ?>
</p>
<p><?php echo $application->getTitle() ?></p>
<?php $form = new BaseForm() ?>
<?php echo $form->renderFormTag(url_for('@op_opensocial_delete_consumer_secret?id='.$sf_request->getParameter('id'))) ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Delete') ?>">
</form>

<?php echo link_to_function(__('Back to previous page'), 'history.back()') ?>
