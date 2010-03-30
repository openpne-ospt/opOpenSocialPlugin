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
  * @return Doctrine_Collection
  */
  public function getQueueGroups()
  {
    return $this->createQuery()
      ->distinct()
      ->select('application_id, name')
      ->execute(array(), Doctrine::HYDRATE_NONE);
  }
}
