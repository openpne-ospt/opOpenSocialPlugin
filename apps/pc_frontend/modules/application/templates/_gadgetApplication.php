<?php
if (!empty($memberApplication))
{
  use_helper('OpenSocial');
  include_component('application', 'gadget',
    array(
      'view'       => 'home',
      'memberApp' => $memberApplication
    )
  );
}
else
{
  include_box('HomeApplicationError', __('Error'), __('Failed in loading the application.'));
}
