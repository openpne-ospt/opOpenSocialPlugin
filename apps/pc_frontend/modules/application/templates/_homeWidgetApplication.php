<?php
if (!empty($memberApplication))
{
  include_component('application', 'gadget',
    array(
      'view'       => 'home',
      'member_app' => $memberApplication
    )
  );
}
else
{
  include_box('aaaa','aaaaaaaaaaaaa','aaaaaaaaaaaaaaaaaa');
}
