<?php
/**
 * OpenSocialHelper
 *
 * @package    OpenPNE
 * @subpackage helper
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

/**
 * include application information box
 *
 * @param integer     $id
 * @param integer     $aid         An application id
 * @param integer     $mid         A module id
 * @param boolean     $isOwner
 * @param Application $application 
 */
function include_application_information_box($id, $aid, $mid = 0, $isOwner = false , $application)
{
  $params = array(
    'id'          => $id,
    'aid'         => $aid,
    'mid'         => $mid,
    'isOwner'     => $isOwner,
    'application' => $application,
  );
  include_partial('application/informationBox', $params);
}
