<?php op_mobile_page_title($application->getTitle()) ?>

<table width="100%" bgcolor="<?php echo $op_color["core_color_4"] ?>">
<tr><td colspan="2" align="center">
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
</td>
</tr>
<tr>
<td align="center" width="50%" valign="top">
<?php if ($application->getThumbnail()): ?>
<?php echo image_tag($application->getThumbnail(), array('alt' => $application->getTitle(), 'size' => '120x120')) ?>
<?php else: ?>
<?php echo image_tag('no_image.gif', array('size' => '120x120')) ?>
<?php endif; ?>
<br>
<?php echo sprintf('%s(%d)', $application->getTitle(), $application->countMembers()) ?>
</td>
<td valign="top">
<?php if ($application->getDescription()): ?>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo __('Description') ?>:</font><br>
<?php echo $application->getDescription() ?><br>
<?php endif; ?>
<?php if ($application->getAuthor()): ?>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo __('Author') ?>:</font><br>
<?php echo $application->getAuthor() ?><br>
<?php endif; ?>

</td>
</tr>
<tr><td colspan="2" align="center">
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
<?php echo link_to(__('Use this App'), '@application_add?id='.$application->getId()) ?>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
</td></tr>

<?php if ($memberApplication): ?>
<tr><td colspan="2">
<?php echo link_to(__('Remove this from this App'), '@application_remove?id='.$memberApplication->getId()) ?>
</td></tr>
<?php endif; ?>

<?php slot('op_mobile_footer_menu') ?>
<?php echo link_to(__('My Apps'), '@my_application_list') ?>
<?php end_slot(); ?>

</table>
