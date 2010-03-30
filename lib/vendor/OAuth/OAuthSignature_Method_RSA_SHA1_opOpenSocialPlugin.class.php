<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OAuthSignatureMethod_RSA_SHA1_opOpenSocialPlugin
 *
 * @package    opOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.com> 
 */
class OAuthSignatureMethod_RSA_SHA1_opOpenSocialPlugin extends OAuthSignatureMethod_RSA_SHA1 {
  protected function fetch_private_cert(&$request) {
    $file = Shindig_Config::get('private_key_file');
    if (!(file_exists($file) && is_readable($file))) {
      throw new Exception("Error loding private key");
    }

    $private_key = @file_get_contents($file);

    if (! $private_key) {
      throw new Exception("Error loding private key");
    }

    $phrase = Shindig_Config::get('private_key_phrase');
    if (strpos($private_key, '-----BEGIN') === false) {
      $tmp .= "-----BEGIN PRIVATE KEY-----\n";
      $chunks .= str_split($private_key, 64);
      foreach ($chunks as $chunk) {
        $tmp .= $chunk . "\n";
      }
      $tmp .= "-----END PRIVATE KEY-----";
      $private_key = $tmp;
    }
    if (!$rsa_private_key = @openssl_pkey_get_private($private_key, $phrase)) {
      throw new Exception("Could not create the key");
    }

    return $rsa_private_key;
  }
}
