<?php
$options = array(
  'title'   => __('Remove the App').": ".$application->getTitle(),
  'body'    => __('Do you really remove this App?'),
  'no_url'  => url_for('@my_application_list'),
  'no_method' => 'get',
);

op_include_yesno('ApplicationRemoveConfirm', new BaseForm(), new BaseForm(array(),array(),false), $options);

