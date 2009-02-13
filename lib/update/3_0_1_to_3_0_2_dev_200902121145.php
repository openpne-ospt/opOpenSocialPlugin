<?php

class opOpenSocialPluginUpdate_3_0_1_to_3_0_2_dev_200902121145 extends opUpdate
{
  public function update()
  {
    $this->dropTable('application_persistent_data');
  }
}
