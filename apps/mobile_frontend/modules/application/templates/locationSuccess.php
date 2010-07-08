<?php op_mobile_page_title($application->getTitle()) ?>

<div class="block">
<?php echo __('Do you send your location to this App?') ?>
</div>
<div align="center">
<?php if ('gps' == $sf_params->get('type')): ?>
<?php echo $location->getRawValue()->renderGetLocationGps(url_for('@application_accept_location?id='.$application->id.'&tk='.$tk, true)) ?>
<?php else: ?>
<?php echo $location->getRawValue()->renderGetLocationCell(url_for('@application_accept_location?id='.$application->id.'&tk='.$tk, true)) ?>
<?php endif; ?>
<form action="<?php echo url_for('@application_render?id='.$application->id) ?>" method="<?php echo $sf_context->getRequest()->isMethod(sfWebRequest::GET) ? 'GET' : 'POST' ?>">
<?php if($sf_params->has('callback')): ?>
<input type="hidden" name="url" value="<?php echo $sf_params->get('callback') ?>">
<?php endif; ?>
<input type="submit" value="<?php echo __('No') ?>"><br>
</form>
</div>
