<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('App Configuration') ?>
<?php end_slot() ?>

<?php include_partial('bottomSubmenu') ?>

<?php echo $applicationConfigForm->renderFormTag(url_for('@op_opensocial_application_config')) ?>
<table>
<?php echo $applicationConfigForm ?>
<tr><td colspan="2"><input type="submit" value="<?php echo __('Modify') ?>" /></td></tr>
</table>
</form>

