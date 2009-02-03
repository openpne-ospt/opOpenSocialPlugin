<?php
$options = array(
  'title'  => __('Remove the application').": ".$appTitle,
  'body'   => __('Do you really delete this application?'),
  'no_url' =>  url_for('application/list'),
  'no_method' => 'get',
);

op_include_yesno('ApplicationRemoveConfirm', new sfForm(), new sfForm(array(),array(),false), $options);

