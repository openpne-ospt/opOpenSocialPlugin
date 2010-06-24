<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialLocationDocomo
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialLocationDocomo extends opOpenSocialLocationAbstract
{
  protected $params = null;

  public function renderGetLocationCell($callback, array $params)
  {
    $result = '<form action="http://w1m.docomo.ne.jp/cp/iarea" method="POST">
<input type="hidden" name="ecode" value="OPENAREACODE">
<input type="hidden" name="msn" value="OPENAREAKEY">
<input type="hidden" name="nl" value="'.$callback.'">
<input type="hidden" name="posinfo" value="1">
';

    $arg = 1;
    if (!$this->request->isCookie() && defined('SID') && SID)
    {
      $result .= '<input type="hidden" name="arg'.($arg++).'" value="'.SID.'">';
    }

    if (count($params) >= 1)
    {
      $result .= '<input type="hidden" name="arg'.($arg++).'" value="params='.base64_encode(serialize($params)).'">';
    }

    $result .= '
<input type="submit" value="'.__('Yes').'">
</form>';

    return $result;
  }

  public function renderGetLocationGps($callback, array $params)
  {
    $p = array();
    if (!$this->request->isCookie() && defined('SID') && SID)
    {
      $p[] = SID;
    }
    if (count($params) >= 1)
    {
      $p[] = 'params='.base64_encode(serialize($params));
    }

    $result = '<form action="'.$callback.'?'.(count($p) ? implode('&', $p) : '').'" method="POST" lcs>';
    $result .= '<input type="submit" value="'.__('Yes').'">';
    $result .= '</form>';
    return $result;
  }

  public function getParameter($name, $default = null)
  {
    if (null === $this->params)
    {
      if ($this->request->hasParameter('params'))
      {
        $this->params = unserialize(base64_decode($this->request->getParameter('params')));
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
