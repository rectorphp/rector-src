<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector\Fixture;

class SkipTernary
{
    public function run(?string $maybe, string $part): string
    {
       $address = $maybe;
       $address .= $address ? ' - ' . $part : $part;

       return $address;
    }
}