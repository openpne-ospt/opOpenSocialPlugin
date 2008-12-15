<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2>OpenSocialAPIプロフィール連動設定</h2>
<table>
<th>識別名</th><th>対応先</th>
<?php echo $profileConfigForm ?>
<tr><td colspan="2"><input type="submit" value="設定変更"></td></tr>
</table>
