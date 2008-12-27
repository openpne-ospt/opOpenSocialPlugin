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
 * @param boolean     $is_owner
 * @param Application $application 
 */
function include_application_information_box($id, $aid, $mid = 0, $is_owner = false , $application)
{
  $params = array(
    'id'          => $id,
    'aid'         => $aid,
    'mid'         => $mid,
    'is_owner'    => $is_owner,
    'application' => $application,
  );
  include_partial('application/informationBox', $params);
}
