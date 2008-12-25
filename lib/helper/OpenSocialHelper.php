<?php
/**
 * OpenSocialHelper
 *
 * @package    opOpenSocialPlugin
 * @subpackage helper
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

function include_application_information_box($id, $aid, $mid = 0, $is_owner = false , $title = '', $description = '', $thumbnail = '', $author = '', $author_email = '')
{
  $params = array(
    'id'          => $id,
    'aid'         => $aid,
    'mid'         => $mid,
    'is_owner'    => $is_owner,
    'title'       => $title,
    'description' => $description,
    'thumbnail'   => $thumbnail,
    'author'      => $author,
    'author_email'=> $author_email
  );
  include_partial('application/informationBox', $params);
}
?>
