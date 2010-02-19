<?php use_helper('Javascript') ?>
<?php foreach ($pager->getResults() as $member): ?>
<tr>
<td><?php echo $member->getNameAndCount() ?></td>
<?php if (in_array($member->getId(), $sf_data->getRaw('installedFriends'))): ?>
<td><?php echo __('Already using') ?></td>
<?php else: ?>
<td><input type="checkbox" id="checkbox_<?php echo $member->getId() ?>" name="<?php echo $member->getId() ?>" onclick="checkAction(this); false;" /></td>
<?php endif; ?>
</tr>
<?php endforeach; ?>
<tr><td colspan="2">
<div class="pagerRelative">
<?php if ($pager->getPreviousPage() != $pager->getPage()): ?>
<p class="prev">
<?php echo link_to_function('&lt;'.__('Previous', array(), 'pager'), 'changePage('.$pager->getPreviousPage().')') ?>
</p>
<?php endif; ?>
<p class="number">
<?php echo __('%first% - %last% of %total%', array('%first%' => $pager->getFirstIndice(), '%last%' => $pager->getLastIndice(), '%total%' => $pager->getNbResults()), 'pager') ?>
</p>
<?php if ($pager->getNextPage() != $pager->getPage()): ?>
<p class="next">
<?php echo link_to_function(__('Next', array(), 'pager').'&gt;', 'changePage('.$pager->getNextPage().')') ?>
</p>
</div>
<?php endif; ?>
</td></tr>
