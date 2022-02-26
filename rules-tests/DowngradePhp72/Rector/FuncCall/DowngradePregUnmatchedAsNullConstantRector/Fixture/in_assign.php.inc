<?php

namespace Rector\Tests\DowngradePhp72\Rector\FuncCall\DowngradePregUnmatchedAsNullConstantRector\Fixture;

function inAssign($pattern, $subject, $matches, $flags, $offset)
{
    $result = preg_match($pattern, $subject, $matches, $flags | PREG_UNMATCHED_AS_NULL, $offset);
    if ($result === false) {
        throw \Composer\Pcre\PcreException::fromFunction('preg_match', $pattern);
    }
}

?>
