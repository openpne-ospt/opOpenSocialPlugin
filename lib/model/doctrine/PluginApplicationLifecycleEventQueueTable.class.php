<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginApplicationLifecycleEventQueueTable
 *
 * @package    opOpenSocialPlugin
 * @subpackage model
 * @author     Shogo Kawahara <kawahara@tejimaya.com>
 */

class PluginApplicationLifecycleEventQueueTable extends Doctrine_Table
{
 /**
  * getQueueGroups
  *
  * @return array
  */
  public function getQueueGroups()
  {
    return $this->createQuery()
      ->distinct()
      ->select('application_id')
      ->execute(array(), Doctrine::HYDRATE_NONE);
  }

 /**
  * getQueuesByApplicationId
  *
  * @param integer $applicationId
  * @param integer $limit
  * @return Doctrine_Collection
  */
  public function getQueuesByApplicationId($applicationId, $limit = null)
  {
    $query = $this->createQuery()
      ->where('application_id = ?', $applicationId);

    if (is_numeric($limit) && $limit)
    {
      $query->limit($limit);
    }

    return $query->execute();
  }
}
