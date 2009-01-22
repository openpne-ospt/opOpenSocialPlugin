<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opOpenSocialPluginUpdate_1_0beta3_to_1_0beta4_dev_200901221408 extends opUpdate
{
  public function update()
  {
    $this->changeColumn('member_application', 'is_home_widget', 'boolean', array(
      'notnull'    => true,
      'default'    => 0,
      'columnName' => 'is_gadget',
    ));
  }
}
