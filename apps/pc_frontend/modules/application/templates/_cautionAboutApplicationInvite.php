<?php if ($count): ?>
<p class="caution">
<?php echo __('You\'ve gotten %0% invitations to app.', array('%0%' => $count)) ?>
&nbsp;
<?php echo link_to(__('Go to Confirmation Page'), '@confirmation_list?category=invitation_app') ?>
</p>
<?php endif; ?>
