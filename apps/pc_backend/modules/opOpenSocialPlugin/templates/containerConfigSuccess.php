<?php use_helper('Javascript') ?>

<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('Container Configuration') ?>
<?php end_slot() ?>

<?php include_partial('bottomSubmenu') ?>
<?php echo $containerConfigForm->renderFormTag(url_for('@op_opensocial_container_config')) ?>
<table>
<?php echo $containerConfigForm ?>
<tr><td colspan="2"><input type="submit" value="<?php echo __('Modify') ?>" /></td></tr>
</table>
</form>

<?php if (Doctrine::getTable('SnsConfig')->get('is_use_outer_shindig', false)): ?>
<div>
<h3>Download openpne.js</h3>
<p>
<?php echo link_to(__('Download openpne.js'), '@op_opensocial_generate_container_config') ?>
</p>
<p>
<ul>
<li><?php echo __('Copy the downloaded file to %config% directory of Shindig.', array('%config%' => '<strong>config/</strong>')) ?></li>
<li><?php echo __('If you modify the profile item, you must update this file.') ?></li>
</ul>
</p>
<h3>Backend key</h3>
<table>
<tr><th>Backend Consumer Key</th><td><?php echo $consumerKey ?></td></tr>
<tr><th>Backend Consumer Secret</th><td>
<div id="oauth_consumer_secret"></div>
<?php if ($consumerSecret): ?>
<?php echo button_to_remote(__('Show consumer secret'), array(
  'update' => 'oauth_consumer_secret',
  'url' => '@op_opensocial_show_backend_secret',
  'method' => 'get'
)) ?><br />
<?php echo button_to(__('Reset consumer secret'), '@op_opensocial_update_backend_secret') ?><br />
<?php echo button_to(__('Delete consumer secret'), '@op_opensocial_delete_backend_secret') ?>
<?php else: ?>
<?php echo button_to(__('Generate consumer secret'), '@op_opensocial_update_backend_secret') ?>
<?php endif; ?>
</td></tr>
</table>
</div>
<?php endif; ?>
