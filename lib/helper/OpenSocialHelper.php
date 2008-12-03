<?php
/**
 * OpenSocialHelper
 *
 * @package    opOpenSocialPlugin
 * @subpackage helper
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

function include_application_information_box($id, $mid, $title = '', $description = '', $thumbnail = '', $author = '', $author_email = '')
{
  $params = array(
    'id'          => $id,
    'mid'         => $mid,
    'title'       => $title,
    'description' => $description,
    'thumbnail'   => $thumbnail,
    'author'      => $author,
    'author_email'=> $author_email
  );
  include_partial('global/applicationInformationBox', $params);
}
?>
