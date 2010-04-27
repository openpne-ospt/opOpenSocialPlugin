<?php op_mobile_page_title('Add App', $application->getTitle()) ?>
<?php
op_include_yesno('yesNo', new BaseForm(), new BaseForm(array(), array(), null), array(
  'body' => __('Do you wish to install this App?').__('The App might use your profile and your %friend% information.'),
  'no_url' => url_for('@my_application_list'),
  'no_method' => 'get',
  'align' => 'center',
))
?>
