<?php
if (SnsConfigPeer::get('is_view_profile_application', true))
{
  include_partial('application/renderApplications', array(
    'memberId' => $sf_request->getParameter('id',$sf_user->getMember()->getId()),
    'view' => 'profile'
  ));
}
