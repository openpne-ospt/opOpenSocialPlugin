<?php
/**
 * PluginOpenSocialPersonFieldTable
 *
 * @package    opOpenSocialPlugin
 * @subpackage model
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class PluginOpenSocialPersonFieldTable extends Doctrine_Table
{
  public function getNotNullPersonFields()
  {
    return $this->createQuery()
      ->select('field_name IS NOT NULL')
      ->execute();
  }
}
