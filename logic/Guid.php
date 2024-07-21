<?php

function NewGuid() {
    if (function_exists('openssl_random_pseudo_bytes') === true) {
        $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    mt_srand((double) microtime() * 10000);
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45); // "-"
    $uuid = substr($charid, 0, 8) . $hyphen
          . substr($charid, 8, 4) . $hyphen
          . substr($charid, 12, 4) . $hyphen
          . substr($charid, 16, 4) . $hyphen
          . substr($charid, 20, 12);
    return $uuid;

}