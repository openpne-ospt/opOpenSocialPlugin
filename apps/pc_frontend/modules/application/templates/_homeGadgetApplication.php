<?php
if (!empty($memberApplication))
{
  include_component('application', 'gadget',
    array(
      'view'      => 'home',
      'memberApp' => $memberApplication
    )
  ); 
}
else
{
  include_box('HomeGadgetApplicationError', __('Error'), __('Failed in loading the application.'));
}
