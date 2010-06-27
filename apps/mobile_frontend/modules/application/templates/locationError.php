<?php op_mobile_page_title($application->getTitle(), __('Error')) ?>

<div class="block">
あなたの端末では位置情報の取得が出来ません。
</div>

<div align="center">
<form action="<?php echo url_for('@application_render?id='.$application->id) ?>" method="<?php echo $sf_context->getRequest()->isMethod(sfWebRequest::GET) ? 'GET' : 'POST' ?>">
<?php if($sf_params->has('callback')): ?>
<input type="hidden" name="url" value="<?php echo $sf_params->get('callback') ?>">
<?php endif; ?>
<input type="submit" value="<?php echo __('Back') ?>">
</form>
</div>
