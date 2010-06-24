<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialLocationEZweb
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialLocationEZweb extends opOpenSocialLocationAbstract
{
  protected $params = null;

  public function renderGetLocationCell($callback, array $params)
  {
    sfContext::getInstance()->getUser()->setFlash('op_ezweb_location_params', serialize($params));
    $result = '<form action="device:location?url='.urlencode($callback).'&foo=bar'.'" method="POST">
<input type="submit" value="'.__('Yes').'">
</form>';
    return $result;
  }

  public function renderGetLocationGps($callback, array $params)
  {
    sfContext::getInstance()->getUser()->setFlash('op_ezweb_location_params', serialize($params));
    $result = '<form action="device:gpsone?url='.urlencode($callback).'&ver=1" method="POST">
<input type="hidden" name="detum" value="0">
<input type="hidden" name="unit" value="1">
<input type="hidden" name="acry" value="0">
<input type="hidden" name="number" value="0">
<input type="submit" value="'.__('Yes').'">
</form>
';
    return $result;
  }

  public function getParameter($name, $default = null)
  {
    if (null === $this->params)
    {
      if (sfContext::getInstance()->getUser()->hasFlash('op_ezweb_location_params'))
      {
        $this->params = unserialize(sfContext::getInstance()->getUser()->getFlash('op_ezweb_location_params'));
      }
      else
      {
        $this->params = array();
      }
    }

    if (is_array($this->params) && isset($this->params[$name]))
    {
      return $this->params[$name];
    }

    if ($this->request->hasParameter($name))
    {
      return $this->request->getParameter($name, $default);
    }

    return $default;
  }
}
