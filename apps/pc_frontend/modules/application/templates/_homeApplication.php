<?php 
include_partial('application/renderApplications', 
array(
  'memberId' => $sf_user->getMember()->getId(),
  'view' => 'home'
)
);
