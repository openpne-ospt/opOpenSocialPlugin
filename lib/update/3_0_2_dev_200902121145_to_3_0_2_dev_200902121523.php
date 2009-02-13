<?php

class opOpenSocialPluginUpdate_3_0_2_dev_200902121145_to_3_0_2_dev_200902121523 extends opUpdate
{
  public function update()
  {
    $this->createTable(
      'application_persistent_data',
      array(
        'id' => array('type' => 'integer', 'primary' => true, 'autoincrement' => true),
        'application_id' => array('type' => 'integer'),
        'member_id' => array('type' => 'integer'),
        'name' => array('type' => 'string', 'length' => 128),
        'value' => array('type' => 'string', 'length' => 65535),
      ),
      array(
        'foreignKeys' => array(
          array(
            'local' => 'application_id',
            'foreign' => 'id',
            'foreignTable' => 'application',
            'onDelete' => 'CASCADE',
          ),
          array(
            'local' => 'member_id',
            'foreign' => 'id',
            'foreignTable' => 'member',
            'onDelete' => 'CASCADE',
          )
        )
      )
    );
  }
}
