<?php

/**
 * HELPERS.PHP
 * This file is included with every server ping.
 * It's a collection of helper functions we didn't write.
 */

// Used to generate a random string for our login cookie.
// https://stackoverflow.com/a/13733588
function getToken($length) {
  $token = "";
  $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
  $codeAlphabet.= "0123456789";
  $max = strlen($codeAlphabet);

  for ($i=0; $i < $length; $i++) {
    $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
  }

  return $token;
}

// Generates a cryptographically secure random number between $min and $max.
// https://stackoverflow.com/a/13733588
function crypto_rand_secure($min, $max) {
  $range = $max - $min;
  if ($range < 1) return $min;
  $log = ceil(log($range, 2));
  $bytes = (int) ($log / 8) + 1;
  $bits = (int) $log + 1;
  $filter = (int) (1 << $bits) - 1;

  do {
    $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
    $rnd = $rnd & $filter;
  } while ($rnd > $range);

  return $min + $rnd;
}