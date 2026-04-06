<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * rc4 - backward compatible RC4 implementation (kept for legacy support)
 * @param string $key
 * @param string $str
 * @return string
 */
function rc4($key, $str) {
    $s = [];
    for ($i = 0; $i < 256; $i++) {
        $s[$i] = $i;
    }

    $j = 0;
    $key_len = strlen($key);

    for ($i = 0; $i < 256; $i++) {
        $j = ($j + $s[$i] + ord($key[$i % $key_len])) % 256;
        $tmp = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $tmp;
    }

    $i = $j = 0;
    $res = "";

    for ($y = 0; $y < strlen($str); $y++) {
        $i = ($i + 1) % 256;
        $j = ($j + $s[$i]) % 256;

        $tmp = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $tmp;

        $k = $s[ ($s[$i] + $s[$j]) % 256 ];
        $res .= chr(ord($str[$y]) ^ $k);
    }

    return $res;
}

/**
 * AES-256-GCM helper (example) - recommended for migration
 * - key must be 32 bytes
 * - returns base64(nonce|tag|ciphertext)
 */
function aes_gcm_encrypt($key, $plaintext, $aad = '') {
    if (strlen($key) < 32) {
        // pad or derive key using hash
        $key = hash('sha256', $key, true);
    } else {
        $key = substr($key, 0, 32);
    }
    $iv = random_bytes(12); // 96-bit nonce recommended for GCM
    $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag, $aad);
    return base64_encode($iv . $tag . $ciphertext);
}

function aes_gcm_decrypt($key, $b64, $aad = '') {
    if (strlen($key) < 32) {
        $key = hash('sha256', $key, true);
    } else {
        $key = substr($key, 0, 32);
    }
    $raw = base64_decode($b64);
    $iv = substr($raw, 0, 12);
    $tag = substr($raw, 12, 16);
    $ciphertext = substr($raw, 28);
    return openssl_decrypt($ciphertext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag, $aad);
}

function hash256($str)
{
    return hash('sha256', $str);
}