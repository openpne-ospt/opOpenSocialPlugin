<?php op_mobile_page_title(__('Invite App'), $application->getTitle()) ?>

<?php if ($pager->getRawValue() instanceof sfPager && $pager->getNbResults()): ?>
<?php echo __('Select the member who wants to invite to this App.') ?>

<hr color="<?php echo $op_color['core_color_12']?>">
<center>
<?php op_include_pager_total($pager) ?>
</center>
<?php if (count($ids)): ?>
<?php if (1 === count($ids)): ?>
<?php echo __('1 member has been selected.') ?>
<?php else: ?>
<?php echo __('%0% members has been selected.', array('%0%' => count($ids))) ?>
<?php endif; ?>
<?php endif; ?>

<?php echo $form->renderFormTag(url_for('@application_invite?id='.$memberApplication->getId())) ?>
<?php
$list = array();
$checkedIds = array();
foreach ($pager->getResults() as $member)
{
  if (in_array($member->getId(), $sf_data->getRaw('installedFriends')))
  {
    $string = __('%0% is already using', array('%0%' => $member->getName()));
  }
  else
  {
    $checked = false;
    if (in_array($member->id, $ids->getRawValue()))
    {
      $checked = true;
      $checkedIds[] = $member->id;
    }
    $string = '<input type="checkbox" name="ids[]" value="'.$member->id.'"'.($checked ? ' checked' : '').'>'.$member->getName();
  }
  $list[] = $string;
}

op_include_list('memberList', $list);
?>
<?php echo $form->renderHiddenFields() ?>
<?php if ($sf_params->has('callback')): ?>
<input type="hidden" name="callback" value="<?php echo $sf_params->get('callback') ?>">
<?php endif; ?>
<input type="hidden" name="nowpage" value="<?php echo $nowpage ?>">
<?php foreach ($ids as $id): ?>
<?php if (!in_array($id, $checkedIds)): ?>
<input type="hidden" name="ids[]" value="<?php echo $id ?>">
<?php endif; ?>
<?php endforeach; ?>
<center>
<?php if ($pager->getPreviousPage() != $pager->getPage()): ?>
<input type="submit" value="<?php echo __('Previous') ?>" name="previous">
<?php endif; ?>
<?php if ($pager->getNextPage() != $pager->getPage()): ?>
<input type="submit" value="<?php echo __('Next') ?>" name="next">
<?php endif; ?>
</center>
<br>
<center>
<input type="submit" value="<?php echo __('Invite') ?>" name="invite">
</center>
</form>
<?php else: ?>
<?php echo __('%Friend% does not exist.') ?>
<?php endif; ?>
<?php slot('op_mobile_footer_menu') ?>
<?php echo link_to(__('Back to the App'),
  '@application_render?id='.$application->id.($sf_params->has('callback') ? '&url='.urlencode($sf_params->get('callback')) : '')); ?>
<?php end_slot(); ?>
