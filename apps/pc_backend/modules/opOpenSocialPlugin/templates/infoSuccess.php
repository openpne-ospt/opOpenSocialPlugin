<?php use_helper('Javascript') ?>

<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('About App') ?>
<?php end_slot() ?>

<table>
<tr><th colspan="2"><?php echo __('About App') ?></th></tr>
<tr><th><?php echo __('App Type') ?></th><td><?php echo implode(', ', $application->getApplicationTypes()->getRawValue()) ?></td></tr>
<tr><th><?php echo __('Name') ?></th><td><?php echo $application->getTitle() ?></td></tr>
<tr><th><?php echo __('Status') ?></th><td><?php echo $application->isActive() ? __('Active') : __('Inactive') ?></td></tr>
<?php if ($application->getMemberId()): ?>
<tr><th><?php echo __('Member who Added App') ?></th><td><?php echo $application->getAdditionalMember()->getName() ?></td></tr>
<?php endif; ?>
<tr><th><?php echo __('App URL') ?></th><td><?php echo $application->getUrl() ?></td></tr>
<tr><th><?php echo __('Title URL') ?></th><td>
<?php if ($application->getTitleUrl()) : ?>
<?php echo link_to(null,$application->getTitleUrl(),array('target' => '_blank')) ?>
<?php endif ?>
</td></tr>
<tr><th><?php echo __('Screenshot') ?></th><td>
<?php if ($application->getScreenshot()) : ?>
<?php echo image_tag($application->getScreenshot(), array('alt' => $application->getTitle())) ?>
<?php endif ?>
</td></tr>
<tr><th><?php echo __('Thumbnail') ?></th><td>
<?php if ($application->getThumbnail()) : ?>
<?php echo image_tag($application->getThumbnail(), array('alt' => $application->getTitle())) ?>
<?php endif ?>
</td></tr>
<tr><th><?php echo __('Description') ?></th><td><?php echo $application->getDescription() ?></td></tr>
<tr><th><?php echo __('Users') ?></th><td><?php echo $application->countMembers() ?></td></tr>
<tr><th><?php echo __('Last Updated') ?></th><td><?php echo $application->getUpdatedAt() ?></td></tr>
<tr><th colspan="2"><?php echo __('About Author') ?></th></tr>
<tr><th><?php echo __('Name') ?></th><td><?php echo $application->getAuthorEmail() ? mail_to($application->getAuthorEmail(), $application->getAuthor(), array('encode' => true)) : $application->getAuthor() ?></td></tr>
<tr><th><?php echo __('Affiliation') ?></th><td><?php echo $application->getAuthorAffiliation() ?></td></tr>
<tr><th><?php echo __('Aboutme') ?></th><td><?php echo $application->getAuthorAboutme() ?></td></tr>
<tr><th><?php echo __('Photo') ?></th><td>
<?php if($application->getAuthorPhoto()) : ?>
<?php echo image_tag($application->getAuthorPhoto(), array('alt' => $application->getAuthor())) ?>
<?php endif ?>
</td></tr>
<tr><th><?php echo __('Link') ?></th><td>
<?php if ($application->getAuthorLink()) : ?>
<?php echo link_to(null,$application->getAuthorLink(),array('target' => '_blank')) ?>
<?php endif ?>
</td></tr>
<tr><th><?php echo __('Quote') ?></th><td><?php echo $application->getAuthorQuote() ?></td></tr>

<tr><th colspan="2"><?php echo __('Information for Developer') ?></th></tr>
<tr><th>Consumer key</th><td><?php echo $application->getConsumerKey() ?></td></tr>
<tr><th>Consumer secret</th><td>

<div id="oauth_consumer_secret"></div>
<?php if ($application->getConsumerSecret()): ?>
<?php echo button_to_remote(__('Show consumer secret'), array(
  'update' => 'oauth_consumer_secret',
  'url' => '@op_opensocial_show_consumer_secret?id='.$application->getId(),
  'method' => 'get'
)) ?><br />
<?php echo button_to(__('Reset consumer secret'), '@op_opensocial_update_consumer_secret?id='.$application->getId()) ?><br />
<?php echo button_to(__('Delete consumer secret'), '@op_opensocial_delete_consumer_secret?id='.$application->getId()) ?>
<?php else: ?>
<?php echo button_to(__('Generate consumer secret'), '@op_opensocial_update_consumer_secret?id='.$application->getId()) ?>
<?php endif; ?>

</td></tr>
<tr><th>Signature method</th><td>HMAC-SHA1</td></tr>
<tr><td colspan="2">
<?php echo button_to(__('Delete'),'@op_opensocial_delete?id='.$sf_request->getParameter('id'), array('style' => 'float:left')) ?> 
<?php $form = new sfForm() ?>
<?php echo $form->renderFormTag(url_for('@op_opensocial_update?id='.$sf_request->getParameter('id')), array('style' => 'float:left')) ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Update') ?>" />
</form>
<?php if ($application->isActive()): ?>
<?php echo $form->renderFormTag(url_for('@op_opensocial_inactivate?id='.$sf_request->getParameter('id'))) ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Inactivate') ?>" />
</form>
<?php else: ?>
<?php echo $form->renderFormTag(url_for('@op_opensocial_activate?id='.$sf_request->getParameter('id'))) ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Activate') ?>" />
</form>
<?php endif; ?>
</td></tr>
</table>

<?php if (isset($mobileForm)): ?>
<h3><?php echo __('Mobile App Configuration') ?></h3>
<?php echo $mobileForm->renderFormTag(url_for('@op_opensocial_info?id='.$application->id)) ?>
<table>
<?php echo $mobileForm ?>
<tr><td colspan="2"><input type="submit" value="<?php echo __('Save') ?>" /></td></tr>
</table>
</form>
<?php endif; ?>
