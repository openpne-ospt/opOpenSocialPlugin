<?php op_mobile_page_title(__('Remove the App'), $application->getTitle()) ?>
<?php
op_include_yesno('yesNo', new BaseForm(), new BaseForm(array(), array(), null), array(
  'body' => __('Do you really remove this App?'),
  'no_url' => url_for('@my_application_list'),
  'no_method' => 'get',
  'align' => 'center',
))
?>
