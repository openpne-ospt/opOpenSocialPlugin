<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('Generate or reset consumer secret') ?>
<?php end_slot() ?>

<?php use_helper('Javascript') ?>
<p><?php echo __('Generate or reset consumer secret of this app?') ?></p>
<p><?php echo $application->getTitle() ?></p>
<?php $form = new BaseForm() ?>
<?php echo $form->renderFormTag(url_for('@op_opensocial_update_consumer_secret?id='.$sf_request->getParameter('id'))) ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Yes') ?>">
</form>

<?php echo link_to_function(__('Back to previous page'), 'history.back()') ?>
