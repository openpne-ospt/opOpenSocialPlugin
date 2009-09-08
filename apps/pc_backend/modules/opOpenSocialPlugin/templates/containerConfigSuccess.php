<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2><?php echo __('コンテナ設定') ?></h2>

<form action="<?php echo url_for('opOpenSocialPlugin/containerConfig') ?>" method="post">
<table>
<?php echo $containerConfigForm ?>
<tr><td colspan="2"><input type="submit" value="<?php echo __('設定変更') ?>" /></td></tr>
</table>
</form>

<? if ($isUseOuterShindig): ?>
<?php echo link_to(__('openpne.jsのダウンロード'), 'opOpenSocialPlugin/generateContainerConfig') ?>
<? endif ?>

