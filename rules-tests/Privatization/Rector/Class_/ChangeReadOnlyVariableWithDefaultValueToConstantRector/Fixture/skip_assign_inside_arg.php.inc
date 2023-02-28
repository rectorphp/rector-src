<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector\Fixture;

final class SkipAssignInsideArg
{
    public function run()
    {
        $c = base64_decode(str_replace(['--', '_'], ['+', '/'], $key));

        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CFB");
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
    }
}
