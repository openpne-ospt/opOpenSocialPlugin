<?php
$memberApps = MemberApplicationPeer::getHomeMemberApplications($memberId, $sf_user->getMember()->getId(), SnsConfigPeer::get('home_application_limit', 3));
foreach ($memberApps as $memberApp)
{
  include_component('application','gadget',
    array(
      'view'       => 'home',
      'memberApp' => $memberApp
    )
  );
}
