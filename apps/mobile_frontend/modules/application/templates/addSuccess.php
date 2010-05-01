<?php op_mobile_page_title(__('Add App'), $application->getTitle()) ?>
<?php
op_include_yesno('yesNo', new BaseForm(), new BaseForm(array(), array(), null), array(
  'body' => __('Do you wish to install this App?').__('The App might use your profile and your %friend% information.'),
  'yes_url' => url_for('@application_add?id='.$application->getId().($sf_params->has('invite') ? '&invite='.$sf_params->get('invite') : '')),
  'no_url' => url_for('@my_application_list'),
  'no_method' => 'get',
  'align' => 'center',
))
?>
